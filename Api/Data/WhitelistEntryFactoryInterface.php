<?php

namespace DevHub\ForceCustomerLogin\Api\Data;

use DevHub\ForceCustomerLogin\Model\WhitelistEntry;

interface WhitelistEntryFactoryInterface
{
    /**
     * Create class instance with specified parameters
     *
     * @param array $data
     * @return WhitelistEntry
     */
    public function create(array $data = []);
}
