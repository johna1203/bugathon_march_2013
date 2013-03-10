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
 * Test class for Mage_Catalog_Block_Product_View_Options.
 *
 * @group module:Mage_Catalog
 * @magentoDataFixture Mage/Catalog/_files/product_simple.php
 */
class Mage_Catalog_Block_Product_View_OptionsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Catalog_Block_Product_View_Options
     */
    protected $_block;

    /**
     * @var Mage_Catalog_Model_Product
     */
    protected $_product;

    protected function setUp()
    {
        $this->_product = new Mage_Catalog_Model_Product();
        $this->_product->load(1);
        Mage::unregister('current_product');
        Mage::register('current_product', $this->_product);
        $this->_block = new Mage_Catalog_Block_Product_View_Options;
    }

    public function testSetGetProduct()
    {
        $this->assertSame($this->_product, $this->_block->getProduct());

        $product = new Mage_Catalog_Model_Product();
        $this->_block->setProduct($product);
        $this->assertSame($product, $this->_block->getProduct());
    }

    public function testAddAndGetOptionRenderer()
    {
        $this->_block->addOptionRenderer('test', 'test/test', 'test.phtml');
        $this->assertEquals(
            array(
                'block'     => 'test/test',
                'template'  => 'test.phtml',
                'renderer'  => null,
            ),
            $this->_block->getOptionRender('test')
        );

        $this->assertEquals(
            array(
                'block'     => 'catalog/product_view_options_type_default',
                'template'  => 'catalog/product/view/options/type/default.phtml',
                'renderer'  => null,
            ),
            $this->_block->getOptionRender('not_exists')
        );

    }

    public function testGetGroupOfOption()
    {
        $this->assertEquals('default', $this->_block->getGroupOfOption('test'));
    }

    public function testGetOptions()
    {
        $options = $this->_block->getOptions();
        $this->assertNotEmpty($options);
        foreach ($options as $option) {
            $this->assertInstanceOf('Mage_Catalog_Model_Product_Option', $option);
        }
    }

    public function testHasOptions()
    {
        $this->assertTrue($this->_block->hasOptions());
    }

    public function testGetJsonConfig()
    {
        $config = json_decode($this->_block->getJsonConfig());
        $this->assertNotNull($config);
        $this->assertNotEmpty($config);
    }

    public function testGetOptionHtml()
    {
        $this->_block->addOptionRenderer(
            'select', 'catalog/product_view_options_type_select', 'catalog/product/view/options/type/select.phtml'
        );
        $this->_block->addOptionRenderer(
            'date', 'catalog/product_view_options_type_date', 'catalog/product/view/options/type/date.phtml'
        );
        $this->_block->setLayout(Mage::app()->getLayout());
        $html = false;
        foreach ($this->_block->getOptions() as $option) {
            $html = $this->_block->getOptionHtml($option);
            $this->assertContains('Test', $html); /* contain Test in option title */
        }
        if (!$html) {
            $this->fail('Product with options is required for test');
        }
    }
}
