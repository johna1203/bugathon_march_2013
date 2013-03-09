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

class Magento_Test_Helper_FactoryTest extends PHPUnit_Framework_TestCase
{
    public function testGetHelper()
    {
        $helper = Magento_Test_Helper_Factory::getHelper('config');
        $this->assertNotEmpty($helper);

        $helperNew = Magento_Test_Helper_Factory::getHelper('config');
        $this->assertSame($helper, $helperNew, 'Factory must cache instances of helpers.');
    }

    public function testSetHelper()
    {
        $helper = new stdClass();
        Magento_Test_Helper_Factory::setHelper('config', $helper);
        $helperGot = Magento_Test_Helper_Factory::getHelper('config');
        $this->assertSame($helper, $helperGot, 'The helper must be used, when requested again');

        $helperNew = new stdClass();
        Magento_Test_Helper_Factory::setHelper('config', $helperNew);
        $helperGot = Magento_Test_Helper_Factory::getHelper('config');
        $this->assertSame($helperNew, $helperGot, 'The helper must be changed upon new setHelper() method');
    }
}

