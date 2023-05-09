<?php

namespace DevHub\ForceCustomerLogin\Controller;

use DevHub\ForceCustomerLogin\Api\Controller\LoginCheckInterface;
use Magento\Framework\App\Action\Redirect;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;

class LoginRouter implements RouterInterface
{
    /**
     * @var ActionFactory
     */
    private $actionFactory;
    /**
     * @var LoginCheckInterface
     */
    private $loginCheck;

    /**
     * LoginRouter constructor.
     *
     * @param ActionFactory $actionFactory
     * @param LoginCheckInterface $loginCheck
     * @throws \InvalidArgumentException
     */
    public function __construct(
        ActionFactory $actionFactory,
        LoginCheckInterface $loginCheck
    ) {
        $this->actionFactory = $actionFactory;
        $this->loginCheck = $loginCheck;
    }

    /**
     * @inheritDoc
     * @return ActionInterface|void
     */
    public function match(RequestInterface $request)
    {
        if ($this->loginCheck->execute()) {
            /** @var Http $request */
            $request->setDispatched(true);
            return $this->actionFactory->create(Redirect::class);
        }
    }
}
