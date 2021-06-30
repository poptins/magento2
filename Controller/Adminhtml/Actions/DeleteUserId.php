<?php
namespace Poptin\Magento2\Controller\Adminhtml\Actions;

use Poptin\Magento2\Data;

class DeleteUserId extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;
    protected $poptinApi;
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
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $this->configHelper->setPoptinUserId('');
        $this->configHelper->setPoptinToken('');
        $this->configHelper->setPoptinClientId('');
        $this->configHelper->setPoptinEmbedType('2');


        $this->messageManager->addSuccessMessage(__("Poptin was successfully removed from your site"));
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('poptin/actions/index');
        return $resultRedirect;
    }
}
