<?php

namespace DevHub\ForceCustomerLogin\Block\Adminhtml\Settings;

use Magento\Backend\Block\Widget\Container;

class Whitelist extends Container
{
    /**
     * @inheritDoc
     */
    protected function _prepareLayout()
    {
        $restoreDefautsButtonProps = [
            'id' => 'restore_defaults',
            'label' => __('Restore Defaults'),
            'class' => 'primary add',
            'button_class' => '',
            'onclick' => "setLocation('" . $this->getRestoreDefaultsUrl() . "')",
            'class_name' => 'Magento\Backend\Block\Widget\Button'
        ];
        $this->buttonList->add('restore_defaults', $restoreDefautsButtonProps);

        $addButtonProps = [
            'id' => 'add_new_entry',
            'label' => __('Add Entry'),
            'class' => 'primary add',
            'button_class' => '',
            'onclick' => "setLocation('" . $this->getCreateUrl() . "')",
            'class_name' => 'Magento\Backend\Block\Widget\Button'
        ];
        $this->buttonList->add('add_new', $addButtonProps);

        return parent::_prepareLayout();
    }

    /**
     * Retrieve restore defaults url
     *
     * @return string
     */
    protected function getRestoreDefaultsUrl()
    {
        return $this->getUrl(
            'ForceCustomerLogin/Manage/RestoreDefault'
        );
    }

    /**
     * Retrieve create url
     *
     * @return string
     */
    protected function getCreateUrl()
    {
        return $this->getUrl(
            'ForceCustomerLogin/Manage/Create'
        );
    }
}
