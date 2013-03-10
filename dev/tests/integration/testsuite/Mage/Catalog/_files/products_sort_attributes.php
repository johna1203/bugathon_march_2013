<?php

Mage::app()->reinitStores();
Mage::app()->setCurrentStore(Mage_Core_Model_Store::ADMIN_CODE);
Mage::app()->getStore()->setWebsiteId(1);

$installer = new Mage_Catalog_Model_Resource_Setup('catalog_write');

//
// Create a select attribute with a table source model
//
$attribute = new Mage_Catalog_Model_Resource_Eav_Attribute();
if ($data = $installer->getAttribute('catalog_product', 'attr_src_table')) {
    $attribute->setData($data);
} else {
    $attributeCode = 'attr_src_table';
    $data = array(
        'attribute_code' => $attributeCode,
        'entity_type_id' => $installer->getEntityTypeId('catalog_product'),
        'is_global' => 0,
        'is_user_defined' => 1,
        'frontend_input' => 'select',
        'is_unique' => 0,
        'is_required' => 0,
        'is_configurable' => 0,
        'is_searchable' => 0,
        'is_visible_in_advanced_search' => 0,
        'is_comparable' => 0,
        'is_filterable' => 0,
        'is_filterable_in_search' => 0,
        'is_used_for_promo_rules' => 0,
        'is_html_allowed_on_front' => 1,
        'is_visible_on_front' => 0,
        'used_in_product_listing' => 1,
        'used_for_sort_by' => 1,
        'frontend_label' => array(
            0 => 'Table Source Model Test'
        ),
        'option' => array(
            'value' => array(
                // Reverse label on purpose!
                'a' => array(0 => 'C'),
                'b' => array(0 => 'B'),
                'c' => array(0 => 'A'),
            ),
            'order' => array(
                'a' => 10,
                'b' => 20,
                'c' => 30,
            )
        ),
        'backend_type' => 'int',
    );
    $attribute->setData($data);
    $attribute->save();
    $installer->addAttributeToSet('catalog_product', 'Default', 'General', $attribute->getId());
}
$tableAttributeOptions = $attribute->getSource()->getAllOptions();

//
// Create a select attribute with a boolean source model
//
$attribute = new Mage_Catalog_Model_Resource_Eav_Attribute();
if ($data = $installer->getAttribute('catalog_product', 'attr_src_bool')) {
    $attribute->setData($data);
} else {
    $attributeCode = 'attr_src_bool';
    $data = array(
        'attribute_code' => $attributeCode,
        'entity_type_id' => $installer->getEntityTypeId('catalog_product'),
        'is_global' => 0,
        'is_user_defined' => 1,
        'frontend_input' => 'boolean',
        'is_unique' => 0,
        'is_required' => 0,
        'is_configurable' => 0,
        'is_searchable' => 0,
        'is_visible_in_advanced_search' => 0,
        'is_comparable' => 0,
        'is_filterable' => 0,
        'is_filterable_in_search' => 0,
        'is_used_for_promo_rules' => 0,
        'is_html_allowed_on_front' => 1,
        'is_visible_on_front' => 0,
        'used_in_product_listing' => 1,
        'used_for_sort_by' => 1,
        'frontend_label' => array(
            0 => 'Yes/No Source Model Test'
        ),
        'backend_type' => 'int',
    );
    $attribute->setData($data);
    $attribute->save();
    $installer->addAttributeToSet('catalog_product', 'Default', 'General', $attribute->getId());
}

//
// Create a select attribute with a boolean source model
//
$attribute = new Mage_Catalog_Model_Resource_Eav_Attribute();
if ($data = $installer->getAttribute('catalog_product', 'attr_src_cntryofmnfctr')) {
    $attribute->setData($data);
} else {
    $attributeCode = 'attr_src_cntryofmnfctr';
    $data = array(
        'attribute_code' => $attributeCode,
        'entity_type_id' => $installer->getEntityTypeId('catalog_product'),
        'is_global' => 0,
        'is_user_defined' => 1,
        'frontend_input' => 'select',
        'source_model' => 'catalog/product_attribute_source_countryofmanufacture',
        'is_unique' => 0,
        'is_required' => 0,
        'is_configurable' => 0,
        'is_searchable' => 0,
        'is_visible_in_advanced_search' => 0,
        'is_comparable' => 0,
        'is_filterable' => 0,
        'is_filterable_in_search' => 0,
        'is_used_for_promo_rules' => 0,
        'is_html_allowed_on_front' => 1,
        'is_visible_on_front' => 0,
        'used_in_product_listing' => 1,
        'used_for_sort_by' => 1,
        'frontend_label' => array(
            0 => 'Countryofmanufacturer Source Model Test'
        ),
        'backend_type' => 'varchar',
    );
    $attribute->setData($data);
    $attribute->save();
    // The source model is changed for select frontend input attributes if no ID is set
    // See Mage_Eav_Model_Resource_Entity_Attribute::_beforeSave()
    // So we need to set it again after the attribute has been saved.
    $attribute->setSourceModel('catalog/product_attribute_source_countryofmanufacture')->save();
    $installer->addAttributeToSet('catalog_product', 'Default', 'General', $attribute->getId());
}
$countryOfManufacturers = $attribute->getSource()->getAllOptions();

//
// Create 2 products to be able to test sorting
//

for ($numProducts = 2, $num = 0; $num < $numProducts; $num++) {
    $product = new Mage_Catalog_Model_Product();
    $sku = 'sort_product' . $num;
    if (! $product->getIdBySku($sku)) {
        $customAttributeData = array(
            'attr_src_table' => $tableAttributeOptions[$num+1]['value'],
            'attr_src_bool' => $num % 2,
            'attr_src_cntryofmnfctr' => $countryOfManufacturers[$num+1]['value']
        );
        $product->setTypeId(Mage_Catalog_Model_Product_Type::TYPE_SIMPLE)
                ->setAttributeSetId($installer->getAttributeSetId('catalog_product', 'Default'))
                ->setWebsiteIds(array(1))
                ->setName('Sort Product ' . $num)
                ->setSku($sku)
                ->setPrice(10)
                ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
                ->setStatus(Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
                ->setStockData(
                    array(
                        'use_config_manage_stock' => 1,
                        'qty' => 100,
                        'is_qty_decimal' => 0,
                        'is_in_stock' => 1,
                    )
                )
                ->addData($customAttributeData)
                ->save();
    }
}