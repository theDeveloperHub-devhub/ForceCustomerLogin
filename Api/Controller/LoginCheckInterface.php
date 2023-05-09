<?php

namespace DevHub\ForceCustomerLogin\Api\Controller;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\ResponseInterface;

interface LoginCheckInterface
{
    /*
     * Configuration
     */
    const MODULE_CONFIG_TARGET = 'customer/DevHub_ForceCustomerLogin/url';
    /*
     * Configuration
     */
    const MODULE_CONFIG_FORCE_SECURE_REDIRECT = 'customer/DevHub_ForceCustomerLogin/force_secure_redirect';

    /**
     * Manages redirect
     *
     * @return bool TRUE if redirection is applied, else FALSE
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute();
}
