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
class Mage_Core_Model_Layout_UpdateTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Core_Model_Layout_Update
     */
    protected $_model;

    public static function setUpBeforeClass()
    {
        /* Point application to predefined layout fixtures */
        Mage::getConfig()->setOptions(array(
            'design_dir' => dirname(dirname(__FILE__)) . '/_files/design',
        ));
        Mage::getDesign()->setPackageName('test')
            ->setTheme('default');

        /* Disable loading and saving layout cache */
        Mage::app()->getCacheInstance()->banUse('layout');
    }

    protected function setUp()
    {
        $this->_model = new Mage_Core_Model_Layout_Update();
    }

    /**
     * Replace configuration XML node <area>/layout/updates with the desired content
     *
     * @param string $replacementXmlStr
     * @param string $area
     */
    protected function _replaceConfigLayoutUpdates($replacementXmlStr, $area = 'frontend')
    {
        /* Erase existing layout updates */
        unset(Mage::app()->getConfig()->getNode("{$area}/layout")->updates);
        /* Setup layout updates fixture */
        Mage::app()->getConfig()->extend(new Varien_Simplexml_Config("
            <config>
                <{$area}>
                    <layout>
                        <updates>
                            {$replacementXmlStr}
                        </updates>
                    </layout>
                </{$area}>
            </config>
        "));
    }

    /**
     * Retrieve contents of the layout update file, preprocessed to be comparable with the merged layout data
     *
     * @param string $filename
     * @return string
     */
    protected function _readLayoutFileContents($filename)
    {
        /* Load & render XML to get rid of comments and replace root node name from <layout> to <layouts> */
        $xml = simplexml_load_file($filename, 'Varien_Simplexml_Element');
        $text = '';
        foreach ($xml->children() as $child) {
            $text .= $child->asNiceXml();
        }
        return '<layouts>' . $text . '</layouts>';
    }

    public function testGetFileLayoutUpdatesXmlFromTheme()
    {
        $this->_replaceConfigLayoutUpdates('
            <core module="Mage_Core">
                <file>core.xml</file>
            </core>
        ');
        $expectedXmlStr = $this->_readLayoutFileContents(
            dirname(__FILE__) . '/../_files/design/frontend/test/default/layout/core.xml'
        );
        $actualXml = $this->_model->getFileLayoutUpdatesXml('frontend', 'test', 'default');
        $this->assertXmlStringEqualsXmlString($expectedXmlStr, $actualXml->asNiceXml());
    }

    /**
     * @magentoConfigFixture current_store advanced/modules_disable_output/Mage_Catalog true
     * @magentoConfigFixture current_store advanced/modules_disable_output/Mage_Page    true
     */
    public function testGetFileLayoutUpdatesXmlDisabledOutput()
    {
        $this->_replaceConfigLayoutUpdates('
            <catalog module="Mage_Catalog">
                <file>catalog.xml</file>
            </catalog>
            <core module="Mage_Core">
                <file>core.xml</file>
            </core>
            <page module="Mage_Page">
                <file>page.xml</file>
            </page>
        ');
        $expectedXmlStr = $this->_readLayoutFileContents(
            dirname(__FILE__) . '/../_files/design/frontend/test/default/layout/core.xml'
        );
        $actualXml = $this->_model->getFileLayoutUpdatesXml('frontend', 'test', 'default');
        $this->assertXmlStringEqualsXmlString($expectedXmlStr, $actualXml->asNiceXml());
    }
}
