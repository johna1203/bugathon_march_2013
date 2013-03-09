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

$store = new Mage_Core_Model_Store;
$websiteId = Mage::app()->getWebsite()->getId();
$groupId = Mage::app()->getWebsite()->getDefaultGroupId();
$store->setCode('fixturestore') // fixture_store conflicts with "current_store" notation
    ->setWebsiteId($websiteId)
    ->setGroupId($groupId)
    ->setName('Fixture Store')
    ->setSortOrder(10)
    ->setIsActive(1)
;
$store->save();

/* Refresh stores memory cache */
Mage::app()->reinitStores();
