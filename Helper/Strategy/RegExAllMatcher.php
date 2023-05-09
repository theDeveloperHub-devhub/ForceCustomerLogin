<?php

namespace DevHub\ForceCustomerLogin\Helper\Strategy;

use DevHub\ForceCustomerLogin\Model\WhitelistEntry;

class RegExAllMatcher implements StrategyInterface
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
        return (
            preg_match(
                sprintf(
                    '#^.*%s/?.*$#i',
                    $this->quoteRule((string)$rule->getUrlRule())
                ),
                $this->getCleanUrl($url)
            ) === 1
        );
    }

    /**
     * Quote delimiter in whitelist entry rule
     *
     * @param string $rule
     * @param string $delimiter
     * @return string
     */
    private function quoteRule($rule, $delimiter = '#')
    {
        return str_replace($delimiter, \sprintf('\%s', $delimiter), $rule);
    }

    /**
     * @param string $url
     * @return string
     */
    private function getCleanUrl($url)
    {
        return str_replace(self::REWRITE_DISABLED_URL_PREFIX, '', $url);
    }
}
