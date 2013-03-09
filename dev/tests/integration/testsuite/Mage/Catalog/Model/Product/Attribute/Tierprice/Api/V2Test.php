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
 * Test class for Mage_Catalog_Model_Product_Attribute_Tierprice_Api_V2.
 *
 * @group module:Mage_Catalog
 */
class Mage_Catalog_Model_Product_Attribute_Tierprice_Api_V2Test extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Catalog_Model_Product_Attribute_Tierprice_Api_V2
     */
    protected $_model;

    protected function setUp()
    {
        $this->_model = new Mage_Catalog_Model_Product_Attribute_Tierprice_Api_V2;
    }

    /**
     * @expectedException Mage_Api_Exception
     */
    public function testPrepareTierPricesInvalidData()
    {
        $product = new Mage_Catalog_Model_Product();
        $this->_model->prepareTierPrices($product, array(1));
    }

    public function testPrepareTierPricesInvalidWebsite()
    {
        $product = new Mage_Catalog_Model_Product();
        $data = $this->_model->prepareTierPrices(
            $product,
            array((object) array('qty' => 3, 'price' => 8, 'website' => 100))
        );
        $this->assertEquals(
            array(array('website_id' => 0, 'cust_group' => 32000, 'price_qty' => 3, 'price' => 8)),
            $data
        );
    }

    public function testPrepareTierPrices()
    {
        $product = new Mage_Catalog_Model_Product();

        $this->assertNull($this->_model->prepareTierPrices($product));

        $data = $this->_model->prepareTierPrices($product,
            array((object) array('qty' => 3, 'price' => 8))
        );
        $this->assertEquals(
            array(array('website_id' => 0, 'cust_group' => 32000, 'price_qty' => 3, 'price' => 8)),
            $data
        );
    }
}
