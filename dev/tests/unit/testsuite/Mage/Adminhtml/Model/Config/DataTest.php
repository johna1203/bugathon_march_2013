<?php
/**
 * {license_notice}
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @subpackage  unit_tests
 * @copyright   {copyright}
 * @license     {license_link}
 */

class Mage_Adminhtml_Model_Config_DataTest extends PHPUnit_Framework_TestCase
{
    /**
     * @param array $fixtureData
     * @param string $configPath
     * @param bool $expectedInheritFlag
     * @dataProvider getConfigDataValueDataProvider
     */
    public function testGetConfigDataValue(array $fixtureData, $configPath, $expectedInheritFlag)
    {
        $configData = $this->getMock('Mage_Adminhtml_Model_Config_Data', array('load'));
        $configData
                ->expects($this->any())
                ->method('load')
                ->will($this->returnValue($fixtureData))
        ;
        /** @var $configData Mage_Adminhtml_Model_Config_Data */
        $actualInheritFlag = null;
        $configData->getConfigDataValue($configPath, $actualInheritFlag);
        $this->assertEquals($expectedInheritFlag, $actualInheritFlag);
    }

    public function getConfigDataValueDataProvider()
    {
        return array(
            'empty config value at store view level' => array(
                array('design/head/title_prefix' => null), 'design/head/title_prefix', false
            ),
        );
    }
}