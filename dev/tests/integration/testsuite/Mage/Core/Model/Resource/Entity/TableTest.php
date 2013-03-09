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
class Mage_Core_Model_Resource_Entity_TableTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Core_Model_Resource_Entity_Table
     */
    protected $_model;

    protected function setUp()
    {
        // @codingStandardsIgnoreStart
        $config = new Varien_Simplexml_Config();
        $config->table      = 'test_table';
        $config->test_key   = 'test';
        // @codingStandardsIgnoreEnd

        $this->_model = new Mage_Core_Model_Resource_Entity_Table($config);
    }

    public function testGetTable()
    {
        $this->assertEquals('test_table', $this->_model->getTable());
    }

    public function testGetConfig()
    {
        $this->assertInstanceOf('Varien_Simplexml_Config', $this->_model->getConfig());
        $this->assertEquals('test', $this->_model->getConfig('test_key'));
        $this->assertFalse($this->_model->getConfig('some_key'));
    }
}
