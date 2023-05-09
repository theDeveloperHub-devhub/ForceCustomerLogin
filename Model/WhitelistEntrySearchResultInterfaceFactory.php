<?php

namespace DevHub\ForceCustomerLogin\Model;

use DevHub\ForceCustomerLogin\Api\Data\Collection\WhitelistEntrySearchResultInterface;
use Magento\Framework\ObjectManagerInterface;

class WhitelistEntrySearchResultInterfaceFactory
{
    /**
     * Object Manager instance
     *
     * @var ObjectManagerInterface
     */
    private $_objectManager = null;

    /**
     * Instance name to create
     *
     * @var string
     */
    private $_instanceName = null;

    /**
     * Factory constructor
     *
     * @param ObjectManagerInterface $objectManager
     * @param string $instanceName
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        $instanceName = '\\DevHub\\ForceCustomerLogin\\Api\\Data\\Collection\\WhitelistEntrySearchResultInterface'
    ) {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
    }

    /**
     * Create class instance with specified parameters
     *
     * @param array $data
     * @return WhitelistEntrySearchResultInterface
     */
    public function create(array $data = array())
    {
        return $this->_objectManager->create($this->_instanceName, $data);
    }
}
