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
class Mage_Core_Model_Config_BaseTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $config = new Mage_Core_Model_Config_Base(<<<XML
<?xml version="1.0"?>
<root><key>value</key></root>
XML
        );
        $this->assertInstanceOf('Mage_Core_Model_Config_Element', $config->getNode('key'));
    }
}
