<?php

namespace DevHub\ForceCustomerLogin\Controller;

use DevHub\ForceCustomerLogin\Api\Controller\LoginCheckInterface;
use DevHub\ForceCustomerLogin\Api\Repository\WhitelistRepositoryInterface;
use DevHub\ForceCustomerLogin\Helper\Strategy\StrategyManager;
use DevHub\ForceCustomerLogin\Model\Session;
use DevHub\ForceCustomerLogin\Model\WhitelistEntry;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\Http as ResponseHttp;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

class LoginCheck implements LoginCheckInterface
{
    /**
     * @var CustomerSession
     */
    private $customerSession;
    /**
     * @var Session
     */
    private $session;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var WhitelistRepositoryInterface
     */
    private $whitelistRepository;
    /**
     * @var StrategyManager
     */
    private $strategyManager;
    /**
     * @var ModuleCheck
     */
    private $moduleCheck;
    /**
     * @var \Magento\Framework\UrlInterface
     */
    private $url;
    /**
     * @var Http|RequestInterface
     */
    private $request;
    /**
     * @var ResponseHttp
     */
    private $response;
    /**
     * @var PasswordResetHelper
     */
    private $passwordResetHelper;

    /**
     * @param Context $context
     * @param CustomerSession $customerSession
     * @param Session $session
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     * @param WhitelistRepositoryInterface $whitelistRepository
     * @param StrategyManager $strategyManager
     * @param ModuleCheck $moduleCheck
     * @param ResponseHttp $response
     * @param PasswordResetHelper $passwordResetHelper
     */
    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        Session $session,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        WhitelistRepositoryInterface $whitelistRepository,
        StrategyManager $strategyManager,
        ModuleCheck $moduleCheck,
        ResponseHttp $response,
        PasswordResetHelper $passwordResetHelper
    ) {
        $this->customerSession = $customerSession;
        $this->session = $session;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->whitelistRepository = $whitelistRepository;
        $this->strategyManager = $strategyManager;
        $this->moduleCheck = $moduleCheck;
        $this->response = $response;
        $this->passwordResetHelper = $passwordResetHelper;
        $this->request = $context->getRequest();
        $this->url = $context->getUrl();
    }

    /**
     * Manages redirect
     *
     * @return bool TRUE if redirection is applied, else FALSE
     * @throws NoSuchEntityException
     */
    public function execute()
    {
        if ($this->moduleCheck->isModuleEnabled() === false) {
            return false;
        }

        // if user is logged in, every thing is fine
        if ($this->customerSession instanceof CustomerSession &&
            $this->customerSession->isLoggedIn()) {
            return false;
        }

        // allow "Login as customer" via Magento Admin
        $sessionData = $this->session->getData();
        $afterLoginReferer = $sessionData['after_login_referer'] ?? '';
        if (strpos($afterLoginReferer, 'loginascustomer') !== false) {
            return false;
        }

        $url = $this->url->getCurrentUrl();
        $urlParts = \parse_url($url);
        $path = is_array($urlParts) && isset($urlParts['path']) ? $urlParts['path'] : '';
        $targetUrl = $this->getTargetUrl();

        // skip dynamic asset files
        $assetPathParts = explode('/', rtrim($path, '/'));
        $assetPart = array_pop($assetPathParts);
        if ($assetPart && preg_match('#\.(css|js|png|jpe?g|gif|svg)#imsU', $assetPart)) {
            return false;
        }

        // current path is already pointing to target url, no redirect needed
        if (strpos($path, $targetUrl) !== false) {
            return false;
        }

        // Explicit behaviour for password reset creation
        if ($this->passwordResetHelper->processDirectCreatePasswordRequest($this->url, $this->request)) {
            return false;
        }

        if (!$this->request->isPost() && strpos($url, 'customer/account/createpost') !== false) {
            /** @var Store $store */
            $store = $this->storeManager->getStore();
            $this->response->setNoCacheHeaders();
            $this->response->setRedirect($store->getUrl('customer/account'));
            $this->response->sendResponse();
            return true;
        }

        // check if current url is a match with one of the ignored urls
        /** @var WhitelistEntry $rule */
        foreach ($this->whitelistRepository->getCollection()->getItems() as $rule) {
            $strategy = $rule->getStrategy();
            if (!$strategy) {
                return false;
            }
            $strategy = $this->strategyManager->get($strategy);
            if ($strategy->isMatch($path, $rule)) {
                return false;
            }
        }

        // Set Url To redirect using standard method of magento
        if (strpos($url, 'customer/section/load') === false && strpos($url, '_=') === false) {
            $this->customerSession->setBeforeAuthUrl($url);
        }

        // Add any GET query parameters back to the path after making our url checks.
        if (is_array($urlParts) && isset($urlParts['query']) && !empty($urlParts['query'])) {
            $path .= '?' . $urlParts['query'];
        }

        if (!$this->isAjaxRequest()) {
            $this->session->setAfterLoginReferer($path);
        }

        $this->response->setNoCacheHeaders();
        $this->response->setRedirect($this->getRedirectUrl($targetUrl));
        $this->response->sendResponse();

        return true;
    }

    /**
     * @return string
     */
    private function getTargetUrl()
    {
        return $this->scopeConfig->getValue(
            self::MODULE_CONFIG_TARGET,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return bool
     */
    private function getForceSecureRedirectOption()
    {
        return (bool)$this->scopeConfig->getValue(
            self::MODULE_CONFIG_FORCE_SECURE_REDIRECT,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    private function getBaseUrl()
    {
        $secure = $this->getForceSecureRedirectOption();
        $secure = ($secure === true) ? true : null;
        /** @var Store $store */
        $store = $this->storeManager->getStore();
        return $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_LINK, $secure);
    }

    /**
     * Check if a request is AJAX request
     *
     * @return bool
     */
    private function isAjaxRequest()
    {
        if ($this->request instanceof Http) {
            return $this->request->isAjax();
        }
        if ($this->request->getParam('ajax') || $this->request->getParam('isAjax')) {
            return true;
        }
        return false;
    }

    /**
     * @param string $targetUrl
     * @return string
     * @throws NoSuchEntityException
     */
    private function getRedirectUrl($targetUrl)
    {
        return \sprintf(
            '%s%s',
            $this->getBaseUrl(),
            $targetUrl
        );
    }
}
