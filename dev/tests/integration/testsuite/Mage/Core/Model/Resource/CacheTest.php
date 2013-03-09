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
class Mage_Core_Model_Resource_CacheTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Core_Model_Resource_Cache
     */
    protected $_model;

    public function setUp()
    {
        $this->_model = new Mage_Core_Model_Resource_Cache();
    }

    public function testGetTable()
    {
        $this->assertEquals('core_cache_option', $this->_model->getTable('core/cache_option'));
        $this->assertEquals('core_cache_option', $this->_model->getTable(array('cache', 'option')));
    }

    public function testUniqueFields()
    {
        $fields = array('field' => 'text');
        $this->_model->addUniqueField($fields);
        $this->assertEquals(array($fields), $this->_model->getUniqueFields());
        $this->_model->resetUniqueField();
        $this->assertEquals(array(), $this->_model->getUniqueFields());
    }

    public function testHasDataChanged()
    {
        $object = new Varien_Object(
            array(
                'code'  => 'value1',
                'value' => 'value2'
            )
        );
        $this->assertTrue($this->_model->hasDataChanged($object));

        $object->setOrigData();
        $this->assertFalse($this->_model->hasDataChanged($object));
        $object->setData('code', 'v1');
        $this->assertTrue($this->_model->hasDataChanged($object));
    }

    public function testGetSaveAllOptions()
    {
        $options = $this->_model->getAllOptions();
        $this->assertEquals(array('config' => 1), $options);
        $options['test_option'] = 1;
        $this->_model->saveAllOptions($options);
        try {
            $this->assertEquals($options, $this->_model->getAllOptions());
        } catch (Exception $e) {
            unset($options['test_option']);
            $this->_model->saveAllOptions($options);
            throw $e;
        }

        unset($options['test_option']);
        $this->_model->saveAllOptions($options);
        $this->assertEquals($options, $this->_model->getAllOptions());
    }
}
