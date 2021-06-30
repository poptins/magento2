<?php


namespace Poptin\Magento2\Helper;

use Magento\Store\Model\ScopeInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    const XML_PATH_POPTIN_USER_ID = 'poptin_settings/poptin/poptin_user_id';
    const XML_PATH_POPTIN_TOKEN = 'poptin_settings/poptin/token';
    const XML_PATH_POPTIN_CLIENT_ID = 'poptin_settings/poptin/poptin_client_id';
    const XML_PATH_POPTIN_ETYPE = 'poptin_settings/poptin/poptin_etype';
	
    protected $resourceConfig;
    protected $cacheManager;
    protected $_poptinSession;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ConfigResource\ConfigInterface $resourceConfig,
        \Magento\Framework\App\Cache\Manager $cacheManager,
        \Magento\Framework\Session\SessionManagerInterface $coreSession
    ) {
    
        $this->resourceConfig = $resourceConfig;
        $this->cacheManager = $cacheManager;
        $this->_poptinSession = $coreSession;
        parent::__construct($context);
    }



    public function getPoptinUserId($store = null)
    {
        $poptinUserId = $this->scopeConfig->getValue(self::XML_PATH_POPTIN_USER_ID, ScopeInterface::SCOPE_STORE, $store);
        return $poptinUserId;
    }

    public function setPoptinUserId($poptinUserId)
    {
        $this->resourceConfig->saveConfig(
            self::XML_PATH_POPTIN_USER_ID,
            $poptinUserId,
            \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            \Magento\Store\Model\Store::DEFAULT_STORE_ID
        );
        $this->cacheManager->clean(['config']);
    }


    public function setPoptinLoginUrl($url)
    {
        $this->_poptinSession->setPoptinloginurlValue($url);
    }

    public function getPoptinLoginUrl()
    {
        return $this->_poptinSession->getPoptinloginurlValue();
    }


    public function getPoptinToken($store = null)
    {
        $poptinToken = $this->scopeConfig->getValue(self::XML_PATH_POPTIN_TOKEN, ScopeInterface::SCOPE_STORE, $store);
        return $poptinToken;
    }


    public function setPoptinToken($poptinToken)
    {
        $this->resourceConfig->saveConfig(
            self::XML_PATH_POPTIN_TOKEN,
            $poptinToken,
            \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            \Magento\Store\Model\Store::DEFAULT_STORE_ID
        );
        $this->cacheManager->clean(['config']);
    }


    public function getPoptinClientId($store = null)
    {
        $clientID = $this->scopeConfig->getValue(self::XML_PATH_POPTIN_CLIENT_ID, ScopeInterface::SCOPE_STORE, $store);
        return $clientID;
    }

    public function setPoptinClientId($poptinClientId)
    {
        $this->resourceConfig->saveConfig(
            self::XML_PATH_POPTIN_CLIENT_ID,
            $poptinClientId,
            \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            \Magento\Store\Model\Store::DEFAULT_STORE_ID
        );
        $this->cacheManager->clean(['config','full_page']);
    }
	
	public function setPoptinEmbedType($etype = 1)
    {
        $this->resourceConfig->saveConfig(
            self::XML_PATH_POPTIN_ETYPE,
            $etype,
            \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            \Magento\Store\Model\Store::DEFAULT_STORE_ID
        );
        $this->cacheManager->clean(['config','full_page']);
    }
	
	public function getPoptinEmbedType($store = null)
    {
        $poptinEtype = $this->scopeConfig->getValue(self::XML_PATH_POPTIN_ETYPE, ScopeInterface::SCOPE_STORE, $store);
        return $poptinEtype;
	}
	
	
}
