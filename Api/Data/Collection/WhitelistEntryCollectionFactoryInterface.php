<?php

namespace DevHub\ForceCustomerLogin\Api\Data\Collection;

interface WhitelistEntryCollectionFactoryInterface
{
    /**
     * Create class instance with specified parameters
     *
     * @param array $data
     * @return \DevHub\ForceCustomerLogin\Model\ResourceModel\WhitelistEntry\Collection
     */
    public function create(array $data = []);
}
