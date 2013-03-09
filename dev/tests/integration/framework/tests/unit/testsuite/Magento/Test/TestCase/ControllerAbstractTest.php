<?php
/**
 * {license_notice}
 *
 * @category    Magento
 * @package     Magento
 * @subpackage  integration_tests
 * @copyright   {copyright}
 * @license     {license_link}
 */

class Magento_Test_TestCase_ControllerAbstractTest extends Magento_Test_TestCase_ControllerAbstract
{
    protected $_bootstrap;

    /**
     * Bootstrap instance getter.
     * Mocking real bootstrap
     *
     * @return Magento_Test_Bootstrap
     */
    protected function _getBootstrap()
    {
        if (!$this->_bootstrap) {
            $this->_bootstrap = $this->getMock('Magento_Test_Bootstrap', array('getAllOptions'), array(), '', false);
        }
        return $this->_bootstrap;
    }

    public function testSetUp()
    {
        $this->assertInternalType('array', $this->_runOptions);
        $this->assertArrayHasKey('request', $this->_runOptions);
        $this->assertInstanceOf('Magento_Test_Request', $this->_runOptions['request']);
        $this->assertArrayHasKey('response', $this->_runOptions);
        $this->assertInstanceOf('Magento_Test_Response', $this->_runOptions['response']);
    }

    public function testGetRequest()
    {
        $request = $this->getRequest();
        $this->assertInstanceOf('Magento_Test_Request', $request);
        $this->assertSame($request, $this->getRequest());
    }

    public function testGetResponse()
    {
        $response = $this->getResponse();
        $this->assertInstanceOf('Magento_Test_Response', $response);
        $this->assertSame($response, $this->getResponse());
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function testAssert404NotFound()
    {
        $this->getRequest()->setActionName('noRoute');
        $this->getResponse()->setBody(
            '404 Not Found test <h3>We are sorry, but the page you are looking for cannot be found.</h3>'
        );
        $this->assert404NotFound();

        $this->getResponse()->setBody('');
        try {
            $this->assert404NotFound();
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            return;
        }
        $this->fail('Failed response body validation');
    }

    /**
     * @expectedException PHPUnit_Framework_AssertionFailedError
     */
    public function testAssertRedirectFailure()
    {
        $this->assertRedirect();
    }

    /**
     * @depends testAssertRedirectFailure
     */
    public function testAssertRedirect()
    {
        $this->getResponse()->setRedirect('http://magentocommerce.com');
        $this->assertRedirect();
        $this->assertRedirect('http://magentocommerce.com');
    }
}

