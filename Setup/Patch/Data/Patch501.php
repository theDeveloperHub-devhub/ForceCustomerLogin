<?php

namespace DevHub\ForceCustomerLogin\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class Patch501 implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private ModuleDataSetupInterface $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(ModuleDataSetupInterface $moduleDataSetup)
    {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * @inheritdoc
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $whitelistEntries = [
            $this->getWhitelistEntryAsArray(0, 'Rest API', '/rest', true, 'regex-all'),
            $this->getWhitelistEntryAsArray(0, 'Customer Account Login', '/customer/account/login', true, 'regex-all'),
            $this->getWhitelistEntryAsArray(0, 'Customer Account Logout', '/customer/account/logout', true, 'regex-all'),
            $this->getWhitelistEntryAsArray(0, 'Customer Account Logout Success', '/customer/account/logoutSuccess', true, 'regex-all'),
            $this->getWhitelistEntryAsArray(0, 'Customer Account Create', '/customer/account/create', true, 'regex-all'),
            $this->getWhitelistEntryAsArray(0, 'Customer Account Create Password', '/customer/account/createPassword', true, 'regex-all'),
            $this->getWhitelistEntryAsArray(0, 'Customer Account Forgot Password', '/customer/account/forgotpassword', true, 'regex-all'),
            $this->getWhitelistEntryAsArray(0, 'Customer Account Forgot Password Post', '/customer/account/forgotpasswordpost', true, 'regex-all'),
            $this->getWhitelistEntryAsArray(0, 'Customer Section Load', '/customer/section/load', true, 'regex-all'),
            $this->getWhitelistEntryAsArray(0, 'Contact Us', '/contact', true, 'regex-all'),
            $this->getWhitelistEntryAsArray(0, 'Help', '/help', true, 'regex-all'),
            $this->getWhitelistEntryAsArray(0, 'Sitemap.xml', '/sitemap.xml', true, 'regex-all'),
            $this->getWhitelistEntryAsArray(0, 'Robots.txt', '/robots.txt', true, 'regex-all'),
            $this->getWhitelistEntryAsArray(0, 'Customer Account Dashboard', '/customer/account', true, 'regex-all'),
            $this->getWhitelistEntryAsArray(0, 'Customer Account Reset Password Post', '/customer/account/resetpasswordpost'),
            $this->getWhitelistEntryAsArray(0, 'Varnish ESI url', '/page_cache/block/esi/blocks'),
            $this->getWhitelistEntryAsArray(0, 'Store-Switcher Redirect', '/stores/store/redirect'),
            $this->getWhitelistEntryAsArray(0, 'Store-Switcher Switch', '/stores/store/switch'),
            $this->getWhitelistEntryAsArray(0, 'Customer Create (Post)', '/customer/account/createpost'),
            $this->getWhitelistEntryAsArray(0, 'Paypal', '/paypal/ipn/'),
        ];

        foreach ($whitelistEntries as $entry) {
            try {
                $this->moduleDataSetup->getConnection()->insert(
                    $this->moduleDataSetup->getTable('devhub_forcelogin_whitelist'),
                    $entry
                );
            } catch (\Exception $e) {
            }
        }

        $this->moduleDataSetup->getConnection()->endSetup();

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @param int $storeId
     * @param string $label
     * @param string $urlRule
     * @param boolean $editable
     * @param string $strategy
     * @return array
     */
    public static function getWhitelistEntryAsArray(
        $storeId,
        $label,
        $urlRule,
        $editable = false,
        $strategy = 'default'
    ) {
        return [
            'store_id' => $storeId,
            'label' => $label,
            'url_rule' => $urlRule,
            'editable' => $editable,
            'strategy' => $strategy
        ];
    }
}
