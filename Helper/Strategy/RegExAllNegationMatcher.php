<?php

namespace DevHub\ForceCustomerLogin\Helper\Strategy;

use DevHub\ForceCustomerLogin\Model\WhitelistEntry;

class RegExAllNegationMatcher extends RegExAllMatcher implements StrategyInterface
{
    /**
     * @inheritDoc
     */
    public function isMatch($url, WhitelistEntry $rule)
    {
        return !parent::isMatch($url, $rule);
    }
}
