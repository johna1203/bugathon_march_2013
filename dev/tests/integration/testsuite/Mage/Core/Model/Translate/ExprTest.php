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
class Mage_Core_Model_Translate_ExprTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Core_Model_Translate_Expr
     */
    protected $_model;

    protected $_expectedText   = __FILE__;
    protected $_expectedModule = __CLASS__;

    public function setUp()
    {
        $this->_model = new Mage_Core_Model_Translate_Expr($this->_expectedText, $this->_expectedModule);
    }

    public function testConstructor()
    {
        $this->assertEquals($this->_expectedText, $this->_model->getText());
        $this->assertEquals($this->_expectedModule, $this->_model->getModule());
    }

    public function testSetTextSetModule()
    {
        $expectedText = __FILE__ . '!!!';
        $expectedModule = __CLASS__ . '!!!';
        $this->_model->setText($expectedText);
        $this->_model->setModule($expectedModule);
        $this->assertEquals($expectedText, $this->_model->getText());
        $this->assertEquals($expectedModule, $this->_model->getModule());
    }

    public function testGetCode()
    {
        $this->assertEquals($this->_expectedModule . '::' . $this->_expectedText, $this->_model->getCode());
        $this->assertEquals($this->_expectedModule . '##' . $this->_expectedText, $this->_model->getCode('##'));
    }
}
