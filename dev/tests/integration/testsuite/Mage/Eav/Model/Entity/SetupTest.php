<?php

class Mage_Eav_Model_Entity_SetupTest extends PHPUnit_Framework_TestCase
{
    /** @var Mage_Eav_Model_Entity_Setup */
    protected $_installer;

    protected $_origDeveloperMode;

    /**
     * @var array
     */
    protected static $_fixture = array(
        'classGroup' => 'phpunit_test_createEntityTables',
        'tableName' => 'phpunit_test_create_entity_tables_tmp',
        'entityName' => 'test',
    );

    /**
     * Instantiate eav setup instance and the config fixtures and
     * drop test tables if they exist
     *
     * Unable to use @magentoConfigFixture here because the config
     * path isn't hardcoded

     */
    protected function setUp()
    {
        // Set up config fixtures
        $config = Mage::getConfig();
        $config->setNode(
            "global/models/" . self::$_fixture['classGroup'] . "/resourceModel",
                self::$_fixture['classGroup'] . "_resource"
        );
        $config->setNode(
            "global/models/" . self::$_fixture['classGroup'] . "_resource/entities/" . self::$_fixture['entityName'] . "/table",
            self::$_fixture['tableName']
        );

        $this->dropTestTables();

        $this->_installer = Mage::getModel('eav/entity_setup', 'default_setup');
        if (!$this->_origDeveloperMode = Mage::getIsDeveloperMode()) {
            Mage::setIsDeveloperMode(true);
        }
    }

    /**
     * Clean up the tables and reset developer mode setting
     */
    protected function tearDown()
    {
        $this->dropTestTables();
        if (!$this->_origDeveloperMode) {
            Mage::setIsDeveloperMode(false);
        }
    }

    /**
     * Return table alias for test table
     *
     * @return string
     */
    protected function getTableAlias()
    {
        return $tableAlias = self::$_fixture['classGroup'] . '/' . self::$_fixture['entityName'];
    }

    /**
     * Drop test tables
     */
    protected function dropTestTables()
    {
        // Clean up any tables that might be left over from previous tests

        /* @var $resource Mage_Core_Model_Resource */
        $resource = Mage::getSingleton('core/resource');
        /** @var $adapter Varien_Db_Adapter_Interface */
        $adapter = $resource->getConnection('default_read');

        // First drop the value tables because of the FK constraints to the entity table
        foreach (array('datetime', 'decimal', 'int', 'text', 'varchar', 'char') as $type) {
            $valueTableName = $resource->getTableName(array($this->getTableAlias(), $type));
            if ($adapter->isTableExists($valueTableName)) {
                $adapter->dropTable($valueTableName);
            }
        }

        // Finally drop the entity table
        $entityTable = $resource->getTableName($this->getTableAlias());
        if ($adapter->isTableExists($entityTable)) {
            $adapter->dropTable($entityTable);
        }
    }

    /**
     * Check the config fixtures work as expected
     */
    public function assertPreConditions()
    {
        $result = Mage::getSingleton('core/resource')->getTableName($this->getTableAlias());
        $prefix = Mage::getConfig()->getTablePrefix();
        $this->assertEquals($prefix . self::$_fixture['tableName'], $result);
    }

    /**
     * Test Mage_Eav_Model_Entity_Setup::createEntityTables()
     *
     * @test
     */
    public function createEntityTables()
    {
        /* @var $resource Mage_Core_Model_Resource */
        $resource = Mage::getSingleton('core/resource');
        /** @var $adapter Varien_Db_Adapter_Interface */
        $adapter = $resource->getConnection('default_read');

        $this->_installer->createEntityTables($this->getTableAlias());

        $this->assertTrue(
            $adapter->isTableExists($resource->getTableName($this->getTableAlias())),
            "The base table {$this->getTableAlias()} was not created."
        );

        foreach (array('datetime', 'decimal', 'int', 'text', 'varchar', 'char') as $type) {
            $valueTableName = $resource->getTableName(array($this->getTableAlias(), $type));
            $this->assertTrue(
                $adapter->isTableExists($valueTableName),
                "The value table {$valueTableName} was not created."
            );
        }
    }
}
