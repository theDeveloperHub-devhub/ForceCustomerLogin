<?php

namespace DevHub\ForceCustomerLogin\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class WhitelistEntry extends AbstractDb
{
    /**
     * Initialize connection and define resource
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('devhub_forcelogin_whitelist', 'whitelist_entry_id');
    }
}
