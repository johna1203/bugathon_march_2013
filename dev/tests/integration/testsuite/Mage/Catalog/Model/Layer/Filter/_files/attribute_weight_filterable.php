<?php
/**
 * {license_notice}
 *
 * @category    Magento
 * @package     Mage_Catalog
 * @subpackage  integration_tests
 * @copyright   {copyright}
 * @license     {license_link}
 */

$installer = new Mage_Catalog_Model_Resource_Setup('catalog_write');

$installer->updateAttribute('catalog_product', 'weight', 'is_filterable', 1);

