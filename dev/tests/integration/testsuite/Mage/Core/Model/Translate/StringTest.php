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
class Mage_Core_Model_Translate_StringTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Core_Model_Translate_String
     */
    protected $_model;

    public function setUp()
    {
        $this->_model = new Mage_Core_Model_Translate_String();
    }

    public function testConstructor()
    {
        $this->assertInstanceOf('Mage_Core_Model_Resource_Translate_String', $this->_model->getResource());
    }

    public function testSetGetString()
    {
        $expectedString = __METHOD__;
        $this->_model->setString($expectedString);
        $actualString = $this->_model->getString();
        $this->assertEquals($expectedString, $actualString);
    }
}
