<?php

namespace DevHub\ForceCustomerLogin\Helper\Strategy;

use DevHub\ForceCustomerLogin\Model\WhitelistEntry;

interface StrategyInterface
{
    /**
     * @param string $url
     * @param WhitelistEntry $rule
     * @return bool
     */
    public function isMatch($url, WhitelistEntry $rule);

    /**
     * @return string
     */
    public function getName();
}
