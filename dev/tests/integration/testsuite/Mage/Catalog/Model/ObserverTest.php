<?php
/**
 * {license_notice}
 *
 * @category    Magento
 * @package     Magento_Catalog
 * @subpackage  integration_tests
 * @copyright   {copyright}
 * @license     {license_link}
 */

/**
 *
 * @group module:Mage_Catalog
 */
class Mage_Catalog_Model_ObserverTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Catalog_Model_Observer
     */
    protected $_model;

    protected function setUp()
    {
        $this->_model = new Mage_Catalog_Model_Observer;
    }

    protected function tearDown()
    {
        $this->_model = null;
    }

    public function testCheckReservedAttributeCodesAttributeEmptySuccess()
    {
        $event = new Varien_Object(array('attribute' => null));
        $observer = new Varien_Event_Observer();
        $observer->setEvent($event);
        $this->_model->checkReservedAttributeCodes($observer);
    }

    /**
     * @expectedException Mage_Core_Exception
     */
    public function testCheckReservedAttributeCodesAttributeException()
    {
        $attribute = new Mage_Catalog_Model_Entity_Attribute();
        $attribute->setIsUserDefined(true);
        $attribute->setAttributeCode('position');
        $observer = new Varien_Event_Observer();

        $event = new Varien_Object(array('attribute' => $attribute));
        $observer->setEvent($event);

        $this->_model->checkReservedAttributeCodes($observer);
    }
}
