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

/**
 * Test class for Magento_Test_Listener_Annotation_Isolation.
 */
class Magento_Test_Listener_Annotation_IsolationTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Magento_Test_Listener
     */
    protected $_listener;

    /**
     * @var Magento_Test_Listener_Annotation_Isolation|PHPUnit_Framework_MockObject_MockObject
     */
    protected $_annotation;

    protected function setUp()
    {
        $this->_listener = new Magento_Test_Listener;
        $this->_listener->startTest($this);

        $this->_annotation = $this->getMock(
            'Magento_Test_Listener_Annotation_Isolation',
            array('_isolateApp'),
            array($this->_listener)
        );
    }

    protected function tearDown()
    {
        $this->_listener->endTest($this->_listener->getCurrentTest(), 0);
    }

    public function testStartTestSuite()
    {
        $this->_annotation->expects($this->once())->method('_isolateApp');
        $this->_annotation->startTestSuite();
    }

    /**
     * @magentoAppIsolation invalid
     * @expectedException Exception
     */
    public function testEndTestIsolationInvalid()
    {
        $this->_annotation->endTest();
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoAppIsolation disabled
     * @expectedException Exception
     */
    public function testEndTestIsolationAmbiguous()
    {
        $this->_annotation->endTest();
    }

    public function testEndTestIsolationDefault()
    {
        $this->_annotation->expects($this->never())->method('_isolateApp');
        $this->_annotation->endTest();
    }

    public function testEndTestIsolationController()
    {
        /** @var $controllerTestCase Magento_Test_TestCase_ControllerAbstract */
        $controllerTestCase = $this->getMockForAbstractClass('Magento_Test_TestCase_ControllerAbstract');
        $this->_listener->startTest($controllerTestCase);
        $this->_annotation->expects($this->once())->method('_isolateApp');
        $this->_annotation->endTest();
    }

    /**
     * @magentoAppIsolation disabled
     */
    public function testEndTestIsolationDisabled()
    {
        $this->_annotation->expects($this->never())->method('_isolateApp');
        $this->_annotation->endTest();
    }

    /**
     * @magentoAppIsolation enabled
     */
    public function testEndTestIsolationEnabled()
    {
        $this->_annotation->expects($this->once())->method('_isolateApp');
        $this->_annotation->endTest();
    }
}
