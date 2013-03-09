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
class Mage_Core_Controller_Varien_FrontTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Core_Controller_Varien_Front
     */
    protected $_model;

    protected function setUp()
    {
        $this->_model = new Mage_Core_Controller_Varien_Front;
    }

    public function testSetGetDefault()
    {
        $this->_model->setDefault('test', 'value');
        $this->assertEquals('value', $this->_model->getDefault('test'));

        $default = array('some_key' => 'some_value');
        $this->_model->setDefault($default);
        $this->assertEquals($default, $this->_model->getDefault());
    }

    public function testGetRequest()
    {
        $this->assertInstanceOf('Mage_Core_Controller_Request_Http', $this->_model->getRequest());
    }

    public function testGetResponse()
    {
        if (!Magento_Test_Bootstrap::canTestHeaders()) {
            $this->markTestSkipped('Can\'t test get response without sending headers');
        }
        $this->assertInstanceOf('Mage_Core_Controller_Response_Http', $this->_model->getResponse());
    }

    public function testAddGetRouter()
    {
        $router = new Mage_Core_Controller_Varien_Router_Default();
        $this->_model->addRouter('test', $router);
        $this->assertEquals($router, $this->_model->getRouter('test'));
        $this->assertEmpty($this->_model->getRouter('tt'));
    }

    public function testGetRouters()
    {
        $this->assertEmpty($this->_model->getRouters());
        $this->_model->addRouter('test', new Mage_Core_Controller_Varien_Router_Default());
        $this->assertNotEmpty($this->_model->getRouters());
    }

    public function testInit()
    {
        $this->assertEmpty($this->_model->getRouters());
        $this->_model->init();
        $this->assertNotEmpty($this->_model->getRouters());
    }

    public function testDispatch()
    {
        if (!Magento_Test_Bootstrap::canTestHeaders()) {
            $this->markTestSkipped('Cant\'t test dispatch process without sending headers');
        }
        $_SERVER['HTTP_HOST'] = 'localhost';
        $this->_model->init();
        /* empty action */
        $this->_model->getRequest()->setRequestUri('core/index/index');
        $this->_model->dispatch();
        $this->assertEmpty($this->_model->getResponse()->getBody());
    }

    public function testGetRouterByRoute()
    {
        $this->_model->init();
        $this->assertInstanceOf('Mage_Core_Controller_Varien_Router_Standard', $this->_model->getRouterByRoute(''));
        $this->assertInstanceOf(
            'Mage_Core_Controller_Varien_Router_Standard',
            $this->_model->getRouterByRoute('checkout')
        );
        $this->assertInstanceOf('Mage_Core_Controller_Varien_Router_Default', $this->_model->getRouterByRoute('test'));
        $this->assertInstanceOf('Mage_Core_Controller_Varien_Router_Admin', $this->_model->getRouterByRoute('admin'));
    }

    public function testGetRouterByFrontName()
    {
        $this->_model->init();
        $this->assertInstanceOf(
            'Mage_Core_Controller_Varien_Router_Standard',
            $this->_model->getRouterByFrontName('')
        );
        $this->assertInstanceOf(
            'Mage_Core_Controller_Varien_Router_Standard',
            $this->_model->getRouterByFrontName('checkout')
        );
        $this->assertInstanceOf(
            'Mage_Core_Controller_Varien_Router_Default',
            $this->_model->getRouterByFrontName('test')
        );
        $this->assertInstanceOf(
            'Mage_Core_Controller_Varien_Router_Admin',
            $this->_model->getRouterByFrontName('admin')
        );
    }

    public function testRewrite()
    {
        $route      = $this->_model->getRequest()->getRouteName();
        $controller = $this->_model->getRequest()->getControllerName();
        $action     = $this->_model->getRequest()->getActionName();

        $this->_model->rewrite();

        $this->assertEquals($route, $this->_model->getRequest()->getRouteName());
        $this->assertEquals($controller, $this->_model->getRequest()->getControllerName());
        $this->assertEquals($action, $this->_model->getRequest()->getActionName());
        $this->markTestIncomplete('Requires an URL rewrite fixture.');
    }
}
