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

class Mage_Core_Model_Variable_ConfigTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Core_Model_Variable_Config
     */
    protected $_model;

    protected function setUp()
    {
        $this->_model = new Mage_Core_Model_Variable_Config;
    }

    public function testGetWysiwygJsPluginSrc()
    {
        $src = $this->_model->getWysiwygJsPluginSrc();
        $this->assertStringStartsWith('http://localhost/js/', $src);
        $this->assertStringEndsWith('editor_plugin.js', $src);
    }
}
