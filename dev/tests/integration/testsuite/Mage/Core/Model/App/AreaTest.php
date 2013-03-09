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
class Mage_Core_Model_App_AreaTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Core_Model_App_Area
     */
    protected $_model;

    public function setUp()
    {
        $this->_model = new Mage_Core_Model_App_Area('frontend', new Mage_Core_Model_App);
    }

    /**
     * @magentoAppIsolation enabled
     */
    public function testInitDesign()
    {
        $this->_model->load(Mage_Core_Model_App_Area::PART_DESIGN);
        /** @var Mage_Core_Model_Design_Package $design */
        $design = Mage::registry('_singleton/core/design_package');
        $this->assertInstanceOf('Mage_Core_Model_Design_Package', $design);
        $this->assertSame($design, Mage::getDesign());
        $this->assertEquals('frontend', $design->getArea());

        // try second time and make sure it won't load second time
        $this->_model->load(Mage_Core_Model_App_Area::PART_DESIGN);
        $this->assertSame($design, Mage::getDesign());
    }
}
