<?php
/**
 * {license_notice}
 *
 * @category    Magento
 * @package     Mage_Catalog
 * @subpackage  integration_tests
 * @copyright   {copyright}
 * @license     {license_link}
 */

/**
 * Test class for Mage_Catalog_Block_Product_New.
 *
 * @group module:Mage_Catalog
 * @magentoDataFixture Mage/Catalog/_files/products_new.php
 */
class Mage_Catalog_Block_Product_NewTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Catalog_Block_Product_New
     */
    protected $_block;

    protected function setUp()
    {
        $this->_block = new Mage_Catalog_Block_Product_New;
    }

    public function testGetCacheKeyInfo()
    {
        $info = $this->_block->getCacheKeyInfo();
        $keys = array_keys($info);

        /** order and values of cache key info elements is important */

        $this->assertSame(0, array_shift($keys));
        $this->assertEquals('CATALOG_PRODUCT_NEW', $info[0]);

        $this->assertSame(1, array_shift($keys));
        $this->assertEquals(Mage::app()->getStore()->getId(), $info[1]);

        $this->assertSame(2, array_shift($keys));
        $this->assertEquals(Mage::getDesign()->getPackageName(), $info[2]);
        $this->assertSame(3, array_shift($keys));
        $this->assertEquals(Mage::getDesign()->getTheme('template'), $info[3]);

        $this->assertSame(4, array_shift($keys));
        $this->assertNotEquals('', $info[4]);

        $this->assertSame('template', array_shift($keys));
        /**
         * This block is implemented without template by default (invalid).
         * Having the cache key fragment with empty value can potentially lead to caching bugs
         */

        $this->assertSame(5, array_shift($keys));
        $this->assertNotEquals('', $info[5]);
    }

    public function testSetGetProductsCount()
    {
        $this->assertEquals(Mage_Catalog_Block_Product_New::DEFAULT_PRODUCTS_COUNT, $this->_block->getProductsCount());
        $this->_block->setProductsCount(100);
        $this->assertEquals(100, $this->_block->getProductsCount());
    }

    public function testToHtml()
    {
        $this->markTestIncomplete("Functionality not compatible with Magento 1.x");
        $this->assertEmpty($this->_block->getProductCollection());

        $this->_block->setProductsCount(5);
        $this->_block->setTemplate('catalog/product/widget/new/content/new_list.phtml');
        $this->_block->setLayout(new Mage_Core_Model_Layout());

        $html = $this->_block->toHtml();
        $this->assertNotEmpty($html);
        $this->assertContains('New Product', $html);
        $this->assertInstanceOf(
            'Mage_Catalog_Model_Resource_Product_Collection', $this->_block->getProductCollection()
        );
    }
}
