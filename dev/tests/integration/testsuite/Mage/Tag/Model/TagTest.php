<?php
/**
 * {license_notice}
 *
 * @category    Magento
 * @package     Magento_Tag
 * @subpackage  integration_tests
 * @copyright   {copyright}
 * @license     {license_link}
 */

/**
 * @group module:Mage_Tag
 */
class Mage_Tag_Model_TagTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Tag_Model_Tag
     */
    protected $_model;

    protected function setUp()
    {
        $this->_model= new Mage_Tag_Model_Tag();
    }

    /**
     * @magentoAppIsolation enabled
     */
    public function testCRUD()
    {
        Mage::app()->setCurrentStore(Mage::app()->getStore(Mage_Core_Model_App::ADMIN_STORE_ID));
        $this->_model->setName('test');
        $crud = new Magento_Test_Entity($this->_model, array('name' => uniqid()));
        $crud->testCrud();
    }
}
