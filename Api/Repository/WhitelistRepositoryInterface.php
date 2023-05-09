<?php

namespace DevHub\ForceCustomerLogin\Api\Repository;

use DevHub\ForceCustomerLogin\Api\Data\Collection\WhitelistEntrySearchResultInterface;
use DevHub\ForceCustomerLogin\Model\ResourceModel\WhitelistEntry\Collection;
use DevHub\ForceCustomerLogin\Model\WhitelistEntry;
use Magento\Framework\Api\SearchCriteria;

interface WhitelistRepositoryInterface
{
    /*
     * Special store ids
     */
    const ROOT_STORE_ID = 0;
    /*
     * Strategy
     */
    const DEFAULT_STRATEGY = 'default';

    /**
     * Get collection {@link \DevHub\ForceCustomerLogin\Model\ResourceModel\WhitelistEntry\Collection}.
     *
     * @return Collection
     */
    public function getCollection();

    /**
     * Search by criterias for whitelist entries.
     *
     * @param SearchCriteria $searchCriteria
     * @return WhitelistEntrySearchResultInterface
     */
    public function getList(SearchCriteria $searchCriteria);

    /**
     * @param int|null $entityId If NULL a new entity will be created
     * @param string $label
     * @param string $urlRule
     * @param string $strategy
     * @param int $storeId
     * @return WhitelistEntry
     */
    public function createEntry($entityId, $label, $urlRule, $strategy = self::DEFAULT_STRATEGY, $storeId = 0);

    /**
     * @param int $id
     * @return boolean
     */
    public function deleteEntry($id);
}
