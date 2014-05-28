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

class Gamuza_Slips_Block_Standard_Form
extends Mage_Payment_Block_Form
{

protected function _construct()
{
    parent::_construct();

    $this->setTemplate('gamuza/slips/standard/form.phtml');
}

protected function _getConfig()
{
    return Mage::getSingleton('slips/config');
}

public function getAvailableCcTypes()
{
    $types = $this->_getConfig()->getCcTypes();
    if ($method = $this->getMethod()) {
        $availableTypes = $method->getConfigData('cctypes');
        if ($availableTypes) {
            $availableTypes = explode(',', $availableTypes);
            foreach ($types as $code=>$name) {
                if (!in_array($code, $availableTypes)) {
                    unset($types[$code]);
                }
            }
        }
    }

    return $types;
}

protected function _toHtml()
{
    Mage::dispatchEvent('payment_form_block_to_html_before', array(
        'block'     => $this
    ));

    return parent::_toHtml();
}

public function _getStoreConfig ($key, $field)
{
	$_key = strtolower ($key);
	
	return Mage::getStoreConfig ("payment/{$_key}_settings/{$field}");
}

}

