<?php
/**
 * {license_notice}
 *
 * @category    Magento
 * @package     Mage_Core
 * @subpackage  integration_tests
 * @copyright   {copyright}
 * @license     {license_link}
 */

$storeId = Mage::app()->getAnyStoreView()->getId();
$change = new Mage_Core_Model_Design;
$change->setStoreId($storeId)
    ->setDesign('default/default/blue')
    ->setDateFrom('2001-01-01 01:01:01')
    ->save() // creating with predefined ID doesn't work for some reason
;
