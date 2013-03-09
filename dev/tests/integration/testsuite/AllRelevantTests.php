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
 * Class that provides the test suite that includes only tests relevant to the current set of enabled modules
 */
class AllRelevantTests
{
    public static function suite()
    {
        $suite = new Magento_Test_TestSuite_ModuleGroups(false);
        $suite->addTestFiles(
            File_Iterator_Factory::getFileIterator(
                array(dirname(__FILE__)),
                array('Test.php')
            )
        );
        return $suite;
    }
}
