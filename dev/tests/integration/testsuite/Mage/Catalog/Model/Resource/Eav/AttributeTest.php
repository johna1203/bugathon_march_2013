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
 * @group module:Mage_Catalog
 */
class Mage_Catalog_Model_Resource_Eav_AttributeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Catalog_Model_Resource_Eav_Attribute
     */
    protected $_model;

    protected function setUp()
    {
        $this->_model= new Mage_Catalog_Model_Resource_Eav_Attribute();
    }

    public function testCRUD()
    {
        $this->_model->setAttributeCode('test')
            ->setEntityTypeId(Mage::getSingleton('eav/config')->getEntityType('catalog_product')->getId())
            ->setFrontendLabel('test');
        $crud = new Magento_Test_Entity($this->_model, array('frontend_label' => uniqid()));
        $crud->testCrud();
    }
}
