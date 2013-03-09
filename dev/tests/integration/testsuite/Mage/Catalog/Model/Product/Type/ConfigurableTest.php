<?php
/**
 * {license_notice}
 *
 * @category    Magento
 * @package     Magento_Catalog
 * @subpackage  integration_tests
 * @copyright   {copyright}
 * @license     {license_link}
 */

/**
 * @group module:Mage_Catalog
 * @magentoDataFixture Mage/Catalog/_files/product_configurable.php
 */
class Mage_Catalog_Model_Product_Type_ConfigurableTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Catalog_Model_Product_Type_Configurable
     */
    protected $_model;

    /**
     * @var Mage_Catalog_Model_Product
     */
    protected $_product = null;

    protected function setUp()
    {
        $this->_product = new Mage_Catalog_Model_Product;
        $this->_product->load(1); // fixture

        $this->_model = new Mage_Catalog_Model_Product_Type_Configurable;
        $this->_model->setProduct($this->_product);

        // prevent fatal errors by assigning proper "singleton" of type instance to the product
        $this->_product->setTypeInstance($this->_model);
        $this->_product->setTypeInstance($this->_model, true);
    }

    public function testGetRelationInfo()
    {
        $info = $this->_model->getRelationInfo();
        $this->assertInstanceOf('Varien_Object', $info);
        $this->assertEquals('catalog/product_super_link', $info->getTable());
        $this->assertEquals('parent_id', $info->getParentFieldName());
        $this->assertEquals('product_id', $info->getChildFieldName());
    }

    public function testGetChildrenIds()
    {
        $ids = $this->_model->getChildrenIds(1); // fixture
        $this->assertArrayHasKey(0, $ids);
        $this->assertTrue(2 === count($ids[0]));

        $ids = $this->_model->getChildrenIds(1, false);
        $this->assertArrayHasKey(0, $ids);
        $this->assertTrue(2 === count($ids[0]));
    }

    // testGetParentIdsByChild is after testGetConfigurableAttributesAsArray, because depends on it.

    public function testGetEditableAttributes()
    {
        $attributes = $this->_model->getEditableAttributes(); // $this->_product

        // applicable to all types
        $this->assertArrayHasKey('sku', $attributes);
        $this->assertArrayHasKey('name', $attributes);

        // applicable to configurable
        $this->assertArrayHasKey('price', $attributes);

        // not applicable to configurable
        $this->assertArrayNotHasKey('weight', $attributes);
    }

    public function testCanUseAttribute()
    {
        $this->assertFalse($this->_model->canUseAttribute($this->_getAttributeByCode('sku')));
        $this->assertTrue($this->_model->canUseAttribute($this->_getAttributeByCode('test_configurable')));
    }

    public function testSetGetUsedProductAttributeIds()
    {
        $testConfigurable = $this->_getAttributeByCode('test_configurable');
        $this->assertEquals(array($testConfigurable->getId()), $this->_model->getUsedProductAttributeIds());
    }

    public function testSetUsedProductAttributeIds()
    {
        $testConfigurable = $this->_getAttributeByCode('test_configurable');
        $this->assertEmpty($this->_product->getData('_cache_instance_configurable_attributes'));
        $this->_model->setUsedProductAttributeIds(array($testConfigurable->getId()));
        $attributes = $this->_product->getData('_cache_instance_configurable_attributes');
        $this->assertArrayHasKey(0, $attributes);
        $this->assertInstanceOf('Mage_Catalog_Model_Product_Type_Configurable_Attribute', $attributes[0]);
        $this->assertSame($testConfigurable, $attributes[0]->getProductAttribute());
    }

    public function testGetUsedProductAttributes()
    {
        $testConfigurable = $this->_getAttributeByCode('test_configurable');
        $attributeId = (int)$testConfigurable->getId();
        $attributes = $this->_model->getUsedProductAttributes();
        $this->assertArrayHasKey($attributeId, $attributes);
        $this->assertSame($testConfigurable, $attributes[$attributeId]);
    }

    public function testGetConfigurableAttributes()
    {
        $collection = $this->_model->getConfigurableAttributes();
        $this->assertInstanceOf('Mage_Catalog_Model_Resource_Product_Type_Configurable_Attribute_Collection',
            $collection
        );
        $testConfigurable = $this->_getAttributeByCode('test_configurable');
        foreach ($collection as $attribute) {
            $this->assertInstanceOf('Mage_Catalog_Model_Product_Type_Configurable_Attribute', $attribute);
            $this->assertEquals($testConfigurable->getId(), $attribute->getAttributeId());
            break;
        }
    }

    public function testGetConfigurableAttributesAsArray()
    {
        $attributes = $this->_model->getConfigurableAttributesAsArray();
        $this->assertArrayHasKey(0, $attributes);
        $attribute = $attributes[0];
        $this->assertArrayHasKey('id', $attribute);
        $this->assertArrayHasKey('label', $attribute);
        $this->assertArrayHasKey('use_default', $attribute);
        $this->assertArrayHasKey('position', $attribute);
        $this->assertArrayHasKey('values', $attribute);
        $this->assertArrayHasKey(0, $attribute['values']);
        $this->assertArrayHasKey(1, $attribute['values']);
        foreach ($attribute['values'] as $attributeOption) {
            $this->assertArrayHasKey('product_super_attribute_id', $attributeOption);
            $this->assertArrayHasKey('value_index', $attributeOption);
            $this->assertArrayHasKey('label', $attributeOption);
            $this->assertArrayHasKey('default_label', $attributeOption);
            $this->assertArrayHasKey('store_label', $attributeOption);
            $this->assertArrayHasKey('is_percent', $attributeOption);
            $this->assertArrayHasKey('pricing_value', $attributeOption);
            $this->assertArrayHasKey('use_default_value', $attributeOption);
        }
        $this->assertArrayHasKey('attribute_id', $attribute);
        $this->assertArrayHasKey('attribute_code', $attribute);
        $this->assertArrayHasKey('frontend_label', $attribute);
        $this->assertArrayHasKey('store_label', $attribute);

        $testConfigurable = $this->_getAttributeByCode('test_configurable');
        $this->assertEquals($testConfigurable->getId(), $attributes[0]['attribute_id']);
    }

    /**
     * @depends testGetConfigurableAttributesAsArray
     */
    public function testGetParentIdsByChild()
    {
        $attributes = $this->_model->getConfigurableAttributesAsArray();
        $confAttribute = $attributes[0];
        $optionValueId = $confAttribute['values'][0]['value_index'];
        $result = $this->_model->getParentIdsByChild($optionValueId * 10); // fixture
        $this->assertEquals(array(1), $result);
    }

    public function testGetConfigurableAttributeCollection()
    {
        $collection = $this->_model->getConfigurableAttributeCollection();
        $this->assertInstanceOf('Mage_Catalog_Model_Resource_Product_Type_Configurable_Attribute_Collection',
            $collection
        );
    }

    public function testGetUsedProductIds()
    {
        $ids = $this->_model->getUsedProductIds();
        $this->assertInternalType('array', $ids);
        $this->assertTrue(2 === count($ids)); // impossible to check actual IDs, because they are dynamic in the fixture
    }

    public function testGetUsedProducts()
    {
        $products = $this->_model->getUsedProducts();
        $this->assertInternalType('array', $products);
        $this->assertTrue(2 === count($products));
        foreach ($products as $product) {
            $this->assertInstanceOf('Mage_Catalog_Model_Product', $product);
        }
    }

    public function testGetUsedProductCollection()
    {
        $this->assertInstanceOf('Mage_Catalog_Model_Resource_Product_Type_Configurable_Product_Collection',
            $this->_model->getUsedProductCollection()
        );
    }

    public function testBeforeSave()
    {
        $this->assertEmpty($this->_product->getTypeHasOptions());
        $this->assertEmpty($this->_product->getTypeHasRequiredOptions());

        $this->_product->setCanSaveConfigurableAttributes(true);
        $this->_product->setConfigurableAttributesData(array('values' => 'not empty'));
        $this->_model->beforeSave();
        $this->assertTrue($this->_product->getTypeHasOptions());
        $this->assertTrue($this->_product->getTypeHasRequiredOptions());
    }

    public function testIsSalable()
    {
        $this->markTestIncomplete("Functionality not compatible with Magento 1.x");
        $this->_product->unsetData('is_salable');
        $this->assertTrue($this->_model->isSalable());
    }

    /**
     * @depends testGetConfigurableAttributesAsArray
     */
    public function testGetProductByAttributes()
    {
        $attributes = $this->_model->getConfigurableAttributesAsArray();
        $confAttribute = $attributes[0];
        $optionValueId = $confAttribute['values'][0]['value_index'];

        $product = $this->_model->getProductByAttributes(array($confAttribute['attribute_id'] => $optionValueId));
        $this->assertInstanceOf('Mage_Catalog_Model_Product', $product);
        $this->assertEquals("simple_{$optionValueId}", $product->getSku());
    }

    /**
     * @depends testGetConfigurableAttributesAsArray
     */
    public function testGetSelectedAttributesInfo()
    {
        $attributes = $this->_model->getConfigurableAttributesAsArray();
        $confAttribute = $attributes[0];
        $optionValueId = $confAttribute['values'][0]['value_index'];

        $this->_product->addCustomOption(
            'attributes', serialize(array($confAttribute['attribute_id'] => $optionValueId))
        );
        $info = $this->_model->getSelectedAttributesInfo();
        $this->assertEquals(array(array('label' => 'Test Configurable', 'value' => 'Option 1')), $info);
    }

    /**
     * @depends testGetConfigurableAttributesAsArray
     */
    public function testPrepareForCart()
    {
        $attributes = $this->_model->getConfigurableAttributesAsArray();
        $confAttribute = $attributes[0];
        $optionValueId = $confAttribute['values'][0]['value_index'];

        $buyRequest = new Varien_Object(array(
            'qty' => 5, 'super_attribute' => array($confAttribute['attribute_id'] => $optionValueId)
        ));
        $result = $this->_model->prepareForCart($buyRequest);
        $this->assertInternalType('array', $result);
        $this->assertTrue(2 === count($result));
        foreach ($result as $product) {
            $this->assertInstanceOf('Mage_Catalog_Model_Product', $product);
        }
        $this->assertInstanceOf('Varien_Object', $result[1]->getCustomOption('parent_product_id'));
    }

    public function testGetSpecifyOptionMessage()
    {
        $this->assertEquals('Please specify the product\'s option(s).', $this->_model->getSpecifyOptionMessage());
    }

    /**
     * @depends testGetConfigurableAttributesAsArray
     * @depends testPrepareForCart
     */
    public function testGetOrderOptions()
    {
        $this->_prepareForCart();

        $result = $this->_model->getOrderOptions();
        $this->assertArrayHasKey('info_buyRequest', $result);
        $this->assertArrayHasKey('attributes_info', $result);
        $this->assertEquals(
            array(array('label' => 'Test Configurable', 'value' => 'Option 1')), $result['attributes_info']
        );
        $this->assertArrayHasKey('product_calculations', $result);
        $this->assertArrayHasKey('shipment_type', $result);
        $this->assertEquals(
            Mage_Catalog_Model_Product_Type_Abstract::CALCULATE_PARENT, $result['product_calculations']
        );
        $this->assertEquals(Mage_Catalog_Model_Product_Type_Abstract::SHIPMENT_TOGETHER, $result['shipment_type']);
    }

    /**
     * @depends testGetConfigurableAttributesAsArray
     * @depends testPrepareForCart
     */
    public function testIsVirtual()
    {
        $this->_prepareForCart();
        $this->assertFalse($this->_model->isVirtual());
    }

    public function testHasOptions()
    {
        $this->assertTrue($this->_model->hasOptions());
    }

    public function testGetWeight()
    {
        $this->assertEmpty($this->_model->getWeight());

        $this->_product->setCustomOptions(array(
            'simple_product' => new Varien_Object(array(
                'product' => new Varien_Object(array(
                    'weight' => 2
                ))
            ))
        ));
        $this->assertEquals(2, $this->_model->getWeight());
    }

    public function testAssignProductToOption()
    {
        $option = new Varien_Object;
        $this->_model->assignProductToOption('test', $option);
        $this->assertEquals('test', $option->getProduct());

        // other branch of logic depends on Mage_Sales module
    }

    public function testGetProductsToPurchaseByReqGroups()
    {
        $result = $this->_model->getProductsToPurchaseByReqGroups();
        $this->assertArrayHasKey(0, $result);
        $this->assertInternalType('array', $result[0]);
        $this->assertTrue(2 === count($result[0])); // fixture has 2 simple products
        foreach ($result[0] as $product) {
            $this->assertInstanceOf('Mage_Catalog_Model_Product', $product);
        }
    }

    public function testGetSku()
    {
        $this->assertEquals('configurable', $this->_model->getSku());
        $this->_prepareForCart();
        $this->assertStringStartsWith('simple_', $this->_model->getSku());
    }

    public function testProcessBuyRequest()
    {
        $buyRequest = new Varien_Object(array('super_attribute' => array('10', 'string')));
        $result = $this->_model->processBuyRequest($this->_product, $buyRequest);
        $this->assertEquals(array('super_attribute' => array(10)), $result);
    }

    /**
     * Find and instantiate a catalog attribute model by attribute code
     *
     * @param string $code
     * @return Mage_Catalog_Model_Resource_Eav_Attribute
     */
    protected function _getAttributeByCode($code)
    {
        return Mage::getSingleton('eav/config')->getAttribute('catalog_product', $code);
    }

    /**
     * Select one of the options and "prepare for cart" with a proper buy request
     */
    protected function _prepareForCart()
    {
        $attributes = $this->_model->getConfigurableAttributesAsArray();
        $confAttribute = $attributes[0];
        $optionValueId = $confAttribute['values'][0]['value_index'];

        $buyRequest = new Varien_Object(array(
            'qty' => 5, 'super_attribute' => array($confAttribute['attribute_id'] => $optionValueId)
        ));
        $this->_model->prepareForCart($buyRequest);
    }
}
