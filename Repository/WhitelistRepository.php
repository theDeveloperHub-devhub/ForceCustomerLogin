<?php

namespace DevHub\ForceCustomerLogin\Repository;

use DevHub\ForceCustomerLogin\Api\Data\Collection\WhitelistEntryCollectionFactoryInterface;
use DevHub\ForceCustomerLogin\Api\Data\WhitelistEntryFactoryInterface;
use DevHub\ForceCustomerLogin\Model\WhitelistEntrySearchResultInterfaceFactory as SearchResultFactory;
use DevHub\ForceCustomerLogin\Validator\WhitelistEntry;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Store\Model\StoreManager;

class WhitelistRepository implements \DevHub\ForceCustomerLogin\Api\Repository\WhitelistRepositoryInterface
{
    /**
     * @var WhitelistEntryFactoryInterface
     */
    private $entityFactory;
    /**
     * @var WhitelistEntryCollectionFactoryInterface
     */
    private $collectionFactory;
    /**
     * @var SearchResultFactory
     */
    private $searchResultFactory;
    /**
     * @var StoreManager
     */
    private $storeManager;

    /**
     * WhitelistRepository constructor.
     *
     * @param WhitelistEntryFactoryInterface $entityFactory
     * @param WhitelistEntryCollectionFactoryInterface $collectionFactory
     * @param StoreManager $storeManager
     * @param SearchResultFactory $searchResultFactory
     */
    public function __construct(
        WhitelistEntryFactoryInterface $entityFactory,
        WhitelistEntryCollectionFactoryInterface $collectionFactory,
        StoreManager $storeManager,
        SearchResultFactory $searchResultFactory
    ) {
        $this->entityFactory = $entityFactory;
        $this->collectionFactory = $collectionFactory;
        $this->storeManager = $storeManager;
        $this->searchResultFactory = $searchResultFactory;
    }

    /**
     * @inheritDoc
     */
    public function createEntry($entityId, $label, $urlRule, $strategy = self::DEFAULT_STRATEGY, $storeId = 0)
    {
        $whitelist = $this->entityFactory->create();

        if (null !== $entityId) {
            $whitelist = $whitelist->load($entityId);
        }

        if (!$whitelist->getId()) {
            $whitelist = $this->entityFactory->create()->load($label, 'label');
        }

        // check if existing whitelist entry is editable
        if ($whitelist->getId() &&
            !$whitelist->getEditable()) {
            throw new \RuntimeException(
                'Whitelist entry not editable.'
            );
        }

        $whitelist->setLabel($label);
        $whitelist->setUrlRule($urlRule);
        $whitelist->setStrategy($strategy);
        $whitelist->setStoreId($storeId);
        $whitelist->setEditable(true);

        $validator = new WhitelistEntry();
        $validator->validate($whitelist);

        $whitelist->save();

        return $whitelist;
    }

    /**
     * @inheritDoc
     */
    public function deleteEntry($id)
    {
        $whitelist = $this->entityFactory->create()->load($id);
        if (!$whitelist->getId() ||
            !$whitelist->getEditable()) {
            return false;
        }

        $whitelist->delete();

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getCollection()
    {
        $currentStore = $this->storeManager->getStore();

        $collection = $this->collectionFactory->create();

        $collection->addFieldToFilter(
            'store_id',
            [
                'in' => [
                    static::ROOT_STORE_ID,
                    (int) $currentStore->getId()
                ]
            ]
        );

        return $collection->load();
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteria $searchCriteria)
    {
        /** @var AbstractDb $searchResult */
        $searchResult = $this->searchResultFactory->create();
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $filters = $filterGroup->getFilters();
            if(is_array($filters)) {
                foreach ($filters as $filter) {
                    $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
                    $searchResult->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
                }
            }
        }
        $searchResult->setCurPage((int)$searchCriteria->getCurrentPage());
        $searchResult->setPageSize((int)$searchCriteria->getPageSize());

        return $searchResult;
    }
}
