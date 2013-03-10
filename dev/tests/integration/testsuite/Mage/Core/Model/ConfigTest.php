<?php
/**
 * {license_notice}
 *
 * @category    Magento
 * @package     Mage_Core
 * @subpackage  integration_tests
 * @copyright   {copyright}
 * @license     {license_link}
 */

/**
 * First part of Mage_Core_Model_Config testing:
 * - general behaviour is tested
 *
 * @group module:Mage_Core
 * @see Mage_Core_Model_ConfigFactoryTest
 */
class Mage_Core_Model_ConfigTest extends PHPUnit_Framework_TestCase
{
    protected static $_options = array();

    public static function setUpBeforeClass()
    {
        self::$_options = Magento_Test_Bootstrap::getInstance()->getAppOptions();
    }

    public function testGetResourceModel()
    {
        $this->assertInstanceOf('Mage_Core_Model_Resource_Config', $this->_createModel(true)->getResourceModel());
    }

    public function testGetOptions()
    {
        $this->assertInstanceOf('Mage_Core_Model_Config_Options', $this->_createModel(true)->getOptions());
    }

    public function testSetOptions()
    {
        $model = $this->_createModel();
        $key = uniqid('key');
        $model->setOptions(array($key  => 'value'));
        $this->assertEquals('value', $model->getOptions()->getData($key));
    }

    public function testInit()
    {
        $model = $this->_createModel();
        $this->assertFalse($model->getNode());
        $model->init(self::$_options);
        $this->assertInstanceOf('Varien_Simplexml_Element', $model->getNode());
    }

    public function testLoadBase()
    {
        $model = $this->_createModel();
        $this->assertFalse($model->getNode());
        $model->setOptions(self::$_options);
        $model->loadBase();
        $this->assertInstanceOf('Varien_Simplexml_Element', $model->getNode('global'));
    }

    public function testLoadModulesCache()
    {
        $model = $this->_createModel();
        $this->assertTrue($model->loadModulesCache());
        $this->assertInstanceOf('Mage_Core_Model_Config_Element', $model->getNode());
    }

    public function testLoadModules()
    {
        $model = $this->_createModel();
        $model->setOptions(self::$_options);
        $model->loadBase();
        $this->assertFalse($model->getNode('modules'));
        $model->loadModules();
        $this->assertInstanceOf('Mage_Core_Model_Config_Element', $model->getNode('modules'));
    }

    public function testIsLocalConfigLoaded()
    {
        $model = $this->_createModel();
        $this->assertFalse($model->isLocalConfigLoaded());
        $model->setOptions(self::$_options);
        $model->loadBase();
        $this->assertTrue($model->isLocalConfigLoaded());
    }

    public function testLoadDb()
    {
        $samplePath = 'general/locale/firstday';

        // emulate a system config value in database
        $configResource = new Mage_Core_Model_Resource_Config;
        $configResource->saveConfig($samplePath, 1, 'default', 0);

        try {
            $model = $this->_createModel();
            $model->setOptions(self::$_options);
            $model->loadBase();
            $model->loadModules();

            // load and assert value
            $model->loadDb();
            $this->assertEquals('1', (string)$model->getNode("default/{$samplePath}"));
            $configResource->deleteConfig($samplePath, 'default', 0);
        } catch (Exception $e) {
            $configResource->deleteConfig($samplePath, 'default', 0);
            throw $e;
        }
    }

    public function testGetCache()
    {
        $this->assertInstanceOf('Varien_Cache_Core', $this->_createModel()->getCache());
    }

    public function testSaveCache()
    {
        $model = $this->_createModel(true);
        $model->removeCache();
        $this->assertFalse($model->loadCache());

        $model->saveCache(array(Mage_Core_Model_Cache::OPTIONS_CACHE_ID));
        $this->assertTrue($model->loadCache());
        $this->assertInstanceOf('Mage_Core_Model_Config_Element', $model->getNode());
    }

    public function testRemoveCache()
    {
        $model = $this->_createModel();
        $model->removeCache();
        $this->assertFalse($model->loadCache());
    }

