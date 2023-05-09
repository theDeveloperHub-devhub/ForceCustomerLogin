<?php

namespace DevHub\ForceCustomerLogin\Controller\Adminhtml\Manage;

use DevHub\ForceCustomerLogin\Api\Repository\WhitelistRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Webapi\Exception;

class Save extends Action
{
    /**
     * @var WhitelistRepositoryInterface
     */
    private $whitelistRepository;
    /**
     * @var RedirectFactory
     */
    private $redirectFactory;

    /**
     * Save constructor.
     *
     * @param WhitelistRepositoryInterface $whitelistRepository
     * @param Context $context
     */
    public function __construct(
        WhitelistRepositoryInterface $whitelistRepository,
        Context $context
    ) {
        parent::__construct($context);
        $this->whitelistRepository = $whitelistRepository;
        $this->redirectFactory = $context->getResultRedirectFactory();
    }

    /**
     * Save action.
     *
     * @return ResultInterface|ResponseInterface
     */
    public function execute()
    {
        $result = $this->redirectFactory->create();

        try {
            $whitelistEntry = $this->whitelistRepository->createEntry(
                $this->getRequest()->getParam('whitelist_entry_id'),
                $this->getRequest()->getParam('label'),
                $this->getRequest()->getParam('url_rule'),
                $this->getRequest()->getParam('strategy'),
                $this->getRequest()->getParam('store_id', 0)
            );

            if (!$whitelistEntry->getId() ||
                !$whitelistEntry->getEditable()) {
                throw new \RuntimeException(
                    __('Could not persist manage entry.')
                );
            }
            $this->messageManager->addSuccessMessage(
                __('Whitelist entry successfully saved.')
            );

            $result->setHttpResponseCode(200);
            $result->setPath('ForceCustomerLogin/Manage/index');
        } catch (\Exception $e) {
            $result->setHttpResponseCode(Exception::HTTP_INTERNAL_ERROR);
            $this->messageManager->addErrorMessage(
                \sprintf(
                    __('Could not add record: %s'),
                    $e->getMessage()
                )
            );

            $result->setPath(
                'ForceCustomerLogin/Manage/Create',
                [
                    'label' => \base64_encode($this->getRequest()->getParam('label')),
                    'url_rule' => \base64_encode($this->getRequest()->getParam('url_rule')),
                    'strategy' => \base64_encode($this->getRequest()->getParam('strategy')),
                    'store_id' => \base64_encode($this->getRequest()->getParam('store_id', 0))
                ]
            );
        }

        return $result;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DevHub_ForceCustomerLogin::devhub_force_customer_login_manage');
    }
}
