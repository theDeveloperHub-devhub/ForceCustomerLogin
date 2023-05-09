<?php

namespace DevHub\ForceCustomerLogin\Controller\Adminhtml\Manage;

use DevHub\ForceCustomerLogin\Api\Repository\WhitelistRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Webapi\Exception;

class Delete extends Action
{
    /**
     * @var WhitelistRepositoryInterface
     */
    private $whitelistRepository;
    /**
     * @var RedirectFactory
     */
    private $redirectFactory;

    /**
     * Save constructor.
     *
     * @param WhitelistRepositoryInterface $whitelistRepository
     * @param Context $context
     */
    public function __construct(
        WhitelistRepositoryInterface $whitelistRepository,
        Context $context
    ) {
        parent::__construct($context);
        $this->whitelistRepository = $whitelistRepository;
        $this->redirectFactory = $context->getResultRedirectFactory();
    }

    /**
     * Delete action.
     *
     * @return ResultInterface|ResponseInterface
     */
    public function execute()
    {
        $result = $this->redirectFactory->create();
        $result->setPath('ForceCustomerLogin/Manage/index');

        try {
            if (!$this->whitelistRepository->deleteEntry(
                $this->getRequest()->getParam('id', 0)
            )) {
                throw new \RuntimeException(
                    \sprintf(
                        __('Could not delete manage entry with id %s.'),
                        $this->getRequest()->getParam('id', 0)
                    )
                );
            }

            $this->messageManager->addSuccessMessage(
                __('Whitelist entry successfully removed.')
            );

            $result->setHttpResponseCode(200);
        } catch (\Exception $e) {
            $result->setHttpResponseCode(Exception::HTTP_INTERNAL_ERROR);
            $this->messageManager->addErrorMessage(
                \sprintf(
                    __('Could not remove record: %s'),
                    $e->getMessage()
                )
            );
        }

        return $result;
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
