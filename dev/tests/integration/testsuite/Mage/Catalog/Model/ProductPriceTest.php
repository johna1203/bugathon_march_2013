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
 * Tests product model:
 * - pricing behaviour is tested
 *
 * @group module:Mage_Catalog
 * @see Mage_Catalog_Model_ProductTest
 * @see Mage_Catalog_Model_ProductExternalTest
 */
class Mage_Catalog_Model_ProductPriceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Catalog_Model_Product
     */
    protected $_model;

    protected function setUp()
    {
        $this->_model = new Mage_Catalog_Model_Product;
    }

    public function testGetPrice()
    {
        $this->assertEmpty($this->_model->getPrice());
        $this->_model->setPrice(10.0);
        $this->assertEquals(10.0, $this->_model->getPrice());
    }

    public function testGetPriceModel()
    {
        $default = $this->_model->getPriceModel();
        $this->assertInstanceOf('Mage_Catalog_Model_Product_Type_Price', $default);
        $this->assertSame($default, $this->_model->getPriceModel());

        $this->_model->setTypeId('configurable');
        $type = $this->_model->getPriceModel();
        $this->assertInstanceOf('Mage_Catalog_Model_Product_Type_Configurable_Price', $type);
        $this->assertSame($type, $this->_model->getPriceModel());
    }

    /**
     * See detailed tests at Mage_Catalog_Model_Product_Type*_PriceTest
     */
    public function testGetTierPrice()
    {
        $this->assertEquals(array(), $this->_model->getTierPrice());
    }

    /**
     * See detailed tests at Mage_Catalog_Model_Product_Type*_PriceTest
     */
    public function testGetTierPriceCount()
    {
        $this->assertEquals(0, $this->_model->getTierPriceCount());
    }

    /**
     * See detailed tests at Mage_Catalog_Model_Product_Type*_PriceTest
     */
    public function testGetFormatedTierPrice()
    {
        $this->assertEquals(array(), $this->_model->getFormatedTierPrice());
    }

    /**
     * See detailed tests at Mage_Catalog_Model_Product_Type*_PriceTest
     */
    public function testGetFormatedPrice()
    {
        $this->assertEquals('<span class="price">$0.00</span>', $this->_model->getFormatedPrice());
    }

    public function testSetGetFinalPrice()
    {
        $this->assertEquals(0, $this->_model->getFinalPrice());
        $this->_model->setFinalPrice(10);
        $this->assertEquals(10, $this->_model->getFinalPrice());
    }
}
