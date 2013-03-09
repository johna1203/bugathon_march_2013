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
class Mage_Core_Block_TemplateTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Core_Block_Template
     */
    protected $_block;

    protected function setUp()
    {
        $this->_block = new Mage_Core_Block_Template;
    }

    public function testConstruct()
    {
        $block = new Mage_Core_Block_Template(array('template' => 'value'));
        $this->assertEquals('value', $block->getTemplate());
    }

    public function testSetGetTemplate()
    {
        $this->assertEmpty($this->_block->getTemplate());
        $this->_block->setTemplate('value');
        $this->assertEquals('value', $this->_block->getTemplate());
    }

    /**
     * @magentoAppIsolation enabled
     */
    public function testGetTemplateFile()
    {
        $this->assertStringStartsWith('frontend', $this->_block->getTemplateFile());

        Mage::app()->getConfig()->getOptions()->setDesignDir(dirname(__FILE__) . DIRECTORY_SEPARATOR . '_files');

        // with template
        $template = 'dummy.phtml';
        $this->_block->setTemplate($template);
        $file = $this->_block->getTemplateFile();
        $this->assertStringStartsWith('frontend', $file);
        $this->assertStringEndsWith($template, $file);


        // change area
        $this->_block->setArea('adminhtml');
        $file = $this->_block->getTemplateFile();
        $this->assertStringStartsWith('adminhtml', $file);
        $this->assertStringEndsWith($template, $file);
    }

    public function testGetArea()
    {
        $this->_block->setArea('value');
        $this->assertEquals('value', $this->_block->getArea());
    }

    /**
     * @magentoAppIsolation enabled
     * @covers Mage_Core_Block_Template::assign
     * @covers Mage_Core_Block_Template::setScriptPath
     * @covers Mage_Core_Block_Template::fetchView
     */
    public function testAssign()
    {
        Mage::app()->getConfig()->getOptions()->setDesignDir(dirname(__FILE__) . DIRECTORY_SEPARATOR . '_files');

        $this->_block->assign(array('varOne' => 'value1', 'varTwo' => 'value2'))
            ->setScriptPath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '_files');
        $template = 'template_test_assign.phtml';
        $this->assertEquals('value1, value2', $this->_block->fetchView($template));
    }

    public function testGetDirectOutput()
    {
        $this->assertFalse($this->_block->getDirectOutput());

        $layout = new Mage_Core_Model_Layout;
        $layout->setDirectOutput(true);
        $this->_block->setLayout($layout);
        $this->assertTrue($this->_block->getDirectOutput());
    }

    public function testGetShowTemplateHints()
    {
        $this->assertFalse($this->_block->getShowTemplateHints());
    }

    /**
     * @covers Mage_Core_Block_Template::fetchView
     * @covers Mage_Core_Block_Abstract::setLayout
     * @covers Mage_Core_Block_Abstract::getLayout
     * @see testAssign
     */
    public function testFetchView()
    {
        $layout = new Mage_Core_Model_Layout;
        $layout->setDirectOutput(true);
        $this->_block->setLayout($layout);
        $this->assertTrue($this->_block->getDirectOutput());

        $this->assertEmpty($this->_block->fetchView(uniqid('invalid_filename.phtml')));
    }

    /**
     * @magentoAppIsolation enabled
     */
    public function testRenderView()
    {
        $this->assertEmpty($this->_block->renderView());
        Mage::app()->getConfig()->getOptions()->setDesignDir(dirname(__FILE__) . DIRECTORY_SEPARATOR . '_files');
        Mage::getDesign()->setTheme('default');
        Mage::getDesign()->setPackageName('default');
        $this->_block->setTemplate('dummy.phtml');
        $this->assertEquals('1234567890', $this->_block->renderView());
    }

    /**
     * @covers Mage_Core_Block_Template::_toHtml
     * @covers Mage_Core_Block_Abstract::toHtml
     * @see testAssign()
     */
    public function testToHtml()
    {
        $this->assertEmpty($this->_block->toHtml());
        $this->_block->setTemplate(uniqid('invalid_filename.phtml'));
        $this->assertEmpty($this->_block->toHtml());
    }

    public function testGetBaseUrl()
    {
        $this->assertEquals('http://localhost/index.php/', $this->_block->getBaseUrl());
    }

    public function testGetObjectData()
    {
        $object = new Varien_Object(array('key' => 'value'));
        $this->assertEquals('value', $this->_block->getObjectData($object, 'key'));
    }

    public function testGetCacheKeyInfo()
    {
        $this->assertArrayHasKey('template', $this->_block->getCacheKeyInfo());
    }
}
