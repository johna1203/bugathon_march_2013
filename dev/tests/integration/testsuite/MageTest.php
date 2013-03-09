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

class MageTest extends PHPUnit_Framework_TestCase
{
    public function testIsInstalled()
    {
        $this->assertTrue(Mage::isInstalled());
    }

    /**
     * @magentoAppIsolation enabled
     */
    public function testReset()
    {
        Mage::setRoot(dirname(__FILE__));
        $this->assertNotNull(Mage::getRoot());
        Mage::reset();
        $this->assertNull(Mage::getRoot());
    }

    /**
     * @magentoAppIsolation enabled
     */
    public function testGetDesign()
    {
        $design = Mage::getDesign();
        $this->assertEquals('frontend', $design->getArea());
        $this->assertSame(Mage::getDesign(), $design);
    }
}
