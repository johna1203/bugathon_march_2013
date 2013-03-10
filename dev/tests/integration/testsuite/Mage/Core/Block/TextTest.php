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
class Mage_Core_Block_TextTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Core_Block_Text
     */
    protected $_block;

    public function setUp()
    {
        $this->_block = new Mage_Core_Block_Text;
    }

    public function tearDown()
    {
        $this->_block = null;
    }


    public function testSetGetText()
    {
        $this->_block->setText('text');
        $this->assertEquals('text', $this->_block->getText());
    }

    public function testAddText()
    {
        $this->_block->addText('a');
        $this->assertEquals('a', $this->_block->getText());

        $this->_block->addText('b');
        $this->assertEquals('ab', $this->_block->getText());

        $this->_block->addText('c', false);
        $this->assertEquals('abc', $this->_block->getText());

        $this->_block->addText('-', true);
        $this->assertEquals('-abc', $this->_block->getText());
    }

    public function testToHtml()
    {
        $this->_block->setText('test');
        $this->assertEquals('test', $this->_block->toHtml());
    }
}
