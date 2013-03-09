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
 */
class Mage_Catalog_Model_Product_Type_Configurable_AttributeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Catalog_Model_Product_Type_Configurable_Attribute
     */
    protected $_model;

    protected function setUp()
    {
        $this->_model = new Mage_Catalog_Model_Product_Type_Configurable_Attribute;
    }

    public function testAddPrice()
    {
        $this->assertEmpty($this->_model->getPrices());
        $this->_model->addPrice(100);
        $this->assertEquals(array(100), $this->_model->getPrices());
    }

    public function testGetLabel()
    {
        $this->assertEmpty($this->_model->getLabel());
        $this->_model->setProductAttribute(new Varien_Object(array('store_label' => 'Store Label')));
        $this->assertEquals('Store Label', $this->_model->getLabel());

        $this->_model->setUseDefault(1)->setProductAttribute(new Varien_Object(array('store_label' => 'Other Label')));
        $this->assertEquals('Other Label', $this->_model->getLabel());
    }
}
