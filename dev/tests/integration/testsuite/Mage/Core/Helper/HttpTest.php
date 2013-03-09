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
class Mage_Core_Helper_HttpTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Core_Helper_Http
     */
    protected $_helper = null;

    public function setUp()
    {
        $this->_helper = new Mage_Core_Helper_Http;
    }

    public function testGetRemoteAddrHeaders()
    {
        $this->assertEquals(array(), $this->_helper->getRemoteAddrHeaders());
    }

    public function testGetRemoteAddr()
    {
        $this->assertEquals(false, $this->_helper->getRemoteAddr());
    }

    public function testGetServerAddr()
    {
        $this->assertEquals(false, $this->_helper->getServerAddr());
    }

    public function testGetHttpMethods()
    {
        $this->assertEquals(false, $this->_helper->getHttpAcceptCharset());
        $this->assertEquals(false, $this->_helper->getHttpHost());
        $this->assertEquals(false, $this->_helper->getHttpReferer());
        $this->assertEquals(false, $this->_helper->getHttpAcceptLanguage());
        $this->assertEquals(false, $this->_helper->getHttpUserAgent());
    }

    public function testGetRequestUri()
    {
        $this->assertNull($this->_helper->getRequestUri());
    }

    public function testValidateIpAddr()
    {
        $this->assertTrue((bool)$this->_helper->validateIpAddr('127.0.0.1'));
        $this->assertFalse((bool)$this->_helper->validateIpAddr('invalid'));
    }
}
