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
 * Test class for Mage_Catalog_Seo_SitemapController.
 *
 * @group module:Mage_Catalog
 * @magentoDataFixture Mage/Catalog/_files/categories.php
 */
class Mage_Catalog_Seo_SitemapControllerTest extends Magento_Test_TestCase_ControllerAbstract
{
    public function testCategoryAction()
    {
        $this->markTestSkipped('Should be fixed for current version');
        $this->dispatch('catalog/seo_sitemap/category/');

        $responseBody = $this->getResponse()->getBody();

        /* General content */
        $this->assertContains('<h1>Categories</h1>', $responseBody);
        $this->assertContains('5 Item(s)', $responseBody);

        /* Sitemap content */
        $matchesCount = preg_match('#<ul class="sitemap">.+?</ul>#s', $responseBody, $matches);
        $this->assertEquals(1, $matchesCount);
        $listHtml = $matches[0];

        $this->assertContains('Category 1', $listHtml);
        $this->assertContains('Category 1.1', $listHtml);
        $this->assertContains('Category 1.1.1', $listHtml);
        $this->assertContains('Category 2', $listHtml);
        $this->assertContains('Movable', $listHtml);

        $this->assertContains('category-1.html', $listHtml);

        $this->markTestIncomplete('Bug MAGETWO-144');

        $this->assertContains('http://localhost/index.php/category-1/category-1-1.html', $listHtml);
        $this->assertContains('http://localhost/index.php/category-1/category-1-1/category-1-1-1.html', $listHtml);
        $this->assertContains('http://localhost/index.php/category-2.html', $listHtml);
        $this->assertContains('http://localhost/index.php/movable.html', $listHtml);
    }

    /**
     * @magentoConfigFixture current_store catalog/sitemap/tree_mode 1
     */
    public function testCategoryActionTreeMode()
    {
        $this->markTestIncomplete("Functionality not compatible with Magento 1.x");
        $this->dispatch('catalog/seo_sitemap/category/');

        /* Layout updates */
        $handles = Mage::app()->getLayout()->getUpdate()->getHandles();
        $this->assertContains('catalog_seo_sitemap_category_tree', $handles);
    }

    public function testProductAction()
    {
        $this->markTestSkipped('Should be fixed for current version');
        $this->dispatch('catalog/seo_sitemap/product/');

        $responseBody = $this->getResponse()->getBody();

        /* General content */
        $this->assertContains('<h1>Products</h1>', $responseBody);
        $this->assertContains('2 Item(s)', $responseBody);

        /* Sitemap content */
        $matchesCount = preg_match('#<ul class="sitemap">.+?</ul>#s', $responseBody, $matches);
        $this->assertEquals(1, $matchesCount);
        $listHtml = $matches[0];

        $this->assertContains('Simple Product', $listHtml);
        $this->assertContains('Simple Product Two', $listHtml);

        $this->assertContains('http://localhost/index.php/simple-product.html', $listHtml);
        $this->assertContains('http://localhost/index.php/simple-product-two.html', $listHtml);
    }
}
