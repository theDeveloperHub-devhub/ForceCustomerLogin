<?php

namespace DevHub\ForceCustomerLogin\Block\Adminhtml\Manage;

use Magento\Backend\Block\Widget\Form\Container;

class Create extends Container
{
    /**
     * @var string
     */
    private $formIdentifier = 'create_manage_entry_form';

    /**
     * Initialize printer post create block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'whitelist_entry_id';
        $this->_blockGroup = 'DevHub_ForceCustomerLogin';
        $this->_controller = 'adminhtml_manage';
        $this->_mode = 'create';

        parent::_construct();

        $this->updateButtonControls();
    }

    /**
     * @inheritDoc
     */
    public function getSaveUrl()
    {
        return $this->getUrl(
            'ForceCustomerLogin/Manage/Save',
            [
                $this->_objectId => $this->getRequest()->getParam($this->_objectId)
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function getBackUrl()
    {
        return $this->getUrl('ForceCustomerLogin/Manage');
    }

    /**
     * @return void
     */
    protected function updateButtonControls()
    {
        $this->buttonList->update(
            'save',
            'data_attribute',
            [
                'mage-init' => [
                    'button' => [
                        'event' => 'save',
                        'target' => '#' . $this->getFormIdentifier()
                    ]
                ],
            ]
        );
    }

    /**
     * @return string
     */
    protected function getFormIdentifier()
    {
        return $this->formIdentifier;
    }
}
