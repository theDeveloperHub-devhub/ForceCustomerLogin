<?php

namespace DevHub\ForceCustomerLogin\Model;

use DevHub\ForceCustomerLogin\Api\Data\WhitelistEntryFactoryInterface;
use Magento\Framework\ObjectManagerInterface;

class WhitelistEntryFactory implements WhitelistEntryFactoryInterface
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
        $instanceName = '\\DevHub\\ForceCustomerLogin\\Model\\WhitelistEntry'
    ) {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
    }

    /**
     * @inheritDoc
     */
    public function create(array $data = array())
    {
        return $this->_objectManager->create($this->_instanceName, $data);
    }
}
