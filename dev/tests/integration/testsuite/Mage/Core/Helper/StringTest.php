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
class Mage_Core_Helper_StringTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Core_Helper_String
     */
    protected $_helper;

    public function setUp()
    {
        $this->_helper = new Mage_Core_Helper_String;
    }

    /**
     * @covers Mage_Core_Helper_String::truncate
     * @covers Mage_Core_Helper_String::strlen
     * @covers Mage_Core_Helper_String::substr
     */
    public function testTruncate()
    {
        $string = '1234567890';
        $this->assertEquals('12...', $this->_helper->truncate($string, 5));

        $words = '123 456 789';
        $remainder = '';
        $this->assertEquals('123...', $this->_helper->truncate($words, 8, '...', $remainder, false));
    }

    /**
     * @covers Mage_Core_Helper_String::splitInjection
     * @covers Mage_Core_Helper_String::strrev
     */
    public function testSplitInjection()
    {
        $string = '1234567890';
        $this->assertEquals('1234 5678 90', $this->_helper->splitInjection($string, 4));
    }

    public function testStrSplit()
    {
        $this->assertEquals(array(), $this->_helper->str_split(''));
        $this->assertEquals(array('1', '2', '3', '4'), $this->_helper->str_split('1234', 1));
        $this->assertEquals(array('1', '2', ' ', '3', '4'), $this->_helper->str_split('12 34', 1, false, true));
        $this->assertEquals(array(
            '12345', '123', '12345', '6789'
        ), $this->_helper->str_split('12345  123    123456789', 5, true, true));
    }

    /**
     * Bug: $maxWordLength parameter has a misleading name. It limits qty of words in the result.
     */
    public function testSplitWords()
    {
        $words = '123  123  45 789';
        $this->assertEquals(array('123', '123', '45'), $this->_helper->splitWords($words, false, 3));
        $this->assertEquals(array('123', '45'), $this->_helper->splitWords($words, true, 2));
    }

    public function testCleanString()
    {
        $string = '12345';
        $this->assertEquals($string, $this->_helper->cleanString($string));
    }

    public function testStrpos()
    {
        $this->assertEquals(1, $this->_helper->strpos('123', 2));
    }
}
