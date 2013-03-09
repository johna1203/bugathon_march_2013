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
 * Test class for Mage_Catalog_Model_Category_Attribute_Api.
 *
 * @group module:Mage_Catalog
 */
class Mage_Catalog_Model_Category_Attribute_ApiTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Catalog_Model_Category_Attribute_Api
     */
    protected $_model;

    protected function setUp()
    {
        $this->_model = new Mage_Catalog_Model_Category_Attribute_Api;
    }

    public function testItems()
    {
        $attributes = $this->_model->items();
        $this->assertNotEmpty($attributes);
        $attribute = array_shift($attributes);
        $this->assertContains('attribute_id', array_keys($attribute));
        $this->assertContains('code', array_keys($attribute));
    }

    /**
     * Internal assert that validate options structure
     *
     * @param array $options
     */
    protected function _assertOptionsStructure(array $options)
    {
        $first = current($options);
        $this->assertArrayHasKey('value', $first);
        $this->assertArrayHasKey('label', $first);
    }

    public function testLayoutOptions()
    {
        $options = $this->_model->options('page_layout');
        $this->assertNotEmpty($options);
        $this->_assertOptionsStructure($options);
    }

    public function testModeOptions()
    {
        $options = $this->_model->options('display_mode');
        $this->assertNotEmpty($options);
        $this->_assertOptionsStructure($options);
    }

    public function testPageOptions()
    {
        $options = $this->_model->options('landing_page');
        $this->assertNotEmpty($options);
        $this->_assertOptionsStructure($options);
    }

    public function testSortByOptions()
    {
        $options = $this->_model->options('available_sort_by');
        $this->assertNotEmpty($options);
        $this->_assertOptionsStructure($options);
    }

    /**
     * @expectedException Mage_Api_Exception
     */
    public function testFault()
    {
        $this->_model->options('not_exists');
    }
}
