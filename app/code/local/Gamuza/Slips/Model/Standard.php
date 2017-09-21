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

class Gamuza_Slips_Model_Standard
extends Mage_Payment_Model_Method_Abstract
{

protected $_code = 'slips_standard';

protected $_canOrder                = true;
protected $_canAuthorize            = false;
protected $_canCapture              = true;
protected $_canRefund               = true;

protected $_formBlockType = 'slips/standard_form';
protected $_infoBlockType = 'slips/standard_info';

public function _getOrderIncrementPrefix ($order_store_id)
{
    $order_entity_type = Mage::getModel('eav/entity_type')->loadByCode ('order');
    $order_entity_type_id = $order_entity_type->getId ();
    $order_entity_type_increment_per_store = $order_entity_type->getIncrementPerStore ();
    if ($order_entity_type_increment_per_store)
    {
	$order_entity_type_pad_length = $order_entity_type->getIncrementPadLength ();
	$order_entity_type_pad_char = $order_entity_type->getIncrementPadChar ();
	$order_entity_store_increment_prefix = Mage::getModel ('eav/entity_store')->loadByEntityStore ($order_entity_type_id, $order_store_id)->getIncrementPrefix ();
	
	return (int) $order_entity_store_increment_prefix . str_pad ('0', $order_entity_type_pad_length, $order_entity_type_pad_char);
    }
    
    return 0;
}

public function _getStoreConfig ($key, $field)
{
    $_key = strtolower ($key);
    
    return Mage::getStoreConfig ("payment/{$_key}_settings/$field");
}

public function order (Varien_Object $payment, $amount)
{
    $order = $payment->getOrder ();
    $order_id = $order->getId ();
    $order_increment_id = $order->getIncrementId ();
    $store_id = $order->getStoreId ();
    
    $ccType = $payment->getCcType ();
    
    // reorder
    $reorder_increment_id = explode ('-', $order_increment_id); // reorder
    $order_increment_id = $reorder_increment_id [0];
    $order_suffix_id = @$reorder_increment_id [1];
    
    $expiration = strtotime ('+' . $this->_getStoreConfig ($ccType, 'expiration') . 'days');
    $transaction_expiration = date ('Y-m-d', $expiration);
    $increment = $this->_getStoreConfig ('slips', 'order_id_increment');
    
    $order_increment_prefix = $this->_getOrderIncrementPrefix ($store_id);
    $number = ($order_increment_id - $order_increment_prefix);
    if (!empty ($order_suffix_id))
    {
        $number *= pow (10, strlen ($order_suffix_id));
        $number += $order_suffix_id;
    }
    $number += $increment;
    
    $data = array ('order_id' => $order_id, 'amount' => $amount, 'expiration' => $transaction_expiration, 'number' => $number);
    $result = Mage::getModel ('utils/sql')->insert ('gamuza_slips_transactions', $data);
    if (!$result)
    {
	Mage::throwException(Mage::helper ('slips')->__('Unable to save the Slip and Deposit informations. Please verify your database.'));
    }
    
    $this->setStore($payment->getOrder()->getStoreId());
    $payment->setAmount($amount);
    $payment->setLastTransId($order_id);
    $payment->setStatus(self::STATUS_APPROVED);
    
    return $this;
}

}

