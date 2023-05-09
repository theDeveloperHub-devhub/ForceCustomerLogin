<?php

namespace DevHub\ForceCustomerLogin\Helper\Strategy;

use DevHub\ForceCustomerLogin\Model\WhitelistEntry;

class StaticMatcher implements StrategyInterface
{
    /*
     * Rewrite
     */
    const REWRITE_DISABLED_URL_PREFIX = '/index.php';

    /**+
     * @var string
     */
    private $name;

    /**
     * RegExAllMatcher constructor.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function isMatch($url, WhitelistEntry $rule)
    {
        return ($this->getCanonicalUrl($url) === $this->getCanonicalRule((string)$rule->getUrlRule()));
    }

    /**
     * @param string $url
     * @return string
     */
    private function getCanonicalUrl($url)
    {
        $canonicalUrl = rtrim($url, '/') . '/';
        return str_replace(self::REWRITE_DISABLED_URL_PREFIX, '', $canonicalUrl);
    }

    /**
     * @param string $rule
     * @return string
     */
    private function getCanonicalRule($rule)
    {
        return rtrim($rule, '/') . '/';
    }
}
