<?php
/*
 * Gamuza Slips - Slips and Deposits for Magento platform.
 * Copyright (c) 2010 - 2014 Gamuza Technologies (http://www.gamuza.com.br/)
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
 * These files are distributed with Gamuza_Slips at http://githug.com/gamuzabrasil/.
 */

class Gamuza_Slips_Block_Standard_Abstract
extends Mage_Core_Block_Template
{

public function _getStoreConfig ($field)
{
    return Mage::getStoreConfig ("payment/slips_settings/{$field}");
}

public function getOrder()
{
    return Mage::registry('current_order');
}
    

public function getSlipUrl ($name)
{
    return preg_replace ('/index\.php\/$/', "", Mage::getUrl ('/')) . "skin/gamuza/slips/{$name}";
}

public function getSlipData ()
{
	$order_id = $this->getOrder()->getId ();

	$collection = Mage::getModel ('slips/transactions')->getCollection ();
	$collection->getSelect()->where ("order_id={$order_id}");

	return $collection->load()->getFirstItem()->toArray ();
}

public function getDepositPaymentInfoHtml ($key, $field)
{
	$_key = strtolower ($key);
	
	return Mage::getStoreConfig ("payment/{$_key}_settings/{$field}");
}

}

