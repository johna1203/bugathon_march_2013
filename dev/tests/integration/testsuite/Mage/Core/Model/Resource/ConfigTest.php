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
class Mage_Core_Model_Resource_ConfigTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Core_Model_Resource_Config
     */
    protected $_model;

    public function setUp()
    {
        $this->_model = new Mage_Core_Model_Resource_Config();
    }

    public function testSaveDeleteConfig()
    {
        $connection = $this->_model->getReadConnection();
        $select = $connection->select()
            ->from($this->_model->getMainTable())
            ->where('path=?', 'test/config');
        $this->_model->saveConfig('test/config', 'test', 'default', 0);
        $this->assertNotEmpty($connection->fetchRow($select));

        $this->_model->deleteConfig('test/config', 'default', 0);
        $this->assertEmpty($connection->fetchRow($select));
    }
}
