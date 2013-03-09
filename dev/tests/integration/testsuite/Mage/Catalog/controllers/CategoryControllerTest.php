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
 * Test class for Mage_Catalog_CategoryController.
 *
 * @group module:Mage_Catalog
 * @magentoDataFixture Mage/Catalog/_files/categories.php
 */
class Mage_Catalog_CategoryControllerTest extends Magento_Test_TestCase_ControllerAbstract
{
    public function assert404NotFound()
    {
        parent::assert404NotFound();
        $this->assertNull(Mage::registry('current_category'));
    }

    public function getViewActionDataProvider()
    {
        return array(
            'category without children' => array(
                '$categoryId' => 5,
                '$expectedProductCount' => 1,
                array(
                    'CATEGORY_5',
                    'catalog_category_default',
                    'catalog_category_layered_nochildren',
                ),
                array(
                    'categorypath-category-1-category-1-1-category-1-1-1-html',
                    'category-category-1-1-1',
                    '<title>Category 1.1.1 - Category 1.1 - Category 1</title>',
                    '<h1>Category 1.1.1</h1>',
                    'Simple Product Two',
                    '$45.67',
                ),
            ),
            'anchor category' => array(
                '$categoryId' => 4,
                '$expectedProductCount' => 2,
                array(
                    'CATEGORY_4',
                    'catalog_category_layered',
                ),
                array(
                    'categorypath-category-1-category-1-1-html',
                    'category-category-1-1',
                    '<title>Category 1.1 - Category 1</title>',
                    '<h1>Category 1.1</h1>',
                    'Simple Product',
                    '$10.00',
                    'Simple Product Two',
                    '$45.67',
                ),
            ),
        );
    }

    /**
     * @dataProvider getViewActionDataProvider
     */
    public function testViewAction($categoryId, $expectedProductCount, array $expectedHandles, array $expectedContent)
    {
        $this->markTestIncomplete('Incomplete before Troll Team will fix');
        $this->dispatch("catalog/category/view/id/$categoryId");

        /** @var $currentCategory Mage_Catalog_Model_Category */
        $currentCategory = Mage::registry('current_category');
        $this->assertInstanceOf('Mage_Catalog_Model_Category', $currentCategory);
        $this->assertEquals($categoryId, $currentCategory->getId(), 'Category in registry.');

        $lastCategoryId = Mage::getSingleton('catalog/session')->getLastVisitedCategoryId();
        $this->assertEquals($categoryId, $lastCategoryId, 'Last visited category.');

        /* Layout updates */
        $handles = Mage::app()->getLayout()->getUpdate()->getHandles();
        foreach ($expectedHandles as $expectedHandleName) {
            $this->assertContains($expectedHandleName, $handles);
        }

        $responseBody = $this->getResponse()->getBody();

        /* Response content */
        foreach ($expectedContent as $expectedText) {
            $this->assertContains($expectedText, $responseBody);
        }

        $actualProductCount = substr_count($responseBody, '<h2 class="product-name">');
        $this->assertEquals($expectedProductCount, $actualProductCount, 'Number of products on the page.');
    }

    public function testViewActionNoCategoryId()
    {
        $this->markTestIncomplete('Incomplete before Troll Team will fix');
        $this->dispatch('catalog/category/view/');

        $this->assert404NotFound();
    }

    public function testViewActionInactiveCategory()
    {
        $this->markTestIncomplete('Incomplete before Troll Team will fix');
        $this->dispatch('catalog/category/view/id/8');

        $this->assert404NotFound();
    }
}
