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
 * Test class for Mage_Catalog_ProductController.
 *
 * @group module:Mage_Catalog
 */
class Mage_Catalog_ProductControllerTest extends Magento_Test_TestCase_ControllerAbstract
{
    public function assert404NotFound()
    {
        parent::assert404NotFound();
        $this->assertNull(Mage::registry('current_product'));
    }

    protected function _getProductImageFile()
    {
        $product = new Mage_Catalog_Model_Product();
        $product->load(1);
        $images = $product->getMediaGalleryImages()->getItems();
        $image = reset($images);
        return $image['file'];
    }

    /**
     * @magentoDataFixture Mage/Catalog/controllers/_files/products.php
     */
    public function testViewAction()
    {
        $this->dispatch('catalog/product/view/id/1');

        /** @var $currentProduct Mage_Catalog_Model_Product */
        $currentProduct = Mage::registry('current_product');
        $this->assertInstanceOf('Mage_Catalog_Model_Product', $currentProduct);
        $this->assertEquals(1, $currentProduct->getId());

        $lastViewedProductId = Mage::getSingleton('catalog/session')->getLastViewedProductId();
        $this->assertEquals(1, $lastViewedProductId);

        /* Layout updates */
        $handles = Mage::app()->getLayout()->getUpdate()->getHandles();
        $this->assertContains('PRODUCT_TYPE_simple', $handles);
        $this->assertContains('PRODUCT_1', $handles);

        $responseBody = $this->getResponse()->getBody();
        /* Product info */
        $this->assertContains('Simple Product 1 Name', $responseBody);
        $this->assertContains('Simple Product 1 Full Description', $responseBody);
        $this->assertContains('Simple Product 1 Short Description', $responseBody);
        /* Stock info */

        $this->markTestIncomplete("Functionality not compatible with Magento 1.x");

        $this->assertContains('$1,234.56', $responseBody);
        $this->assertContains('In stock', $responseBody);
        $this->assertContains('Add to Cart', $responseBody);
        /* Meta info */
        $this->assertContains('<title>Simple Product 1 Meta Title</title>', $responseBody);
        $this->assertContains('<meta name="keywords" content="Simple Product 1 Meta Keyword" />', $responseBody);
        $this->assertContains('<meta name="description" content="Simple Product 1 Meta Description" />', $responseBody);
    }

    public function testViewActionNoProductId()
    {
        $this->markTestIncomplete('Incomplete before Troll Team will fix');
        $this->dispatch('catalog/product/view/id/');

        $this->assert404NotFound();
    }

    public function testViewActionRedirect()
    {
        $this->dispatch('catalog/product/view/?store=default');

        $this->assertRedirect();
    }

    /**
     * @magentoDataFixture Mage/Catalog/controllers/_files/products.php
     */
    public function testGalleryAction()
    {
        $this->markTestIncomplete('Incomplete before Troll Team will fix');
        $this->dispatch('catalog/product/gallery/id/1');

        $this->assertContains('http://localhost/media/catalog/product/', $this->getResponse()->getBody());
        $this->assertContains($this->_getProductImageFile(), $this->getResponse()->getBody());
    }

    public function testGalleryActionRedirect()
    {
        $this->dispatch('catalog/product/gallery/?store=default');

        $this->assertRedirect();
    }

    public function testGalleryActionNoProduct()
    {
        $this->markTestIncomplete('Incomplete before Troll Team will fix');
        $this->dispatch('catalog/product/gallery/id/');

        $this->assert404NotFound();
    }

    /**
     * @magentoDataFixture Mage/Catalog/controllers/_files/products.php
     */
    public function testImageAction()
    {
        $this->markTestSkipped("All logic has been cut to avoid possible malicious usage of the method");
        ob_start();
        /* Preceding slash in URL is required in this case */
        $this->dispatch('/catalog/product/image' . $this->_getProductImageFile());
        $imageContent = ob_get_clean();
        /**
         * Check against PNG file signature.
         * @link http://www.libpng.org/pub/png/spec/1.2/PNG-Rationale.html#R.PNG-file-signature
         */
        $this->assertStringStartsWith(sprintf("%cPNG\r\n%c\n", 137, 26), $imageContent);
    }

    public function testImageActionNoImage()
    {
        $this->markTestIncomplete('Incomplete before Troll Team will fix');
        $this->dispatch('catalog/product/image/');

        $this->assert404NotFound();
    }
}
