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

/**
 * Parent Suite running all the Unit tests
 */
class AllTests
{
    /**
     * @return PHPUnit_Framework_TestSuite
     */
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Magento');
        $suite->addTest(Varien_AllTests::suite());
        $suite->addTest(Mage_AllTests::suite());
        return $suite;
    }
}
