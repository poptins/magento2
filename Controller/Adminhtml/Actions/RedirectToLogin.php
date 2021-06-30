<?php
namespace Poptin\Magento2\Controller\Adminhtml\Actions;

use Poptin\Magento2\Data;

class RedirectToLogin extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;
    protected $_poptinApi;
    protected $_configHelper;

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
        $this->_poptinApi = $poptinapi;
        $this->_configHelper = $configHelper;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $urlToRedirect = "https://app.popt.in";
        $poptinUserId = $this->_configHelper->getPoptinUserId();
        $poptinToken = $this->_configHelper->getPoptinToken();
        if (!empty($poptinUserId) && !empty($poptinToken)) {
            $apiResult = $this->_poptinApi->authorizeAccount($poptinToken, $poptinUserId);
            if ($apiResult['success']) {
                $this->_configHelper->setPoptinLoginUrl($apiResult['login_url']);
                $urlToRedirect = $apiResult['login_url'];
				$urlToRedirect .= '&utm_source=magento2';
            }
			
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath($urlToRedirect);
        return $resultRedirect;
    }
}
