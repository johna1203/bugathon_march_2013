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

class Mage_Core_Helper_AbstractTestAbstract extends Mage_Core_Helper_Abstract
{
}

/**
 * @group module:Mage_Core
 */
class Mage_Core_Helper_AbstractTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Core_Helper_Abstract
     */
    protected $_helper = null;

    protected function setUp()
    {
        $this->_helper = new Mage_Core_Helper_AbstractTestAbstract;
    }

    /**
     * @covers Mage_Core_Helper_Abstract::isModuleEnabled
     * @covers Mage_Core_Helper_Abstract::isModuleOutputEnabled
     */
    public function testIsModuleEnabled()
    {
        $this->assertTrue($this->_helper->isModuleEnabled());
        $this->assertTrue($this->_helper->isModuleOutputEnabled());
    }

    public function test__()
    {
        $uniqueText = uniqid('prefix_');
        $this->assertEquals($uniqueText, $this->_helper->__($uniqueText));
    }

    /**
     * @covers Mage_Core_Helper_Abstract::escapeHtml
     * @covers Mage_Core_Helper_Abstract::htmlEscape
     */
    public function testEscapeHtml()
    {
        $data = array('one', '<two>three</two>');
        $expected = array('one', '&lt;two&gt;three&lt;/two&gt;');
        $this->assertEquals($expected, $this->_helper->escapeHtml($data));
        $this->assertEquals($expected, $this->_helper->escapeHtml($data));
    }

    public function testStripTags()
    {
        $this->assertEquals('three', $this->_helper->stripTags('<two>three</two>'));
    }

    /**
     * @covers Mage_Core_Helper_Abstract::escapeUrl
     * @covers Mage_Core_Helper_Abstract::urlEscape
     */
    public function testEscapeUrl()
    {
        $data = '<two>"three</two>';
        $expected = '&lt;two&gt;&quot;three&lt;/two&gt;';
        $this->assertEquals($expected, $this->_helper->escapeUrl($data));
        $this->assertEquals($expected, $this->_helper->urlEscape($data));
    }

    public function testJsQuoteEscape()
    {
        $data = array("Don't do that.", 'lost_key' => "Can't do that.");
        $expected = array("Don\\'t do that.", "Can\\'t do that.");
        $this->assertEquals($expected, $this->_helper->jsQuoteEscape($data));
        $this->assertEquals($expected[0], $this->_helper->jsQuoteEscape($data[0]));
    }

    /**
     * @covers Mage_Core_Helper_Abstract::quoteEscape
     */
    public function testQuoteEscape()
    {
        $data = "Text with 'single' and \"double\" quotes";
        $expected = array(
            "Text with &#039;single&#039; and &quot;double&quot; quotes",
            "Text with \\&#039;single\\&#039; and \\&quot;double\\&quot; quotes",
        );
        $this->assertEquals($expected[0], $this->_helper->quoteEscape($data));
        $this->assertEquals($expected[1], $this->_helper->quoteEscape($data, true));
    }

    public function testSetGetLayout()
    {
        $this->assertNull($this->_helper->getLayout());
        $this->assertInstanceof(get_class($this->_helper), $this->_helper->setLayout(Mage::app()->getLayout()));
        $this->assertInstanceOf('Mage_Core_Model_Layout', $this->_helper->getLayout());
    }

    public function testUrlEncodeDecode()
    {
        $data = uniqid();
        $result = $this->_helper->urlEncode($data);
        $this->assertNotContains('&', $result);
        $this->assertNotContains('%', $result);
        $this->assertNotContains('+', $result);
        $this->assertNotContains('=', $result);
        $this->assertEquals($data, $this->_helper->urlDecode($result));
    }

    public function testTranslateArray()
    {
        $data = array(uniqid(), array(uniqid(), array(uniqid())));
        $this->assertEquals($data, $this->_helper->translateArray($data));
    }
}
