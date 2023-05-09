<?php

namespace DevHub\ForceCustomerLogin\Model\ResourceModel\WhitelistEntry;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'whitelist_entry_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            'DevHub\ForceCustomerLogin\Model\WhitelistEntry',
            'DevHub\ForceCustomerLogin\Model\ResourceModel\WhitelistEntry'
        );
    }
}
