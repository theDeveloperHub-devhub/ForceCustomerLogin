<?php

namespace DevHub\ForceCustomerLogin\Api\Data\Collection;

use DevHub\ForceCustomerLogin\Api\Data\WhitelistEntryInterface;
use Magento\Framework\Api\SearchResultsInterface;

interface WhitelistEntrySearchResultInterface extends SearchResultsInterface
{
    /**
     * Get items.
     *
     * @return WhitelistEntryInterface[] Array of collection items.
     */
    public function getItems();

    /**
     * Set items.
     *
     * @param WhitelistEntryInterface[] $items
     * @return $this
     */
    public function setItems(array $items = null);
}
