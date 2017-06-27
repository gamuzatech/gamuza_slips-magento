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
 * These files are distributed with Gamuza_Slips at http://github.com/gamuzabrasil/.
 */

class Gamuza_Slips_Block_Real_Slip
extends Gamuza_Slips_Block_Standard_Abstract
// extends Mage_Core_Block_Template
{

public function getPaymentInfoHtml ($ccType)
{
	$form = new Varien_Data_Form();
	
	/*
	 * Slip Settings.
	 */
	$fieldset = $form->addFieldset("real_slip_fieldset", array ("legend" => null));
	$fieldset->addField("tax", "hidden", array(
	"name" => 'tax',
	"value" => $this->_getStoreConfig ($ccType, 'tax'),
	));
	$fieldset->addField("demonstrative1", "hidden", array(
	"name" => 'demonstrative1',
	"value" => $this->_getStoreConfig ($ccType, 'demonstrative1'),
	));
	$fieldset->addField("demonstrative2", "hidden", array(
	"name" => 'demonstrative2',
	"value" => $this->_getStoreConfig ($ccType, 'demonstrative2'),
	));
	$fieldset->addField("demonstrative3", "hidden", array(
	"name" => 'demonstrative3',
	"value" => $this->_getStoreConfig ($ccType, 'demonstrative3'),
	));
	$fieldset->addField("instructions1", "hidden", array(
	"name" => 'instructions1',
	"value" => $this->_getStoreConfig ($ccType, 'instructions1'),
	));
	$fieldset->addField("instructions2", "hidden", array(
	"name" => 'instructions2',
	"value" => $this->_getStoreConfig ($ccType, 'instructions2'),
	));
	$fieldset->addField("instructions3", "hidden", array(
	"name" => 'instructions3',
	"value" => $this->_getStoreConfig ($ccType, 'instructions3'),
	));
	$fieldset->addField("instructions4", "hidden", array(
	"name" => 'instructions4',
	"value" => $this->_getStoreConfig ($ccType, 'instructions4'),
	));
	$fieldset->addField("acceptance", "hidden", array(
	"name" => 'acceptance',
	"value" => $this->_getStoreConfig ($ccType, 'acceptance'),
	));
	$fieldset->addField("specie", "hidden", array(
	"name" => 'specie',
	"value" => $this->_getStoreConfig ($ccType, 'specie'),
	));
	$fieldset->addField("specie_doc", "hidden", array(
	"name" => 'specie_doc',
	"value" => $this->_getStoreConfig ($ccType, 'specie_doc'),
	));
	$fieldset->addField("agency", "hidden", array(
	"name" => 'agency',
	"value" => $this->_getStoreConfig ($ccType, 'agency'),
	));
	$fieldset->addField("account", "hidden", array(
	"name" => 'account',
	"value" => $this->_getStoreConfig ($ccType, 'account'),
	));
	$fieldset->addField("portfolio", "hidden", array(
	"name" => 'portfolio',
	"value" => $this->_getStoreConfig ($ccType, 'portfolio'),
	));

	/*
	 * Slip Transaction Data.
	 */
	$slip_data = $this->getSlipData ();

	$fieldset->addField("number", "hidden", array(
	"name" => 'number',
	"value" => $slip_data ['number'],
	));
	$fieldset->addField("expiration", "hidden", array(
	"name" => 'expiration',
	"value" => $slip_data ['expiration'],
	));
	$fieldset->addField("amount", "hidden", array(
	"name" => 'amount',
	"value" => $slip_data ['amount'],
	));

	/*
	 * Order Information.
	 */
	$_order = $this->getOrder ();
	$order_id = $_order->getIncrementId ();
	$billing_address = $_order->getBillingAddress ();
	$customer_name = $billing_address->getName ();
	$customer_address = $billing_address->getStreetFull ();
	$customer_city = $billing_address->getCity ();
	$customer_region = $billing_address->getRegion ();
	$customer_postcode = $billing_address->getPostcode ();
	$customer_city_region_postcode = "{$customer_city} - {$customer_region} - {$customer_postcode}";
	$order_items_count = count($_order->getAllVisibleItems ());
	$fieldset->addField("order_id", "hidden", array(
	"name" => 'order_id',
	"value" => $order_id,
	));
	$fieldset->addField("customer_name", "hidden", array(
	"name" => 'customer_name',
	"value" => $customer_name,
	));
	$fieldset->addField("customer_address", "hidden", array(
	"name" => 'customer_address',
	"value" => $customer_address,
	));
	$fieldset->addField("customer_city_region_postcode", "hidden", array(
	"name" => 'customer_city_region_postcode',
	"value" => $customer_city_region_postcode,
	));

	/*
	 * Company Information.
	 */
	$company_address = Mage::getStoreConfig ('general/store_information/address');
	$company_name = Mage::getStoreConfig ('general/store_information/name');
    $company_taxvat = Mage::getStoreConfig ('general/store_information/taxvat');
	$company_logo_url = $this->_getCompanyLogoUrl ();
	$fieldset->addField("company_address", "hidden", array(
	"name" => 'company_address',
	"value" => $company_address,
	));
	$fieldset->addField("company_name", "hidden", array(
	"name" => 'company_name',
	"value" => $company_name,
	));
	$fieldset->addField("company_taxvat", "hidden", array(
	"name" => 'company_taxvat',
	"value" => $company_taxvat,
	));
	$fieldset->addField("company_logo_url", "hidden", array(
	"name" => 'company_logo_url',
	"value" => $company_logo_url,
	));

	$fieldset->addField("submit", "submit", array(
	"label" => Mage::helper ('slips')->__('Submit this transaction'),
	"value" => Mage::helper ('slips')->__('Submit'),
	));

	return $form->toHtml ();
}

public function _construct ()
{
	$this->setTemplate ('gamuza/slips/real/slip.phtml');
}

}

