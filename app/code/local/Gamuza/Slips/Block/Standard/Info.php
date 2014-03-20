<?php
/*
 * Gamuza Slips - Slips and Deposits for Magento platform.
 * Copyright (C) 2013 Gamuza Technologies (http://www.gamuza.com.br/)
 * Author: Eneias Ramos de Melo <eneias@gamuza.com.br>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Library General Public
 * License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Library General Public License for more details.
 *
 * You should have received a copy of the GNU Library General Public
 * License along with this library; if not, write to the
 * Free Software Foundation, Inc., 51 Franklin St, Fifth Floor,
 * Boston, MA  02110-1301, USA.
 */

/*
 * See the AUTHORS file for a list of people on the Gamuza Team.
 * See the ChangeLog files for a list of changes.
 * These files are distributed with Gamuza_Slips at http://code.google.com/p/gamuzaopen/.
 */

class Gamuza_Slips_Block_Standard_Info
extends Mage_Payment_Block_Info
{
    protected function _construct ()
    {
        $this->setTemplate ('slips/standard/info.phtml');
    }

    public function getCcTypeName()
    {
        $types = Mage::getSingleton('slips/config')->getCcTypes();
        $ccType = $this->getInfo()->getCcType();
        if (isset($types[$ccType])) {
            return $types[$ccType];
        }
        return (empty($ccType)) ? Mage::helper('slips')->__('N/A') : $ccType;
    }

    protected function _prepareSpecificInformation($transport = null)
    {
        if (null !== $this->_paymentSpecificInformation) {
            return $this->_paymentSpecificInformation;
        }
        $transport = parent::_prepareSpecificInformation($transport);
        $data = array();
        if ($ccType = $this->getCcTypeName()) {
            $data[Mage::helper('slips')->__('Type')] = Mage::helper ('slips')->__($ccType);
        }
        return $transport->setData(array_merge($data, $transport->getData()));
    }

    public function getOrder()
    {
        return Mage::registry('current_order');
    }
    
    public function _getStoreConfig ($field)
    {
		return Mage::getStoreConfig ("payment/slips_settings/{$field}");
    }
    
    public function getPaymentInfoHtml ()
    {
		if (!strcmp ($this->getRequest()->getRouteName (), 'checkout')
			&& !strcmp ($this->getRequest()->getControllerName (), 'onepage')
			&& !strcmp ($this->getRequest()->getActionName (), 'progress')) return;
	
		if ($this->getOrder () == NULL) return;
	
		$ccType = strtolower ($this->getInfo ()->getCcType ());
	
		return Mage::app()->getLayout ()->createBlock ("slips/$ccType")->setCcType ($ccType)->toHtml ();
    }
}