    public function testGetSectionNode()
    {
        $this->assertInstanceOf(
            'Mage_Core_Model_Config_Element', $this->_createModel(true)->getSectionNode(array('admin'))
        );
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @magentoDataFixture Mage/Core/_files/config.php
     */
    public function testGetNode()
    {
        $model = $this->_createModel();
        $this->assertFalse($model->getNode());
        $model->init(self::$_options);
        $this->assertInstanceOf('Mage_Core_Model_Config_Element', $model->getNode());
        $this->assertInstanceOf('Mage_Core_Model_Config_Element', $model->getNode(null, 'store', 1));
        $this->assertInstanceOf('Mage_Core_Model_Config_Element', $model->getNode(null, 'website', 1));

        foreach ($this->configDataProvider() as $sample) {

            list($path, $storeCode, $expectedValue) = $sample;
            $store = Mage::getModel('core/store')->load($storeCode);
            $this->assertInstanceOf('Mage_Core_Model_Store', $store);
            $actualValue = $model->getNode($path, 'stores', $storeCode);
            $this->assertEquals($expectedValue, $actualValue, "Store: $storeCode, Path: $path");
        }
    }

    public function configDataProvider()
    {
        return array(
            array('aaa/bbb/ccc', 'store_0_0_1', '4'), // <path>, <storeCode>, <expectedValue>
            array('aaa/bbb/ccc', 'store_0_1_0', '3'),
            array('aaa/bbb/ccc', 'store_0_1_1', '3'),
            array('aaa/bbb/ccc', 'store_0_0_0', '3'),

            array('aaa/bbb/ccc', 'store_1_0_1', '5'),
            array('aaa/bbb/ccc', 'store_1_1_0', '5'),
            array('aaa/bbb/ccc', 'store_1_1_1', '5'),
            array('aaa/bbb/ccc', 'store_1_0_0', '5'),
        );
    }

    public function testSetNode()
    {
        $model = $this->_createModel();
        $model->init(self::$_options);
        /* some existing node should be used */
        $model->setNode('admin/routers/adminhtml/use', 'test');
        $this->assertEquals('test', (string) $model->getNode('admin/routers/adminhtml/use'));
    }

    public function testDetermineOmittedNamespace()
    {
        $model = $this->_createModel(true);
        $this->assertEquals('cms', $model->determineOmittedNamespace('cms'));
        $this->assertEquals('Mage_Cms', $model->determineOmittedNamespace('cms', true));
        $this->assertEquals('', $model->determineOmittedNamespace('nonexistent'));
        $this->assertEquals('', $model->determineOmittedNamespace('nonexistent', true));
    }

    public function testLoadModulesConfiguration()
    {
        $config = $this->_createModel(true)->loadModulesConfiguration('adminhtml.xml');
        $this->assertInstanceOf('Mage_Core_Model_Config_Base', $config);
        $this->assertInstanceOf('Mage_Core_Model_Config_Element', $config->getNode('menu'));
        $this->assertInstanceOf('Mage_Core_Model_Config_Element', $config->getNode('acl'));
    }

    public function testGetTempVarDir()
    {
        $this->assertTrue(is_dir($this->_createModel()->getTempVarDir()));
    }

    public function testGetDistroServerVars()
    {
        $_SERVER['SCRIPT_NAME'] = __FILE__;
        $_SERVER['HTTP_HOST'] = 'example.com';
        $vars = $this->_createModel()->getDistroServerVars();
        $this->assertArrayHasKey('root_dir', $vars);
        $this->assertArrayHasKey('app_dir', $vars);
        $this->assertArrayHasKey('var_dir', $vars);
        $this->assertArrayHasKey('base_url', $vars);
        $this->assertEquals('http://example.com/', $vars['base_url']);
    }

    public function testSubstDistroServerVars()
    {
        $this->assertEquals('http://localhost/', $this->_createModel()->substDistroServerVars('{{base_url}}'));
    }

    public function testGetModuleConfig()
    {
        $model = $this->_createModel(true);
        $this->assertInstanceOf('Mage_Core_Model_Config_Element', $model->getModuleConfig());
        $this->assertInstanceOf('Mage_Core_Model_Config_Element', $model->getModuleConfig('Mage_Core'));
    }

    public function testGetVarDir()
    {
        $dir = $this->_createModel()->getVarDir();
        $this->assertTrue(is_dir($dir));
        $this->assertTrue(is_writable($dir));
    }

    public function testCreateDirIfNotExists()
    {
        $model = $this->_createModel();
        $dir = $model->getVarDir() . DIRECTORY_SEPARATOR . uniqid('dir');
        try {
            $this->assertFalse(is_dir($dir));
            $this->assertTrue($model->createDirIfNotExists($dir));
            rmdir($dir);
        } catch (Exception $e) {
            rmdir($dir);
            throw $e;
        }
    }

    public function testGetModuleDir()
    {
        $model = $this->_createModel(true);
        foreach (array('etc', 'controllers', 'sql', 'data', 'locale') as $type) {
            $dir = $model->getModuleDir($type, 'Mage_Core');
            $this->assertStringEndsWith($type, $dir);
            $this->assertContains('Mage' . DIRECTORY_SEPARATOR . 'Core', $dir);
        }
        $this->assertTrue(is_dir($this->_createModel(true)->getModuleDir('etc', 'Mage_Core')));
    }

    public function testLoadEventObservers()
    {
        $this->_createModel(true)->loadEventObservers('global');
        $this->assertArrayHasKey('log_log_clean_after', Mage::getEvents()->getAllEvents());
    }

    public function testGetPathVars()
    {
        $result = $this->_createModel()->getPathVars();
        $this->assertArrayHasKey('baseUrl', $result);
        $this->assertArrayHasKey('baseSecureUrl', $result);
    }

    public function testGetResourceConfig()
    {
        $this->assertInstanceOf(
            'Mage_Core_Model_Config_Element', $this->_createModel(true)->getResourceConfig('cms_setup')
        );
    }

    public function testGetResourceConnectionConfig()
    {
        $this->assertInstanceOf(
            'Mage_Core_Model_Config_Element', $this->_createModel(true)->getResourceConnectionConfig('core_read')
        );
    }

    public function testGetResourceTypeConfig()
    {
        $this->assertInstanceOf(
            'Mage_Core_Model_Config_Element', $this->_createModel(true)->getResourceTypeConfig('pdo_mysql')
        );
    }

    public function testGetStoresConfigByPath()
    {
        $model = $this->_createModel(true);

        // default
        $baseUrl = $model->getStoresConfigByPath('web/unsecure/base_url');
        $this->assertArrayHasKey(0, $baseUrl);
        $this->assertArrayHasKey(1, $baseUrl);

        // $allowValues
        $baseUrl = $model->getStoresConfigByPath('web/unsecure/base_url', array(uniqid()));
        $this->assertEquals(array(), $baseUrl);

        // store code
        $baseUrl = $model->getStoresConfigByPath('web/unsecure/base_url', array(), 'code');
        $this->assertArrayHasKey('default', $baseUrl);
        $this->assertArrayHasKey('admin', $baseUrl);

        // store name
        $baseUrl = $model->getStoresConfigByPath('web/unsecure/base_url', array(), 'name');
        $this->assertArrayHasKey('Default Store View', $baseUrl);
        $this->assertArrayHasKey('Admin', $baseUrl);
    }

    /**
     * Test shouldUrlBeSecure() function for "Use Secure URLs in Frontend" = Yes
     *
     * @magentoConfigFixture current_store web/secure/use_in_frontend 1
     */
    public function testShouldUrlBeSecureWhenSecureUsedInFrontend()
    {
        $model = $this->_createModel(true);
        $this->assertFalse($model->shouldUrlBeSecure('/'));
        $this->assertTrue($model->shouldUrlBeSecure('/checkout/onepage'));
    }

    /**
     * Test shouldUrlBeSecure() function for "Use Secure URLs in Frontend" = No
     *
     * @magentoConfigFixture current_store web/secure/use_in_frontend 0
     */
    public function testShouldUrlBeSecureWhenSecureNotUsedInFrontend()
    {
        $model = $this->_createModel(true);
        $this->assertFalse($model->shouldUrlBeSecure('/'));
        $this->assertFalse($model->shouldUrlBeSecure('/checkout/onepage'));
    }

    public function testGetTablePrefix()
    {
        $this->assertEquals('', (string)$this->_createModel(true)->getTablePrefix());
    }

    public function testGetEventConfig()
    {
        $this->assertInstanceOf('Mage_Core_Model_Config_Element',
            $this->_createModel(true)->getEventConfig('global', 'controller_front_init_routers')
        );
    }

    public function testSaveDeleteConfig()
    {
        $model = $this->_createModel(true);
        $model->saveConfig('web/url/redirect_to_base', 0);
        try {
            $model->reinit();
            $this->assertEquals('0', (string)$model->getNode('default/web/url/redirect_to_base'));

            $model->deleteConfig('web/url/redirect_to_base');
            $model->reinit();
            $this->assertEquals('1', (string)$model->getNode('default/web/url/redirect_to_base'));
        } catch (Exception $e) {
            $model->deleteConfig('web/url/redirect_to_base');
            throw $e;
        }
    }

    public function testGetFieldset()
    {
        $this->markTestSkipped('Should be fixed for current version');
        $fieldset = $this->_createModel(true)->getFieldset('customer_account');
        $this->assertArrayHasKey('prefix', $fieldset);
        $this->assertArrayHasKey('firstname', $fieldset);
        $this->assertArrayHasKey('middlename', $fieldset);
        $this->assertArrayHasKey('lastname', $fieldset);
        $this->assertArrayHasKey('suffix', $fieldset);
        $this->assertArrayHasKey('email', $fieldset);
        $this->assertArrayHasKey('password', $fieldset);
    }

    /**
     * Instantiate Mage_Core_Model_Config and initialize (load configuration) if needed
     *
     * @param bool $initialize
     * @return Mage_Core_Model_Config
     */
    protected function _createModel($initialize = false)
    {
        $model = new Mage_Core_Model_Config;
        if ($initialize) {
            $model->init(self::$_options);
        }
        return $model;
    }
}
