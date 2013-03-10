<?php
/**
 * {license_notice}
 *
 * @category    Magento
 * @package     Magento
 * @subpackage  integration_tests
 * @copyright   {copyright}
 * @license     {license_link}
 */

/**
 * MySQL platform database handler
 */
class Magento_Test_Db_Mysql extends Magento_Test_Db_DbAbstract
{
    /**
     * Remove all DB objects
     *
     * @return bool
     */
    public function cleanup()
    {
        $script = $this->_varPath . DIRECTORY_SEPARATOR . 'drop_create_database.sql';
        $this->_createScript($script, "DROP DATABASE `{$this->_schema}`; CREATE DATABASE `{$this->_schema}`");
        $cmd = sprintf('mysql --protocol=TCP --host=%s --user=%s --password=%s %s < %s',
            escapeshellarg($this->_host), escapeshellarg($this->_user),
            escapeshellarg($this->_password), escapeshellarg($this->_schema), escapeshellarg($script)
        );
        return $this->_exec($cmd);
    }

    /**
     * Create database backup
     *
     * @param string $name
     * @return bool
     */
    public function createBackup($name)
    {
        $cmd = sprintf('mysqldump --protocol=TCP --host=%s --user=%s --password=%s'
            // DDL
            . ' --skip-opt --quick --single-transaction --create-options --disable-keys --set-charset'
            . ' --extended-insert --hex-blob --insert-ignore --add-drop-table'
            // DB and files
            . ' %s > %s',
            escapeshellarg($this->_host),
            escapeshellarg($this->_user),
            escapeshellarg($this->_password),
            escapeshellarg($this->_schema),
            escapeshellarg($this->_getBackupFile($name))
        );
        return $this->_exec($cmd);
    }

    /**
     * Restore database from backup
     *
     * @param string $name
     * @return bool
     */
    public function restoreBackup($name)
    {
        $cmd = sprintf(
            'mysql --protocol=TCP --host=%s --user=%s --password=%s %s < %s',
                escapeshellarg($this->_host),
                escapeshellarg($this->_user),
                escapeshellarg($this->_password),
                escapeshellarg($this->_schema),
                escapeshellarg($this->_getBackupFile($name))
        );
        return $this->_exec($cmd);
    }

    /**
     * Store backup files locally
     *
     * @param  $name
     * @return string
     */
    protected function _getBackupFile($name)
    {
        return $this->_varPath . DIRECTORY_SEPARATOR . $name . '.sql';
    }
}
