<?php
namespace Poptin\Magento2\Block;

use Magento\Framework\App\ObjectManager;

class Poptin extends \Magento\Framework\View\Element\Template
{
    function _prepareLayout()
    {
    }

    public function getConfig($path)
    {
        return $this->_scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}
