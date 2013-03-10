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
class Mage_Core_Model_Resource_SetupTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Core_Model_Resource_Setup
     */
    protected $_model;

    public function setUp()
    {
        $this->_model = new Mage_Core_Model_Resource_Setup('default_setup');
    }

    public function testSetTable()
    {
        $this->_model->setTable('test_name', 'test_real_name');
        $this->assertEquals('test_real_name', $this->_model->getTable('test_name'));
    }

    /**
     * @covers Mage_Core_Model_Resource_Setup::applyAllUpdates
     * @covers Mage_Core_Model_Resource_Setup::applyAllDataUpdates
     */
    public function testApplyAllDataUpdates()
    {
        /*reset versions*/
        Mage::getResourceModel('core/resource')->setDbVersion('adminnotification_setup', false);
        Mage::getResourceModel('core/resource')->setDataVersion('adminnotification_setup', false);
        $this->_model->deleteTableRow('core_resource', 'code', 'adminnotification_setup');
        $this->_model->getConnection()->dropTable('adminnotification_inbox');
        try {
            $this->_model->applyAllUpdates();
            $this->_model->applyAllDataUpdates();
        } catch (Exception $e) {
            $this->fail("Impossible to continue other tests, because database is broken: {$e}");
        }
        $this->assertNotEmpty(
            $this->_model->getTableRow('core_resource', 'code', 'adminnotification_setup', 'version')
        );
        $this->assertNotEmpty(
            $this->_model->getTableRow('core_resource', 'code', 'adminnotification_setup', 'data_version')
        );
    }

    public function testUpdateTableRow()
    {
        $original = $this->_model->getTableRow('core_resource', 'code', 'adminnotification_setup', 'version');
        $this->_model->updateTableRow('core_resource', 'code', 'adminnotification_setup', 'version', 'test');
        $this->assertEquals(
            'test',
            $this->_model->getTableRow('core_resource', 'code', 'adminnotification_setup', 'version')
        );
        $this->_model->updateTableRow('core_resource', 'code', 'adminnotification_setup', 'version', $original);
    }

    public function testSetDeleteConfigData()
    {
        $select = $this->_model->getConnection()->select()
            ->from('core_config_data', 'value')
            ->where('path=?', 'my/test/path');

        $this->_model->setConfigData('my/test/path', 'test_value');
        $this->assertEquals('test_value', $this->_model->getConnection()->fetchOne($select));

        $this->_model->deleteConfigData('my/test/path', 'test');
        $this->assertNotEmpty($this->_model->getConnection()->fetchRow($select));

        $this->_model->deleteConfigData('my/test/path');
        $this->assertEmpty($this->_model->getConnection()->fetchRow($select));
    }
}
