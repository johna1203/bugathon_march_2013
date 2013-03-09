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
 * Test class for Mage_Catalog_Block_Product_List_Crosssell.
 *
 * @group module:Mage_Catalog
 * @magentoDataFixture Mage/Catalog/_files/products_crosssell.php
 */
class Mage_Catalog_Block_Product_List_CrosssellTest extends PHPUnit_Framework_TestCase
{
    public function testAll()
    {
        $product = new Mage_Catalog_Model_Product();
        $product->load(2);
        Mage::register('product', $product);
        $block = new Mage_Catalog_Block_Product_List_Crosssell();
        $block->setLayout(new Mage_Core_Model_Layout());
        $block->setTemplate('checkout/cart/crosssell.phtml');
        $block->setItemCount(1);

        $html = $block->toHtml();
        $this->assertNotEmpty($html);
        $this->assertContains('Simple Cross Sell', $html); /* name */
        $this->assertContains('product/1/', $html);  /* part of url */
        $this->assertInstanceOf('Mage_Catalog_Model_Resource_Product_Link_Product_Collection', $block->getItems());
    }
}
