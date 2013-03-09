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
 * Test class for Mage_Catalog_Model_Category_Api_V2.
 *
 * @group module:Mage_Catalog
 */
class Mage_Catalog_Model_Category_Api_V2Test extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Catalog_Model_Category_Api_V2
     */
    protected $_model;

    protected function setUp()
    {
        $this->_model = new Mage_Catalog_Model_Category_Api_V2;
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
    }

    public function testCRUD()
    {
        // @codingStandardsIgnoreStart
        $category = new stdClass();
        $category->name                 = 'test category';
        $category->available_sort_by    = 'name';
        $category->default_sort_by      = 'name';
        $category->is_active            = 1;
        $category->include_in_menu      = 1;
        // @codingStandardsIgnoreEnd

        $categoryId = $this->_model->create(1, $category);
        $this->assertNotEmpty($categoryId);
        $data = $this->_model->info($categoryId);
        $this->assertNotEmpty($data);
        $this->assertEquals($category->name, $data['name']);
        // @codingStandardsIgnoreStart
        $this->assertEquals($category->default_sort_by, $data['default_sort_by']);
        $this->assertEquals($category->is_active, $data['is_active']);
        // @codingStandardsIgnoreEnd

        $category->name = 'new name';
        $this->_model->update($categoryId, $category);
        $data = $this->_model->info($categoryId);
        $this->assertNotEmpty($data);
        $this->assertEquals($category->name, $data['name']);

        $this->assertTrue($this->_model->delete($categoryId));
    }

}
