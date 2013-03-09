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
 * Test class for Mage_Catalog_Model_Category_Api.
 *
 * @group module:Mage_Catalog
 * @magentoDataFixture Mage/Catalog/_files/categories.php
 */
class Mage_Catalog_Model_Category_ApiTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Catalog_Model_Category_Api
     */
    protected $_model;

    protected function setUp()
    {
        $this->_model = new Mage_Catalog_Model_Category_Api;
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
    }

    public function testLevel()
    {
        $default = $this->_model->level();
        $this->assertNotEmpty($default);

        $forWebsite = $this->_model->level(1);
        $this->assertNotEmpty($forWebsite);

        $this->assertEquals($default, $forWebsite);
        $this->assertEquals(
            $default,
            array(
                array(
                    'category_id'   => 2,
                    'parent_id'     => 1,
                    'name'          => 'Default Category',
                    'is_active'     => 1,
                    'position'      => 1,
                    'level'         => 1
                )
            )
        );

    }

    public function testTree()
    {
        $tree = $this->_model->tree();
        $this->assertNotEmpty($tree);
        $this->assertArrayHasKey('category_id', $tree);
        $this->assertArrayHasKey('name', $tree);
        $this->assertEquals(Mage_Catalog_Model_Category::TREE_ROOT_ID, $tree['category_id']);
    }

    public function testCRUD()
    {
        $categoryId = $this->_model->create(1, array(
            'name'              => 'test category',
            'available_sort_by' => 'name',
            'default_sort_by'   => 'name',
            'is_active'         => 1,
            'include_in_menu'   => 1
        ));
        $this->assertNotEmpty($categoryId);
        $data = $this->_model->info($categoryId);
        $this->assertNotEmpty($data);
        $this->assertEquals('test category', $data['name']);

        $this->_model->update($categoryId, array(
            'name'              => 'new name',
            'available_sort_by' => 'name',
            'default_sort_by'   => 'name',
            'is_active'         => 1,
            'include_in_menu'   => 1
        ));
        $data = $this->_model->info($categoryId);
        $this->assertEquals('new name', $data['name']);

        $this->_model->delete($categoryId);
    }

    public function testMove()
    {
        $this->assertTrue($this->_model->move(7, 6, 0));
    }

    public function testAssignedProducts()
    {
        $this->markTestSkipped('Should be fixed for current version');
        $this->assertEmpty($this->_model->assignedProducts(1));
        $this->assertEquals(
            array(array(
                'product_id' => 1,
                'type' => 'simple',
                'set' => 4,
                'sku' => 'simple',
                'position' => '1',
            )),
            $this->_model->assignedProducts(3)
        );
    }

    public function testAssignProduct()
    {
        $this->assertEmpty($this->_model->assignedProducts(6));
        $this->assertTrue($this->_model->assignProduct(6, 1));
        $this->assertNotEmpty($this->_model->assignedProducts(6));
    }

    /**
     * @depends testAssignProduct
     */
    public function testUpdateProduct()
    {
        $this->markTestSkipped('Should be fixed for current version');
        $this->assertTrue($this->_model->updateProduct(6, 1, 2));
        $this->assertEquals(
            array(array(
                'product_id' => 1,
                'type' => 'simple',
                'set' => 4,
                'sku' => 'simple',
                'position' => '2',
            )),
            $this->_model->assignedProducts(6)
        );
    }

    /**
     * @depends testAssignProduct
     */
    public function testRemoveProduct()
    {
        $this->assertNotEmpty($this->_model->assignedProducts(6));
        $this->assertTrue($this->_model->removeProduct(6, 1));
        $this->assertEmpty($this->_model->assignedProducts(6));
    }
}
