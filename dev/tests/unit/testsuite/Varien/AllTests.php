<?php
/**
 * {license_notice}
 *
 * @category    Magento
 * @package     Varien
 * @subpackage  unit_tests
 * @copyright   {copyright}
 * @license     {license_link}
 */

class Varien_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Varien Library');
        $suite->addTestSuite('Varien_Data_Collection_DbTest');
        $suite->addTestSuite('Varien_Db_Adapter_Pdo_MysqlTest');
        return $suite;
    }
}
