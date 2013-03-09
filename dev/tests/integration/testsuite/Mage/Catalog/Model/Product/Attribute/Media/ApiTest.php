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
 * Test class for Mage_Catalog_Model_Product_Attribute_Media_Api.
 *
 * @group module:Mage_Catalog
 * @magentoDataFixture Mage/Catalog/_files/product_simple.php
 * @magentoDataFixture productMediaFixture
 */
class Mage_Catalog_Model_Product_Attribute_Media_ApiTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Catalog_Model_Product_Attribute_Media_Api
     */
    protected $_model;

    protected static $_fixtureDir;

    protected function setUp()
    {
        $this->_model = new Mage_Catalog_Model_Product_Attribute_Media_Api;
    }

    public static function setUpBeforeClass()
    {
        self::$_fixtureDir = realpath(dirname(__FILE__) . '/../../../../_files');
        $mediaTmpDir = Mage::getSingleton('catalog/product_media_config')->getTmpMediaPath();
        mkdir("$mediaTmpDir/m/a", 0777, true);
        copy(self::$_fixtureDir . '/magento_image.jpg', $mediaTmpDir . '/m/a/magento_image.jpg');
    }

    public static function productMediaFixture()
    {
        $product = new Mage_Catalog_Model_Product();
        $product->load(1);
        $product->setTierPrice(array());
        $product->setData('media_gallery', array('images' => array(array('file' => '/m/a/magento_image.jpg',),)));
        $product->save();
    }

    /**
     * @covers Mage_Catalog_Model_Product_Attribute_Media_Api::items
     * @covers Mage_Catalog_Model_Product_Attribute_Media_Api::info
     */
    public function testItemsAndInfo()
    {
        $items = $this->_model->items(1);
        $this->assertNotEmpty($items);
        $this->assertEquals(1, count($items));
        $item = current($items);
        $this->assertArrayHasKey('file', $item);
        $this->assertArrayHasKey('label', $item);;
        $this->assertArrayHasKey('url', $item);

        $info = $this->_model->info(1, $item['file']);
        $this->assertArrayHasKey('file', $info);
        $this->assertArrayHasKey('label', $info);;
        $this->assertArrayHasKey('url', $info);
        return $item['file'];
    }

    /**
     * @depends testItemsAndInfo
     */
    public function testCreate()
    {
        $data = array(
            'file' => array(
                'mime'      => 'image/jpeg',
                'content'   => base64_encode(file_get_contents(self::$_fixtureDir.'/magento_small_image.jpg'))
            )
        );
        $this->_model->create(1, $data);
        $items = $this->_model->items(1);
        $this->assertEquals(2, count($items));
    }

    public function createFaultDataProvider()
    {
        return array(
            array(),
            array('file' => array('mime' => 'test')),
            array('file' => array('mime' => 'image/jpeg', 'content' => 'not valid'))
        );
    }

    /**
     * @dataProvider createFaultDataProvider
     * @expectedException Mage_Api_Exception
     */
    public function testCreateFault($data)
    {
        $this->_model->create(1, $data);
    }

    /**
     * @depends testItemsAndInfo
     */
    public function testUpdate($file)
    {
        $data = array(
            'file' => array(
                'mime'      => 'image/jpeg',
                'content'   => base64_encode(file_get_contents(self::$_fixtureDir.'/magento_small_image.jpg'))
            )
        );
        $this->assertTrue($this->_model->update(1, $file, $data));
    }

    /**
     * @depends testItemsAndInfo
     * @expectedException Mage_Api_Exception
     */
    public function testRemove($file)
    {
        $this->assertTrue($this->_model->remove(1, $file));
        $this->_model->info(1, $file);
    }

    public function testTypes()
    {
        $types = $this->_model->types(4);
        $this->assertNotEmpty($types);
        $this->assertInternalType('array', $types);
        $type = current($types);
        $this->assertArrayHasKey('code', $type);
        $this->assertArrayHasKey('scope', $type);
    }
}
