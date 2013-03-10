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

$productOne = new Mage_Catalog_Model_Product();
$productOne->setId(1)
    ->setTypeId(Mage_Catalog_Model_Product_Type::TYPE_SIMPLE)
    ->setAttributeSetId(4)
    ->setWebsiteIds(array(Mage::app()->getStore()->getWebsiteId()))

    ->setSku('simple_product_1')
    ->setName('Simple Product 1 Name')
    ->setDescription('Simple Product 1 Full Description')
    ->setShortDescription('Simple Product 1 Short Description')

    ->setPrice(1234.56)
    ->setTaxClassId(2)
    ->setStockData(array(
        'use_config_manage_stock'   => 1,
        'qty'                       => 99,
        'is_in_stock'               => 1,
    ))

    ->setMetaTitle('Simple Product 1 Meta Title')
    ->setMetaKeyword('Simple Product 1 Meta Keyword')
    ->setMetaDescription('Simple Product 1 Meta Description')

    ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
    ->setStatus(Mage_Catalog_Model_Product_Status::STATUS_ENABLED)

    ->addImageToMediaGallery(dirname(__FILE__) . '/product_image.png', null, false, false)

    ->save();

$productTwo = new Mage_Catalog_Model_Product();
$productTwo->setId(2)
    ->setTypeId(Mage_Catalog_Model_Product_Type::TYPE_SIMPLE)
    ->setAttributeSetId(4)
    ->setWebsiteIds(array(Mage::app()->getStore()->getWebsiteId()))

    ->setSku('simple_product_2')
    ->setName('Simple Product 2 Name')
    ->setDescription('Simple Product 2 Full Description')
    ->setShortDescription('Simple Product 2 Short Description')

    ->setPrice(987.65)
    ->setTaxClassId(2)
    ->setStockData(array(
        'use_config_manage_stock'   => 1,
        'qty'                       => 24,
        'is_in_stock'               => 1,
    ))

    ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
    ->setStatus(Mage_Catalog_Model_Product_Status::STATUS_ENABLED)

    ->save();
