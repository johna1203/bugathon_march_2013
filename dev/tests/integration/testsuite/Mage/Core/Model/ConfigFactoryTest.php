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
 * Second part of Mage_Core_Model_Config testing:
 * - Mage factory behaviour is tested
 *
 * @group module:Mage_Core
 * @see Mage_Core_Model_ConfigTest
 */
class Mage_Core_Model_ConfigFactoryTest extends PHPUnit_Framework_TestCase
{
    protected static $_options = array();

    public static function setUpBeforeClass()
    {
        self::$_options = Magento_Test_Bootstrap::getInstance()->getAppOptions();
    }

    public function testGetGroupedClassName()
    {
        $model = $this->_createModel(true);
        $this->assertEquals('Mage_Core_Model_Config', $model->getGroupedClassName('model', 'core/config'));
        $this->assertEquals('Mage_Core_Block_Config', $model->getGroupedClassName('block', 'core/config'));
        $this->assertEquals('Mage_Core_Helper_String', $model->getGroupedClassName('helper', 'core/string'));
    }

    public function testGetBlockClassName()
    {
        $this->assertEquals('Mage_Core_Block_Config', $this->_createModel(true)->getBlockClassName('core/config'));
    }

    public function testGetHelperClassName()
    {
        $model = $this->_createModel(true);
        $this->assertEquals('Mage_Core_Helper_Data', $model->getHelperClassName('core'));
        $this->assertEquals('Mage_Core_Helper_String', $model->getHelperClassName('core/string'));
    }

    public function testGetResourceHelper()
    {
        $model = $this->_createModel(true);
        $this->assertInstanceOf('Mage_Core_Model_Resource_Helper_Abstract', $model->getResourceHelper('core'));
        $this->assertInstanceOf('Mage_Core_Model_Resource_Helper_Abstract', $model->getResourceHelper('catalog'));
    }

    public function testGetModelClassName()
    {
        $this->assertEquals('Mage_Core_Model_Config', $this->_createModel(true)->getModelClassName('core/config'));
    }

    public function testGetModelInstance()
    {
        $this->assertInstanceOf('Mage_Core_Model_Config', $this->_createModel(true)->getModelInstance('core/config'));
    }

    public function testGetNodeClassInstance()
    {
        $this->assertInstanceOf('Mage_Core_Model_Variable_Observer', $this->_createModel(true)->getNodeClassInstance(
            'adminhtml/events/cms_wysiwyg_config_prepare/observers/variable_observer'
        ));
    }

    public function testGetResourceModelClassName()
    {
        $this->assertEquals('Mage_Core_Model_Resource_Config',
            $this->_createModel(true)->getResourceModelClassName('core/config')
        );
    }

    public function testGetResourceModelInstance()
    {
        $this->assertInstanceOf(
            'Mage_Core_Model_Resource_Abstract', $this->_createModel(true)->getResourceModelInstance('core/config')
        );
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
