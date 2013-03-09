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
class Mage_Catalog_Helper_Category_FlatTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Catalog_Helper_Category_Flat
     */
    protected $_helper;

    protected function setUp()
    {
        $this->_helper = new Mage_Catalog_Helper_Category_Flat;
    }

    public function testIsEnabledDefault()
    {
        $this->assertFalse($this->_helper->isEnabled());
        $this->assertFalse($this->_helper->isEnabled(true));
    }

    /**
     * @magentoConfigFixture current_store catalog/frontend/flat_catalog_category 1
     */
    public function testIsEnabled()
    {
        $this->assertTrue($this->_helper->isEnabled());
    }

    public function testIsRebuilt()
    {
        $this->assertFalse($this->_helper->isRebuilt());
    }

    public function testIsBuilt()
    {
        $this->assertEquals($this->_helper->isBuilt(), $this->_helper->isEnabled(true));
    }
}
