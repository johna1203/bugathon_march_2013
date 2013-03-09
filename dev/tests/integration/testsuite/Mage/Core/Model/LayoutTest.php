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
class Mage_Core_Model_LayoutTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Core_Model_Layout
     */
    protected $_model;

    public static function setUpBeforeClass()
    {
        /* Point application to predefined layout fixtures */
        Mage::getConfig()->setOptions(array(
            'design_dir' => dirname(__FILE__) . '/_files/design',
        ));
        Mage::getDesign()->setPackageName('test')
            ->setTheme('default');

        /* Disable loading and saving layout cache */
        Mage::app()->getCacheInstance()->banUse('layout');
    }

    protected function setUp()
    {
        $this->_model = new Mage_Core_Model_Layout();
        $this->_model->getUpdate()->addHandle('layout_test_handle_main');
        $this->_model->getUpdate()->load('layout_test_handle_extra');
    }

    public function testGetUpdate()
    {
        $this->assertInstanceOf('Mage_Core_Model_Layout_Update', $this->_model->getUpdate());
    }

    public function testGetSetArea()
    {
        $this->assertEmpty($this->_model->getArea());
        $this->_model->setArea('frontend');
        $this->assertEquals('frontend', $this->_model->getArea());
    }

    public function testGetSetDirectOutput()
    {
        $this->assertFalse($this->_model->getDirectOutput());
        $this->_model->setDirectOutput(true);
        $this->assertTrue($this->_model->getDirectOutput());
    }

    /**
     * @covers Mage_Core_Model_Layout::getAllBlocks
     * @covers Mage_Core_Model_Layout::generateBlocks
     * @covers Mage_Core_Model_Layout::getBlock
     */
    public function testGenerateXmlAndBlocks()
    {
        $this->_model->generateXml();
        /* Generate fixture
        file_put_contents(dirname(__FILE__) . '/_files/_layout_update.xml', $this->_model->getNode()->asNiceXml());
        */
        $this->assertXmlStringEqualsXmlFile(dirname(__FILE__) . '/_files/_layout_update.xml', $this->_model->getXmlString());

        $this->assertEquals(array(), $this->_model->getAllBlocks());

        $expectedBlocks = array(
            'root',
            'head',
            'head.calendar',
            'notifications',
            'notification_baseurl',
            'cache_notifications',
            'notification_survey',
            'notification_security',
            'messages'
        );
        $this->_model->generateBlocks();

        $actualBlocks = $this->_model->getAllBlocks();
        $this->assertEquals($expectedBlocks, array_keys($actualBlocks));

        /** @var $block Mage_Adminhtml_Block_Page_Head */
        $block = $this->_model->getBlock('head');
        $this->assertEquals('Magento Admin', $block->getTitle());

        $block = $this->_model->getBlock('head.calendar');
        $this->assertSame($this->_model->getBlock('head'), $block->getParentBlock());

        /** @var $block Mage_Core_Block_Template */
        $block = $this->_model->getBlock('root');
        $this->assertEquals('popup.phtml', $block->getTemplate());
    }

    public function testSetUnsetBlock()
    {
        $expectedBlockName = 'block_' . __METHOD__;
        $expectedBlock = new Mage_Core_Block_Text();

        $this->_model->setBlock($expectedBlockName, $expectedBlock);
        $this->assertSame($expectedBlock, $this->_model->getBlock($expectedBlockName));

        $this->_model->unsetBlock($expectedBlockName);
        $this->assertFalse($this->_model->getBlock($expectedBlockName));
    }

    /**
     * @dataProvider createBlockDataProvider
     */
    public function testCreateBlock($blockType, $blockName, array $blockData, $expectedName, $expectedAnonSuffix = null)
    {
        $expectedData = $blockData + array('type' => $blockType);

        $block = $this->_model->createBlock($blockType, $blockName, $blockData);

        $this->assertEquals($this->_model, $block->getLayout());
        $this->assertRegExp($expectedName, $block->getNameInLayout());
        $this->assertEquals($expectedData, $block->getData());
        $this->assertEquals($expectedAnonSuffix, $block->getAnonSuffix());
    }

    public function createBlockDataProvider()
    {
        return array(
            'named block' => array(
                'core/text',
                'some_block_name',
                array('type' => 'core/text'),
                '/^some_block_name$/'
            ),
            'anonymous block' => array(
                'core/text_list',
                '',
                array('type' => 'core/text_list',
                'key1' => 'value1'),
                '/^ANONYMOUS_.+/'
            ),
            'anonymous suffix' => array(
                'core/template',
                '.some_anonymous_suffix',
                array('type' => 'core/template'),
                '/^ANONYMOUS_.+/',
                'some_anonymous_suffix'
            )
        );
    }

    /**
     * @covers Mage_Core_Model_Layout::addBlock
     * @covers Mage_Core_Model_Layout::addOutputBlock
     * @covers Mage_Core_Model_Layout::getOutput
     * @covers Mage_Core_Model_Layout::removeOutputBlock
     */
    public function testGetOutput()
    {
        $blockName = 'block_' . __METHOD__;
        $expectedText = "some_text_for_$blockName";

        $block = new Mage_Core_Block_Text();
        $block->setText($expectedText);
        $this->_model->addBlock($block, $blockName);

        $this->_model->addOutputBlock($blockName);
        $this->assertEquals($expectedText, $this->_model->getOutput());

        $this->_model->removeOutputBlock($blockName);
        $this->assertEmpty($this->_model->getOutput());
    }

    public function testGetMessagesBlock()
    {
        $this->assertInstanceOf('Mage_Core_Block_Messages', $this->_model->getMessagesBlock());
    }

    public function testGetBlockSingleton()
    {
        $block = $this->_model->getBlockSingleton('core/text');
        $this->assertInstanceOf('Mage_Core_Block_Text', $block);
        $this->assertSame($block, $this->_model->getBlockSingleton('core/text'));
    }

    public function testHelper()
    {
        $helper = $this->_model->helper('core');
        $this->assertInstanceOf('Mage_Core_Helper_Data', $helper);
        $this->assertSame($this->_model, $helper->getLayout());
    }
}
