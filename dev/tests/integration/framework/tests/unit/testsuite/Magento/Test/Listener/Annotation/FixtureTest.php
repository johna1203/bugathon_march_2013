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

class Magento_Test_Listener_Annotation_FixtureTestSingleConnection extends Magento_Test_Listener_Annotation_Fixture
{
    protected function _isSingleConnection()
    {
        return true;
    }
}

/**
 * Test class for Magento_Test_Listener_Annotation_Fixture.
 *
 * @magentoDataFixture sampleFixtureOne
 */
class Magento_Test_Listener_Annotation_FixtureTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Magento_Test_Listener
     */
    protected $_listener;

    /**
     * @var Magento_Test_Listener_Annotation_Fixture|PHPUnit_Framework_MockObject_MockObject
     */
    protected $_annotation;

    protected function setUp()
    {
        $this->_listener = new Magento_Test_Listener;
        $this->_listener->startTest($this);

        $this->_annotation = $this->getMock(
            'Magento_Test_Listener_Annotation_FixtureTestSingleConnection',
            array('_startTransaction', '_rollbackTransaction', '_applyOneFixture'),
            array($this->_listener)
        );
    }

    public static function sampleFixtureOne()
    {
    }

    public static function sampleFixtureTwo()
    {
    }

    public function testClassAnnotation()
    {
        $this->_annotation
            ->expects($this->at(0))
            ->method('_startTransaction')
        ;
        $this->_annotation
            ->expects($this->at(1))
            ->method('_applyOneFixture')
            ->with(array(__CLASS__, 'sampleFixtureOne'))
        ;
        $this->_annotation->startTest();

        return $this->_annotation;
    }

    /**
     * @param Magento_Test_Listener_Annotation_Fixture $annotation
     * @depends testClassAnnotation
     */
    public function testClassAnnotationShared($annotation)
    {
        $this->_annotation
            ->expects($this->never())
            ->method('_applyOneFixture')
        ;
        $annotation->startTest();
    }

    /**
     * @magentoDataFixture sampleFixtureTwo
     * @magentoDataFixture path/to/fixture/script.php
     */
    public function testMethodAnnotation()
    {
        $this->_annotation
            ->expects($this->at(0))
            ->method('_startTransaction')
        ;
        $this->_annotation
            ->expects($this->at(1))
            ->method('_applyOneFixture')
            ->with(array(__CLASS__, 'sampleFixtureTwo'))
        ;
        $this->_annotation
            ->expects($this->at(2))
            ->method('_applyOneFixture')
            ->with($this->stringEndsWith('path/to/fixture/script.php'))
        ;
        $this->_annotation->startTest();

        $this->_annotation
            ->expects($this->once())
            ->method('_rollbackTransaction')
        ;
        $this->_annotation->endTest();
    }

    /**
     * @param Magento_Test_Listener_Annotation_Fixture $annotation
     * @depends testClassAnnotation
     */
    public function testEndTestSuite($annotation)
    {
        $annotation
            ->expects($this->once())
            ->method('_rollbackTransaction')
        ;
        $annotation->endTestSuite();
    }
}
