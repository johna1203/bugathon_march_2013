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
 * Tests product model:
 * - general behaviour is tested (external interaction and pricing is not tested there)
 *
 * @group module:Mage_Catalog
 * @see Mage_Catalog_Model_ProductExternalTest
 * @see Mage_Catalog_Model_ProductPriceTest
 * @magentoDataFixture Mage/Catalog/_files/categories.php
 */
class Mage_Catalog_Model_ProductTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Catalog_Model_Product
     */
    protected $_model;

    protected function setUp()
    {
        $this->_model = new Mage_Catalog_Model_Product;
    }

    public function testGetResourceCollection()
    {
        $collection = $this->_model->getResourceCollection();
        $this->assertInstanceOf('Mage_Catalog_Model_Resource_Product_Collection', $collection);
        $this->assertEquals($this->_model->getStoreId(), $collection->getStoreId());
    }

    public function testGetUrlModel()
    {
        $model = $this->_model->getUrlModel();
        $this->assertInstanceOf('Mage_Catalog_Model_Product_Url', $model);
        $this->assertSame($model, $this->_model->getUrlModel());
    }

    public function testGetName()
    {
        $this->assertEmpty($this->_model->getName());
        $this->_model->setName('test');
        $this->assertEquals('test', $this->_model->getName());
    }

    public function testGetTypeId()
    {
        $this->assertEmpty($this->_model->getTypeId());
        $this->_model->setTypeId('simple');
        $this->assertEquals('simple', $this->_model->getTypeId());
    }

    public function testGetStatus()
    {
        $this->assertEquals(Mage_Catalog_Model_Product_Status::STATUS_ENABLED, $this->_model->getStatus());
        $this->_model->setStatus(Mage_Catalog_Model_Product_Status::STATUS_DISABLED);
        $this->assertEquals(Mage_Catalog_Model_Product_Status::STATUS_DISABLED, $this->_model->getStatus());
    }

    public function testGetSetTypeInstance()
    {
        // model getter
        $model = $this->_model->getTypeInstance();
        $this->assertInstanceOf('Mage_Catalog_Model_Product_Type_Abstract', $model);
        $this->assertSame($model, $this->_model->getTypeInstance());

        // singleton getter
        $singleton = $this->_model->getTypeInstance(true);
        $this->assertInstanceOf('Mage_Catalog_Model_Product_Type_Abstract', $singleton);
        $this->assertNotSame($model, $this->_model->getTypeInstance(true));
        $this->assertSame($singleton, $this->_model->getTypeInstance(true));

        // model setter
        $simpleModel = new Mage_Catalog_Model_Product_Type_Simple;
        $this->_model->setTypeInstance($simpleModel);
        $this->assertSame($simpleModel, $this->_model->getTypeInstance());
        $this->assertNotSame($model, $this->_model->getTypeInstance());

        // singleton setter
        $simpleSingleton = new Mage_Catalog_Model_Product_Type_Simple;
        $this->_model->setTypeInstance($simpleSingleton, true);
        $this->assertNotSame($model, $this->_model->getTypeInstance(true));
        $this->assertNotSame($simpleModel, $this->_model->getTypeInstance(true));
        $this->assertSame($simpleSingleton, $this->_model->getTypeInstance(true));
        $this->assertSame($simpleSingleton, $this->_model->getTypeInstance(true));
    }

    public function testGetIdBySku()
    {
        $this->markTestSkipped('Should be fixed for current version');
        $this->assertEquals(1, $this->_model->getIdBySku('simple')); // fixture
    }

    public function testGetAttributes()
    {
        // fixture required
        $this->_model->load(1);
        $attributes = $this->_model->getAttributes();
        $this->assertArrayHasKey('name', $attributes);
        $this->assertArrayHasKey('sku', $attributes);
        $this->assertInstanceOf('Mage_Catalog_Model_Resource_Eav_Attribute', $attributes['sku']);
    }

    public function testCanAffectOptions()
    {
        $this->assertFalse($this->_model->canAffectOptions());
        $this->_model->canAffectOptions(true);
        $this->assertTrue($this->_model->canAffectOptions());
    }

    /**
     * @magentoAppIsolation enabled
     */
    public function testCRUD()
    {
        Mage::app()->setCurrentStore(Mage::app()->getStore(Mage_Core_Model_App::ADMIN_STORE_ID));
        $this->_model->setTypeId('simple')->setAttributeSetId(4)
            ->setName('Simple Product')->setSku(uniqid())->setPrice(10)
            ->setMetaTitle('meta title')->setMetaKeyword('meta keyword')->setMetaDescription('meta description')
            ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
            ->setStatus(Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
        ;
        $crud = new Magento_Test_Entity($this->_model, array('sku' => uniqid()));
        $crud->testCrud();
    }

    public function testCleanCache()
    {
        Mage::app()->saveCache('test', 'catalog_product_999', array('catalog_product_999'));
        // potential bug: it cleans by cache tags, generated from its ID, which doesn't make much sense
        $this->_model->setId(999)->cleanCache();
        $this->assertEmpty(Mage::app()->loadCache('catalog_product_999'));
    }

    /**
     * @covers Mage_Catalog_Model_Product::getCalculatedFinalPrice
     * @covers Mage_Catalog_Model_Product::getMinimalPrice
     * @covers Mage_Catalog_Model_Product::getSpecialPrice
     * @covers Mage_Catalog_Model_Product::getSpecialFromDate
     * @covers Mage_Catalog_Model_Product::getSpecialToDate
     * @covers Mage_Catalog_Model_Product::getRequestPath
     * @covers Mage_Catalog_Model_Product::getGiftMessageAvailable
     * @covers Mage_Catalog_Model_Product::getRatingSummary
     * @dataProvider getObsoleteGettersDataProvider
     * @param string $key
     * @param string $method
     */
    public function testGetObsoleteGetters($key, $method)
    {
        $value = uniqid();
        $this->assertEmpty($this->_model->$method());
        $this->_model->setData($key, $value);
        $this->assertEquals($value, $this->_model->$method());
    }

    public function getObsoleteGettersDataProvider()
    {
        return array(
            array('calculated_final_price', 'getCalculatedFinalPrice'),
            array('minimal_price', 'getMinimalPrice'),
            array('special_price', 'getSpecialPrice'),
            array('special_from_date', 'getSpecialFromDate'),
            array('special_to_date', 'getSpecialToDate'),
            array('request_path', 'getRequestPath'),
            array('gift_message_available', 'getGiftMessageAvailable'),
            array('rating_summary', 'getRatingSummary'),
        );
    }

    public function testGetMediaAttributes()
    {
        $model = new Mage_Catalog_Model_Product(array('media_attributes' => 'test'));
        $this->assertEquals('test', $model->getMediaAttributes());

        $attributes = $this->_model->getMediaAttributes();
        $this->assertArrayHasKey('image', $attributes);
        $this->assertArrayHasKey('small_image', $attributes);
        $this->assertArrayHasKey('thumbnail', $attributes);
        $this->assertInstanceOf('Mage_Catalog_Model_Resource_Eav_Attribute', $attributes['image']);
    }

    public function testGetMediaGalleryImages()
    {
        $model = new Mage_Catalog_Model_Product;
        $this->assertEmpty($model->getMediaGalleryImages());

        $this->_model->setMediaGallery(array('images' => array(array('file' => 'magento_image.jpg'))));
        $images = $this->_model->getMediaGalleryImages();
        $this->assertInstanceOf('Varien_Data_Collection', $images);
        foreach ($images as $image) {
            $this->assertInstanceOf('Varien_Object', $image);
            $image = $image->getData();
            $this->assertArrayHasKey('file', $image);
            $this->assertArrayHasKey('url', $image);
            $this->assertArrayHasKey('id', $image);
            $this->assertArrayHasKey('path', $image);
            $this->assertStringEndsWith('magento_image.jpg', $image['file']);
            $this->assertStringEndsWith('magento_image.jpg', $image['url']);
            $this->assertStringEndsWith('magento_image.jpg', $image['path']);
        }
    }

    public function testAddImageToMediaGallery()
    {
            $this->_model->addImageToMediaGallery(dirname(dirname(__FILE__)) . '/_files/magento_image.jpg');
            $gallery = $this->_model->getData('media_gallery');
            $this->assertNotEmpty($gallery);
            $this->assertTrue(isset($gallery['images'][0]['file']));
            $this->assertStringStartsWith('/m/a/magento_image', $gallery['images'][0]['file']);
            $this->assertTrue(isset($gallery['images'][0]['position']));
            $this->assertTrue(isset($gallery['images'][0]['disabled']));
            $this->assertArrayHasKey('label', $gallery['images'][0]);
    }

    public function testGetMediaConfig()
    {
        $model = $this->_model->getMediaConfig();
        $this->assertInstanceOf('Mage_Catalog_Model_Product_Media_Config', $model);
        $this->assertSame($model, $this->_model->getMediaConfig());
    }

    /**
     * @magentoAppIsolation enabled
     */
    public function testDuplicate()
    {
        $this->_model->load(1); // fixture
        $duplicate = $this->_model->duplicate();
        try {
            $this->assertNotEmpty($duplicate->getId());
            $this->assertNotEquals($duplicate->getId(), $this->_model->getId());
            $this->assertNotEquals($duplicate->getSku(), $this->_model->getSku());
            $this->assertEquals(Mage_Catalog_Model_Product_Status::STATUS_DISABLED, $duplicate->getStatus());
            $this->_undo($duplicate);
        } catch (Exception $e) {
            $this->_undo($duplicate);
            throw $e;
        }
    }

    /**
     * Delete model
     *
     * @param Mage_Core_Model_Abstract $duplicate
     */
    protected function _undo($duplicate)
    {
        Mage::app()->getStore()->setId(Mage_Core_Model_App::ADMIN_STORE_ID);
        $duplicate->delete();
    }

    /**
     * @covers Mage_Catalog_Model_Product::isGrouped
     * @covers Mage_Catalog_Model_Product::isSuperGroup
     * @covers Mage_Catalog_Model_Product::isSuper
     */
    public function testIsGrouped()
    {
        $this->assertFalse($this->_model->isGrouped());
        $this->assertFalse($this->_model->isSuperGroup());
        $this->assertFalse($this->_model->isSuper());
        $this->_model->setTypeId(Mage_Catalog_Model_Product_Type::TYPE_GROUPED);
        $this->assertTrue($this->_model->isGrouped());
        $this->assertTrue($this->_model->isSuperGroup());
        $this->assertTrue($this->_model->isSuper());
    }

    /**
     * @covers Mage_Catalog_Model_Product::isConfigurable
     * @covers Mage_Catalog_Model_Product::isSuperConfig
     * @covers Mage_Catalog_Model_Product::isSuper
     */
    public function testIsConfigurable()
    {
        $this->assertFalse($this->_model->isConfigurable());
        $this->assertFalse($this->_model->isSuperConfig());
        $this->assertFalse($this->_model->isSuper());
        $this->_model->setTypeId(Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE);
        $this->assertTrue($this->_model->isConfigurable());
        $this->assertTrue($this->_model->isSuperConfig());
        $this->assertTrue($this->_model->isSuper());
    }

    /**
     * @covers Mage_Catalog_Model_Product::getVisibleInCatalogStatuses
     * @covers Mage_Catalog_Model_Product::getVisibleStatuses
     * @covers Mage_Catalog_Model_Product::isVisibleInCatalog
     * @covers Mage_Catalog_Model_Product::getVisibleInSiteVisibilities
     * @covers Mage_Catalog_Model_Product::isVisibleInSiteVisibility
     */
    public function testVisibilityApi()
    {
        $this->assertEquals(
            array(Mage_Catalog_Model_Product_Status::STATUS_ENABLED), $this->_model->getVisibleInCatalogStatuses()
        );
        $this->assertEquals(
            array(Mage_Catalog_Model_Product_Status::STATUS_ENABLED), $this->_model->getVisibleStatuses()
        );

        $this->_model->setStatus(Mage_Catalog_Model_Product_Status::STATUS_DISABLED);
        $this->assertFalse($this->_model->isVisibleInCatalog());

        $this->_model->setStatus(Mage_Catalog_Model_Product_Status::STATUS_ENABLED);
        $this->assertTrue($this->_model->isVisibleInCatalog());

        $this->assertEquals(array(
                Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_SEARCH,
                Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG,
                Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH
            ), $this->_model->getVisibleInSiteVisibilities()
        );

        $this->assertFalse($this->_model->isVisibleInSiteVisibility());
        $this->_model->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_SEARCH);
        $this->assertTrue($this->_model->isVisibleInSiteVisibility());
        $this->_model->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG);
        $this->assertTrue($this->_model->isVisibleInSiteVisibility());
        $this->_model->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
        $this->assertTrue($this->_model->isVisibleInSiteVisibility());
    }

    /**
     * @covers Mage_Catalog_Model_Product::isDuplicable
     * @covers Mage_Catalog_Model_Product::setIsDuplicable
     */
    public function testIsDuplicable()
    {
        $this->assertTrue($this->_model->isDuplicable());
        $this->_model->setIsDuplicable(0);
        $this->assertFalse($this->_model->isDuplicable());
    }

    /**
     * @covers Mage_Catalog_Model_Product::isSalable
     * @covers Mage_Catalog_Model_Product::isSaleable
     * @covers Mage_Catalog_Model_Product::isAvailable
     * @covers Mage_Catalog_Model_Product::isInStock
     */
    public function testIsSalable()
    {
        $this->_model->load(1); // fixture
        $this->assertTrue((bool)$this->_model->isSalable());
        $this->assertTrue((bool)$this->_model->isSaleable());
        $this->assertTrue((bool)$this->_model->isAvailable());
        $this->assertTrue($this->_model->isInStock());
        $this->_model->setStatus(0);
        $this->assertFalse((bool)$this->_model->isSalable());
        $this->assertFalse((bool)$this->_model->isSaleable());
        $this->assertFalse((bool)$this->_model->isAvailable());
        $this->assertFalse($this->_model->isInStock());
    }

    /**
     * @covers Mage_Catalog_Model_Product::isVirtual
     * @covers Mage_Catalog_Model_Product::getIsVirtual
     */
    public function testIsVirtual()
    {
        $this->assertFalse($this->_model->isVirtual());
        $this->assertFalse($this->_model->getIsVirtual());

        $model = new Mage_Catalog_Model_Product(array('type_id' => Mage_Catalog_Model_Product_Type::TYPE_VIRTUAL));
        $this->assertTrue($model->isVirtual());
        $this->assertTrue($model->getIsVirtual());
    }

    public function testIsRecurring()
    {
        $this->assertFalse($this->_model->isRecurring());
        $this->_model->setIsRecurring(1);
        $this->assertTrue($this->_model->isRecurring());
    }

    public function testGetAttributeText()
    {
        $this->assertNull($this->_model->getAttributeText('status'));
        $this->_model->setStatus(Mage_Catalog_Model_Product_Status::STATUS_ENABLED);
        $this->assertEquals('Enabled', $this->_model->getAttributeText('status'));
    }

    public function testGetCustomDesignDate()
    {
        $this->assertEquals(array('from' => null, 'to' => null), $this->_model->getCustomDesignDate());
        $this->_model->setCustomDesignFrom(1)->setCustomDesignTo(2);
        $this->assertEquals(array('from' => 1, 'to' => 2), $this->_model->getCustomDesignDate());
    }

    public function testToArray()
    {
        $this->assertEquals(array(), $this->_model->toArray());
        $this->_model->setSku('sku')->setName('name');
        $this->assertEquals(array('sku' => 'sku', 'name' => 'name'), $this->_model->toArray());
    }

    public function testFromArray()
    {
        $this->_model->fromArray(array('sku' => 'sku', 'name' => 'name', 'stock_item' => array('key' => 'value')));
        $this->assertEquals(array('sku' => 'sku', 'name' => 'name'), $this->_model->getData());
    }

    public function testIsComposite()
    {
        $this->assertFalse($this->_model->isComposite());

        $model = new Mage_Catalog_Model_Product(array('type_id' => Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE));
        $this->assertTrue($model->isComposite());
    }

    /**
     * @see Mage_Catalog_Model_Product_Type_SimpleTest
     */
    public function testGetSku()
    {
        $this->assertEmpty($this->_model->getSku());
        $this->_model->setSku('sku');
        $this->assertEquals('sku', $this->_model->getSku());
    }

    public function testGetWeight()
    {
        $this->assertEmpty($this->_model->getWeight());
        $this->_model->setWeight(10.22);
        $this->assertEquals(10.22, $this->_model->getWeight());
    }

    public function testGetOptionInstance()
    {
        $model = $this->_model->getOptionInstance();
        $this->assertInstanceOf('Mage_Catalog_Model_Product_Option', $model);
        $this->assertSame($model, $this->_model->getOptionInstance());
    }

    public function testGetProductOptionsCollection()
    {
        $this->assertInstanceOf(
            'Mage_Catalog_Model_Resource_Product_Option_Collection', $this->_model->getProductOptionsCollection()
        );
    }

    public function testGetDefaultAttributeSetId()
    {
        $setId = $this->_model->getDefaultAttributeSetId();
        $this->assertNotEmpty($setId);
        $this->assertRegExp('/^[0-9]+$/', $setId);
    }

    public function testGetReservedAttributes()
    {
        $result = $this->_model->getReservedAttributes();
        $this->assertInternalType('array', $result);
        $this->assertContains('position', $result);
        $this->assertContains('reserved_attributes', $result);
        $this->assertContains('is_virtual', $result);
        // and 84 more...

        $this->assertNotContains('type_id', $result);
        $this->assertNotContains('calculated_final_price', $result);
        $this->assertNotContains('request_path', $result);
        $this->assertNotContains('rating_summary', $result);
    }

    /**
     * @param bool $isUserDefined
     * @param string $code
     * @param bool $expectedResult
     * @dataProvider isReservedAttributeDataProvider
     */
    public function testIsReservedAttribute($isUserDefined, $code, $expectedResult)
    {
        $attribute = new Varien_Object(array('is_user_defined' => $isUserDefined, 'code' => $code));
        $this->assertEquals($expectedResult, $this->_model->isReservedAttribute($attribute));
    }

    public function isReservedAttributeDataProvider()
    {
        array(
            array(true, 'position', true),
            array(true, 'type_id', false),
            array(false, 'no_difference', false),
        );
    }

    public function testSetOrigData()
    {
        $this->assertEmpty($this->_model->getOrigData());
        $this->_model->setOrigData('key', 'value');
        $this->assertEmpty($this->_model->getOrigData());

        $storeId = Mage::app()->getStore()->getId();
        Mage::app()->getStore()->setId(Mage_Core_Model_App::ADMIN_STORE_ID);
        try {
            $this->_model->setOrigData('key', 'value');
            $this->assertEquals('value', $this->_model->getOrigData('key'));
        } catch (Exception $e) {
            Mage::app()->getStore()->setId($storeId);
            throw $e;
        }
        Mage::app()->getStore()->setId($storeId);
    }

    public function testReset()
    {
        $model = $this->_model;

        $this->_assertEmpty($model);

        $this->_model->setData('key', 'value');
        $this->_model->reset();
        $this->_assertEmpty($model);

        $this->_model->setOrigData('key', 'value');
        $this->_model->reset();
        $this->_assertEmpty($model);

        $this->_model->addCustomOption('key', 'value');
        $this->_model->reset();
        $this->_assertEmpty($model);

        $this->_model->addOption(new Mage_Catalog_Model_Product_Option);
        $this->_model->reset();
        $this->_assertEmpty($model);

        $this->_model->canAffectOptions(true);
        $this->_model->reset();
        $this->_assertEmpty($model);
    }

    /**
     * Check is model empty or not
     *
     * @param Mage_Core_Model_Abstract $model
     */
    protected function _assertEmpty($model)
    {
        $this->assertEquals(array(), $model->getData());
        $this->assertEquals(null, $model->getOrigData());
        $this->assertEquals(array(), $model->getCustomOptions());
        // impossible to test $_optionInstance
        $this->assertEquals(array(), $model->getOptions());
        $this->assertFalse($model->canAffectOptions());
        // impossible to test $_errors
    }

    public function testGetCacheIdTags()
    {
        $this->assertFalse($this->_model->getCacheIdTags());

        $this->_model->load(1); // fixture
        $this->assertEquals(array('catalog_product_1'), $this->_model->getCacheIdTags());
    }

    /**
     * @magentoDataFixture Mage/Catalog/_files/two_products.php
     */
    public function testIsProductsHasSku()
    {
        $this->assertTrue($this->_model->isProductsHasSku(array(10, 11)));
    }

    public function testProcessBuyRequest()
    {
        $request = new Varien_Object;
        $result = $this->_model->processBuyRequest($request);
        $this->assertInstanceOf('Varien_Object', $result);
        $this->assertArrayHasKey('errors', $result->getData());
    }

    public function testGetPreconfiguredValues()
    {
        $this->assertInstanceOf('Varien_Object', $this->_model->getPreconfiguredValues());
        $this->_model->setPreconfiguredValues('test');
        $this->assertEquals('test', $this->_model->getPreconfiguredValues());
    }
}
