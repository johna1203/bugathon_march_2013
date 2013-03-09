<?php
/**
 * {license_notice}
 *
 * @category    Magento
 * @package     Magento
 * @subpackage  unit_tests
 * @copyright   {copyright}
 * @license     {license_link}
 */

class Magento_Test_Db_MysqlTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    protected $_varDir;

    /**
     * @var Magento_Test_Db_Mysql
     */
    protected $_model;

    /**
     * @var string
     */
    protected $_commandPrefix;

    protected function setUp()
    {
        $this->_varDir  = $this->_varDir  = sys_get_temp_dir();
        $this->_model = $this->getMock(
            'Magento_Test_Db_Mysql',
            array('_exec', '_createScript'),
            array('host', 'user', 'pass', 'schema', $this->_varDir)
        );
        $this->_commandPrefix = '--protocol=TCP --host=' . escapeshellarg('host')
            . ' --user=' . escapeshellarg('user') . ' --password=' . escapeshellarg('pass');
    }

    public function testCleanup()
    {
        $this->_model->expects($this->once())
            ->method('_createScript')
            ->with(
                $this->_varDir . DIRECTORY_SEPARATOR . 'drop_create_database.sql',
                'DROP DATABASE `schema`; CREATE DATABASE `schema`'
            );

        $command = 'mysql ' . $this->_commandPrefix . ' ' . escapeshellarg('schema') . ' < '
            . escapeshellarg($this->_varDir . DIRECTORY_SEPARATOR . 'drop_create_database.sql');
        $this->_model->expects($this->once())
            ->method('_exec')
            ->with($this->equalTo($command));
        $this->_model->cleanup();
    }

    public function testCreateBackup()
    {
        $command = 'mysqldump ' . $this->_commandPrefix . ' --skip-opt --quick --single-transaction --create-options'
            . ' --disable-keys --set-charset --extended-insert --hex-blob --insert-ignore --add-drop-table '
            . escapeshellarg('schema') . ' > ' . escapeshellarg($this->_varDir . DIRECTORY_SEPARATOR . 'test.sql');
        $this->_model->expects($this->once())
            ->method('_exec')
            ->with($this->equalTo($command));

        $this->_model->createBackup('test');
    }

    public function testRestoreBackup()
    {
        $command = 'mysql ' . $this->_commandPrefix . ' ' . escapeshellarg('schema') . ' < '
            . escapeshellarg($this->_varDir . DIRECTORY_SEPARATOR . 'test.sql');
        $this->_model->expects($this->once())
            ->method('_exec')
            ->with($this->equalTo($command));

        $this->_model->restoreBackup('test');
    }
}
