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

$category = new Mage_Catalog_Model_Category();
$category->setId(3)
    ->setName('Category 1')
    ->setParentId(2) /**/
    ->setPath('1/2/3')
    ->setLevel(2)
    ->setAvailableSortBy('name')
    ->setDefaultSortBy('name')
    ->setIsActive(true)
    ->setPosition(1)
    ->save();

$urlKeys = array(
    'url-key-',
    'url-key-1',
    'url-key-2',
    'url-key-5',
    'url-key-1000',
    'url-key-999',
    'url-key-asdf',
);

foreach ($urlKeys as $i => $urlKey) {
    $id = $i + 1;
    $product = new Mage_Catalog_Model_Product();
    $product->setTypeId(Mage_Catalog_Model_Product_Type::TYPE_SIMPLE)
        ->setId($id)
        ->setStoreId(1)
        ->setAttributeSetId(4)
        ->setWebsiteIds(array(1))
        ->setName('Simple Product ' . $id)
        ->setSku('simple-' . $id)
        ->setPrice(10)
        ->setCategoryIds(array(3))
        ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
        ->setStatus(Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
        ->setUrlKey($urlKey)
        ->setUrlPath($urlKey . '.html')
        ->save();
}
