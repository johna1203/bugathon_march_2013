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
class Mage_Core_Model_Resource_IteratorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Core_Model_Resource_Iterator
     */
    protected $_model;

    /**
     * Counter for testing walk() callback
     *
     * @var int
     */
    protected $_callbackCounter = 0;

    public function setUp()
    {
        $this->_model = new Mage_Core_Model_Resource_Iterator();
    }

    public function testWalk()
    {
        $collection = new Mage_Core_Model_Resource_Store_Collection();
        $this->_model->walk($collection->getSelect(), array(array($this, 'walkCallback')));
        $this->assertGreaterThan(0, $this->_callbackCounter);
    }

    /**
     * Helper callback for testWalk()
     *
     * @param array $data
     * @return bool
     */
    public function walkCallback($data)
    {
        $this->_callbackCounter = $data['idx'];
        return true;
    }

    /**
     * @expectedException Mage_Core_Exception
     */
    public function testWalkException()
    {
        $this->_model->walk('test', array(array($this, 'walkCallback')));
    }
}
