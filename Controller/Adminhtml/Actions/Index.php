<?php
/**
 * A Magento 2 module named Poptin/Magento2
 * Copyright (C) 2017  Poptin inc
 *
 * This file is part of Poptin/Magento2.
 *
 * Poptin/Magento2 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Poptin\Magento2\Controller\Adminhtml\Actions;

class Index extends \Magento\Backend\App\Action
{

    protected $resultPageFactory;
    protected $_poptinapi;
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
        $this->_configHelper = $configHelper;
        $this->_poptinapi = $poptinapi;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $poptinUserId = $this->_configHelper->getPoptinUserId();
        $poptinToken = $this->_configHelper->getPoptinToken();
        if (!empty($poptinUserId) && !empty($poptinToken)) {
            $apiResult = $this->_poptinapi->authorizeAccount($poptinToken, $poptinUserId);
            if ($apiResult['success']) {
                $this->_configHelper->setPoptinLoginUrl($apiResult['login_url']);
            } else {
                $this->messageManager->addErrorMessage($apiResult['message']);
            }
        }
        return $this->resultPageFactory->create();
    }
}
