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
class Mage_Catalog_Helper_Product_FlatTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Catalog_Helper_Product_Flat
     */
    protected $_helper;

    protected function setUp()
    {
        $this->_helper = new Mage_Catalog_Helper_Product_Flat;
    }

    public function testGetFlag()
    {
        $flag = $this->_helper->getFlag();
        $this->assertInstanceOf('Mage_Catalog_Model_Product_Flat_Flag', $flag);
    }

    public function testIsBuilt()
    {
        $this->markTestSkipped('Should be fixed for current version');
        $this->assertFalse($this->_helper->isBuilt());
        $flag = $this->_helper->getFlag();
        try {
            $flag->setIsBuilt(true);
            $this->assertTrue($this->_helper->isBuilt());

            $flag->setIsBuilt(false);
        } catch (Exception $e) {
            $flag->setIsBuilt(false);
            throw $e;
        }
    }

    public function testIsEnabledDefault()
    {

        $this->assertFalse($this->_helper->isEnabled());
    }

    /**
     * @magentoConfigFixture current_store catalog/frontend/flat_catalog_product 1
     */
    public function testIsEnabled()
    {
        $storeId = Mage::app()->getStore()->getId();
        try {
            // true
            $this->assertFalse($this->_helper->isEnabled());

            // admin in "true" returns "false"
            Mage::app()->getStore()->setId(Mage_Core_Model_App::ADMIN_STORE_ID);
            $this->assertFalse($this->_helper->isEnabled());

            Mage::app()->getStore()->setId($storeId);
        } catch (Exception $e) {
            Mage::app()->getStore()->setId($storeId);
            throw $e;
        }
    }

    public function testIsAddFilterableAttributesDefault()
    {
        $this->assertEquals(0, $this->_helper->isAddFilterableAttributes());
    }

    /**
     * @magentoConfigFixture global/catalog/product/flat/add_filterable_attributes 1
     */
    public function testIsAddFilterableAttributes()
    {
        $this->assertEquals(1, $this->_helper->isAddFilterableAttributes());
    }

    public function testIsAddChildDataDefault()
    {
        $this->assertEquals(0, $this->_helper->isAddChildData());
    }

    /**
     * @magentoConfigFixture global/catalog/product/flat/add_child_data 1
     */
    public function testIsAddChildData()
    {
        $this->assertEquals(1, $this->_helper->isAddChildData());
    }
}
