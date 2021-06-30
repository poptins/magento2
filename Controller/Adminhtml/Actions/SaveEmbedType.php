<?php

namespace Poptin\Magento2\Controller\Adminhtml\Actions;

class SaveEmbedType extends \Magento\Backend\App\Action
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
        $this->configHelper->setPoptinEmbedType($data['adv_setting_code']);
        $this->messageManager->addSuccessMessage(__("Advanced Settings Changed Successfully."));
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('poptin/actions/index');
        return $resultRedirect;
    }
}
