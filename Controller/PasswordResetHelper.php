<?php

namespace DevHub\ForceCustomerLogin\Controller;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;

class PasswordResetHelper
{
    const CREATE_PASSWORD_DIRECT_URL_SCHEME = '/customer/account/createpassword/\?.*token=';

    /**
     * @param UrlInterface $urlInstance
     * @param RequestInterface $request
     * @return bool
     */
    public function processDirectCreatePasswordRequest(UrlInterface $urlInstance, RequestInterface $request)
    {
        $url = $urlInstance->getCurrentUrl();

        // Explicit behaviour for special urls
        if (preg_match(
                sprintf(
                    '#^.*%s.*$#i',
                    self::CREATE_PASSWORD_DIRECT_URL_SCHEME
                ),
                $url
            ) === 1) {
            $params = $request->getParams();
            unset($params['token']);
            $request->setParams($params);
            return true;
        }
        return false;
    }
}
