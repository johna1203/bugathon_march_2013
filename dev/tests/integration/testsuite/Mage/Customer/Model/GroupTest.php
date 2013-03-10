<?php
/**
 * {license_notice}
 *
 * @category    Magento
 * @package     Magento_Customer
 * @subpackage  integration_tests
 * @copyright   {copyright}
 * @license     {license_link}
 */

/**
 * @group module:Mage_Customer
 */
class Mage_Customer_Model_GroupTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Customer_Model_Group
     */
    protected $_model;

    protected function setUp()
    {
        $this->_model= new Mage_Customer_Model_Group();
    }

    public function testCRUD()
    {
        $this->_model->setCustomerGroupCode('test');
        $crud = new Magento_Test_Entity($this->_model, array('customer_group_code' => uniqid()));
        $crud->testCrud();
    }
}
