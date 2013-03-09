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
class Mage_Core_Model_ResourceMysqlTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Core_Model_Resource
     */
    protected $_model;

    public function setUp()
    {
        if (Magento_Test_Bootstrap::getInstance()->getDbVendorName() != 'mysql') {
            $this->markTestSkipped('Test is designed to run on MySQL only.');
        }
        $this->_model = new Mage_Core_Model_Resource();
    }

    public function testGetConnectionTypeInstance()
    {
        $this->assertInstanceOf(
            'Mage_Core_Model_Resource_Type_Db_Pdo_Mysql',
            $this->_model->getConnectionTypeInstance('pdo_mysql')
        );
    }

    public function testResourceTypeDb()
    {
        $resource = $this->_model->getConnectionTypeInstance('pdo_mysql');
        $this->assertEquals('Mage_Core_Model_Resource_Entity_Table', $resource->getEntityClass(), 'Entity class');

        $resource->setName('test');
        $this->assertEquals('test', $resource->getName(), 'Set/Get name');

        $this->assertInstanceOf(
            'Zend_Db_Adapter_Abstract',
            $resource->getConnection(Mage::getConfig()->getNode('global/resources/default_setup/connection')->asArray())
        );

    }

    public function testCreateConnection()
    {
        $this->assertFalse($this->_model->createConnection('test_false', 'test', 'test'));
        $this->assertInstanceOf(
            'Varien_Db_Adapter_Pdo_Mysql',
            $this->_model->createConnection(
                'test',
                'pdo_mysql',
                Mage::getConfig()->getNode('global/resources/default_setup/connection')->asArray()
            )
        );

    }

    public function testGetIdxName()
    {
        $this->assertEquals('IDX_CORE_STORE_STORE_ID', $this->_model->getIdxName('core_store', array('store_id')));
    }

    public function testGetFkName()
    {
        $this->assertStringStartsWith(
            'FK_',
            $this->_model->getFkName('sales_flat_creditmemo_comment', 'parent_id', 'sales_flat_creditmemo', 'entity_id')
        );
    }
}
