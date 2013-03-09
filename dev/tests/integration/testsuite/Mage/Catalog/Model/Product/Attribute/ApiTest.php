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
 * Test class for Mage_Catalog_Model_Product_Attribute_Api.
 *
 * @group module:Mage_Catalog
 */
class Mage_Catalog_Model_Product_Attribute_ApiTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Catalog_Model_Product_Attribute_Api
     */
    protected $_model;

    protected function setUp()
    {
        $this->_model = new Mage_Catalog_Model_Product_Attribute_Api;
    }

    public function testItems()
    {
        $items = $this->_model->items(4); /* default product attribute set after installation */
        $this->assertInternalType('array', $items);
        $element = current($items);
        $this->assertArrayHasKey('attribute_id', $element);
        $this->assertArrayHasKey('code', $element);
        $this->assertArrayHasKey('type', $element);
        $this->assertArrayHasKey('required', $element);
        $this->assertArrayHasKey('scope', $element);
        foreach ($items as $item) {
            if ($item['code'] == 'status') {
                return $item['attribute_id'];
            }
        }
        return false;
    }

    /**
     * @depends testItems
     */
    public function testOptions($attributeId)
    {
        if (!$attributeId) {
            $this->fail('Wromg attribute id');
        }
        $options = $this->_model->options($attributeId);
        $this->assertInternalType('array', $options);
        $element = current($options);
        $this->assertArrayHasKey('value', $element);
        $this->assertArrayHasKey('label', $element);
    }
}
