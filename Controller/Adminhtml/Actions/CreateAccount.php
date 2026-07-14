<?php

namespace Poptin\Magento2\Controller\Adminhtml\Actions;

class CreateAccount extends \Magento\Backend\App\Action
{

    protected $resultPageFactory;
    protected $poptinApi;
    protected $messageManager;
    protected $configHelper;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Poptin\Magento2\Api\Poptinapi $poptinapi,
        \Poptin\Magento2\Helper\Data $configHelper
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->poptinApi = $poptinapi;
        $this->configHelper = $configHelper;
        $this->messageManager = $context->getMessageManager();
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $email = $data['email'] ?? '';

        $apiResult = $this->poptinApi->createAccount($email);

        if (is_array($apiResult) && !empty($apiResult['success'])) {
            $this->configHelper->setPoptinUserId($apiResult['user_id'] ?? '');
            $this->configHelper->setPoptinToken($apiResult['token'] ?? '');
            $this->configHelper->setPoptinClientId($apiResult['client_id'] ?? '');
            $this->configHelper->setPoptinLoginUrl($apiResult['login_url'] ?? '');
        } elseif (is_array($apiResult)) {
            $this->messageManager->addErrorMessage(
                $apiResult['message'] ?? __('Unable to create Poptin account.')
            );
        } else {
            $this->messageManager->addErrorMessage(__('Unable to connect to Poptin API. Please try again later.'));
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('poptin/actions/index');
        return $resultRedirect;
    }
}
