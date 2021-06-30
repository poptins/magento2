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

namespace Poptin\Magento2\Block\Adminhtml\Actions;

class Index extends \Magento\Framework\View\Element\Template
{

    protected $formKey;
    protected $configHelper;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Poptin\Magento2\Helper\Data $configHelper,
        array $data = []
    ) {
    
        $this->formKey = $context->getFormKey();
        $this->configHelper = $configHelper;
        parent::__construct($context, $data);
    }

    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }

    public function getPoptinUserId()
    {
        return $this->configHelper->getPoptinUserId();
    }


    public function getPoptinClientId()
    {
        return $this->configHelper->getPoptinClientId();
    }


    public function getPoptinToken()
    {
        return $this->configHelper->getPoptinToken();
    }

    public function getPoptinLoginUrl()
    {
        return $this->configHelper->getPoptinLoginUrl();
    }

    public function getPoptinDeactivatePluginUrl()
    {
        return $this->getUrl('poptin/actions/deleteuserid');
    }

    public function getPoptinRedirectToLoginUrl()
    {
        return $this->getUrl('poptin/actions/redirecttologin');
    }
	
	public function getPoptinEmbedType()
    {
        return $this->configHelper->getPoptinEmbedType();
    }
	
}
