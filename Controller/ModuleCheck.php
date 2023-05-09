<?php

namespace DevHub\ForceCustomerLogin\Controller;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class ModuleCheck
{
    /*
     * Configuration
     */
    const MODULE_CONFIG_ENABLED = 'customer/DevHub_ForceCustomerLogin/enabled';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * ModuleCheck constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     */
    public function isModuleEnabled()
    {
        return !!$this->scopeConfig->getValue(
            self::MODULE_CONFIG_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }
}
