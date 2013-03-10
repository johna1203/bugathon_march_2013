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
 * @group module:Mage_Core
 */
class Mage_Core_Model_Config_OptionsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Core_Model_Config_Options
     */
    protected $_model;

    protected static $_keys = array(
        'app_dir'     => 'app',
        'base_dir'    => 'base',
        'code_dir'    => 'code',
        'design_dir'  => 'design',
        'etc_dir'     => 'etc',
        'lib_dir'     => 'lib',
        'locale_dir'  => 'locale',
        'media_dir'   => 'media',
        'skin_dir'    => 'skin',
        'var_dir'     => 'var',
        'tmp_dir'     => 'tmp',
        'cache_dir'   => 'cache',
        'log_dir'     => 'log',
        'session_dir' => 'session',
        'upload_dir'  => 'upload',
        'export_dir'  => 'export',
    );

    protected function setUp()
    {
        $this->_model = new Mage_Core_Model_Config_Options;
    }

    public function testConstruct()
    {
        $data = $this->_model->getData();
        foreach (array_keys(self::$_keys) as $key) {
            $this->assertArrayHasKey($key, $data);
            unset($data[$key]);
        }
        $this->assertEmpty($data);
    }

    public function testGetDir()
    {
        foreach (self::$_keys as $full => $partial) {
            $this->assertEquals($this->_model->getData($full), $this->_model->getDir($partial));
        }
    }

    /**
     * @expectedException Exception
     */
    public function testGetDirException()
    {
        $this->_model->getDir('invalid');
    }

    /**
     * @covers Mage_Core_Model_Config_Options::getAppDir
     * @covers Mage_Core_Model_Config_Options::getBaseDir
     * @covers Mage_Core_Model_Config_Options::getCodeDir
     * @covers Mage_Core_Model_Config_Options::getDesignDir
     * @covers Mage_Core_Model_Config_Options::getEtcDir
     * @covers Mage_Core_Model_Config_Options::getLibDir
     * @covers Mage_Core_Model_Config_Options::getLocaleDir
     * @covers Mage_Core_Model_Config_Options::getMediaDir
     * @covers Mage_Core_Model_Config_Options::getSkinDir
     * @covers Mage_Core_Model_Config_Options::getSysTmpDir
     * @covers Mage_Core_Model_Config_Options::getVarDir
     * @covers Mage_Core_Model_Config_Options::getTmpDir
     * @covers Mage_Core_Model_Config_Options::getCacheDir
     * @covers Mage_Core_Model_Config_Options::getLogDir
     * @covers Mage_Core_Model_Config_Options::getSessionDir
     * @covers Mage_Core_Model_Config_Options::getUploadDir
     * @covers Mage_Core_Model_Config_Options::getExportDir
     */
    public function testGetters()
    {
        $this->assertTrue(is_dir($this->_model->getAppDir()));
        $this->assertTrue(is_dir($this->_model->getBaseDir()));
        $this->assertTrue(is_dir($this->_model->getCodeDir()));
        $this->assertTrue(is_dir($this->_model->getDesignDir()));
        $this->assertTrue(is_dir($this->_model->getEtcDir()));
        $this->assertTrue(is_dir($this->_model->getLibDir()));
        $this->assertTrue(is_dir($this->_model->getLocaleDir()));
        $this->assertTrue(is_dir($this->_model->getMediaDir()));
        $this->assertTrue(is_dir($this->_model->getSkinDir()));
        $this->assertTrue(is_dir($this->_model->getSysTmpDir()));
        $this->assertTrue(is_dir($this->_model->getVarDir()));
        $this->assertTrue(is_dir($this->_model->getTmpDir()));
        $this->assertTrue(is_dir($this->_model->getCacheDir()));
        $this->assertTrue(is_dir($this->_model->getLogDir()));
        $this->assertTrue(is_dir($this->_model->getSessionDir()));
        $this->assertTrue(is_dir($this->_model->getUploadDir()));
        $this->assertTrue(is_dir($this->_model->getExportDir()));
    }

    public function testCreateDirIfNotExists()
    {
        $var = $this->_model->getVarDir();

        $sampleDir = uniqid($var);
        $this->assertTrue($this->_model->createDirIfNotExists($sampleDir));
        $this->assertTrue($this->_model->createDirIfNotExists($sampleDir));
        rmdir($sampleDir);

        $sampleFile = "{$var}/" . uniqid('file') . '.txt';
        file_put_contents($sampleFile, '1');
        $this->assertFalse($this->_model->createDirIfNotExists($sampleFile));
        unlink($sampleFile);
    }
}
