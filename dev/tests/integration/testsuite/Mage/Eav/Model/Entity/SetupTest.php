<?php

class Mage_Eav_Model_Entity_SetupTest extends PHPUnit_Framework_TestCase
{
    /** @var Mage_Eav_Model_Entity_Setup */
    protected static $_installer;

    protected $_origDeveloperMode;

    /**
     * @var array
     */
    protected static $_config = array(
        'classGroup' => 'phpunit_test_createEntityTables',
        'tableName' => 'phpunit_test_create_entity_tables_tmp',
        'entityName' => 'test',
    );

    /**
     * Setup config fixtures and drop test tables if they exist
     */
    public static function setUpBeforeClass()
    {
        // Set up config fixtures
        $config = Mage::getConfig();
        $config->setNode(
            "global/models/" . self::$_config['classGroup'] . "/resourceModel",
                self::$_config['classGroup'] . "_resource"
        );
        $config->setNode(
            "global/models/" . self::$_config['classGroup'] . "_resource/entities/" . self::$_config['entityName'] . "/table",
            self::$_config['tableName']
        );

        self::dropTestTables();
    }

    /**
     * Clean up test environment
     *
     * - Drop test tables
     * - Set previous include path
     */
    public static function tearDownAfterClass()
    {
        self::dropTestTables();
    }

    /**
     * Instantiate eav setup instance
     */
    protected function setUp()
    {
        self::$_installer = Mage::getModel('eav/entity_setup', 'default_setup');
        if (! $this->_origDeveloperMode = Mage::getIsDeveloperMode()) {
            Mage::setIsDeveloperMode(true);
        }
    }

    protected function tearDown()
    {
        if (! $this->_origDeveloperMode) {
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
        return $tableAlias = self::$_config['classGroup'] . '/' . self::$_config['entityName'];
    }

    /**
     * Drop test tables
     */
    protected static function dropTestTables()
    {
        // Clean up any tables that might be left over from previous tests
        // First drop the value tables because of the FK constraints to the entity table
        /** @var $con Varien_Db_Adapter_Pdo_Mysql */
        $con = Mage::getSingleton('core/resource')->getConnection('eav_write');
        $sql = "SHOW TABLES LIKE '" . self::$_config['tableName'] . "_%'";
        foreach ($con->fetchCol($sql) as $table) {
            $con->dropTable($table);
        }
        // Finally drop the entity table
        $sql = "SHOW TABLES LIKE '" . self::$_config['tableName'] . "'";
        foreach ($con->fetchCol($sql) as $table) {
            $con->dropTable($table);
        }
    }

    /**
     * Check the config fixtures work as expected
     */
    public function assertPreConditions()
    {
        $result = Mage::getSingleton('core/resource')->getTableName($this->getTableAlias());
        $this->assertEquals(self::$_config['tableName'], $result);
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

        self::$_installer->createEntityTables($this->getTableAlias());

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
