<?php

namespace DevHub\ForceCustomerLogin\Controller\Adminhtml\Manage;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;

class Create extends Action
{
    /**
     * @inheritDoc
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
        return $this->_response;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DevHub_ForceCustomerLogin::devhub_force_customer_login_manage');
    }
}
