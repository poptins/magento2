<?php

namespace Poptin\Magento2\Controller\Adminhtml\Actions;

class SendMail extends \Magento\Backend\App\Action
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
		
           $post = $this->getRequest()->getPostValue();
			$web_address=$post["web_address"];
			$user_email=$post["user_email"];
			$textarea_text=$post["textarea_text"];
			
			$subject="Magento 2 help for ".$web_address;
			
			$mail_body="<strong>Domain</strong>: ".$web_address."<br>
						<strong>Message</strong>:".$textarea_text."<br>
						<strong>Reply to address:</strong> ".$user_email."";
						
			$to = 'contact+magentodemo@poptin.com';
			
			$headers = "From: " . strip_tags($user_email) . "\r\n";
			$headers .= "Reply-To: ". strip_tags($user_email) . "\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";	
		
			$mail_status=mail($to, $subject, $mail_body, $headers);
			if($mail_status)
			{
				$response = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON)
    ->setData([
        'result'  => "success",
        "msg"=>"Thanks for your request, we will get back to you soon."
    ]);
			}
			else
			{
				$response = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON)
    ->setData([
        'result'  => "failed",
        "msg"=>"Opps.. something went wrong mail not sent."
    ]);
			}
			
	/*$response = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON)
    ->setData([
        'result'  => "failed",
        "msg"=>"Opps.. something went wrong mail not sent."
    ]);*/
			
        return $response;
	
    }
}
