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
class Mage_Core_Model_Layout_DataTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Core_Model_Layout_Data
     */
    protected $_model;

    public function testConstructor()
    {
        $this->_model = new Mage_Core_Model_Layout_Data();
        $this->assertInstanceOf('Mage_Core_Model_Resource_Layout', $this->_model->getResource());
    }

    public function testCRUD()
    {
        $this->_model = new Mage_Core_Model_Layout_Data();
        $this->_model->setData(array(
            'handle' => 'default',
            'xml' => '<layout/>',
            'sort_order' => 123,
        ));
        $entityHelper = new Magento_Test_Entity($this->_model, array(
            'handle' => 'custom',
            'xml' => '<layout version="0.1.0"/>',
            'sort_order' => 456,
        ));
        $entityHelper->testCrud();
    }
}
