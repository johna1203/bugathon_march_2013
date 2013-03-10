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
class Mage_Core_Model_Config_SystemTest extends PHPUnit_Framework_TestCase
{
    public function testLoad()
    {
        $model = new Mage_Core_Model_Config_System;
        $model->load('Mage_Core');
        $this->assertInstanceOf('Mage_Core_Model_Config_Element', $model->getNode());
        $this->assertInstanceOf('Mage_Core_Model_Config_Element', $model->getNode('sections'));
        $this->assertInstanceOf('Mage_Core_Model_Config_Element', $model->getNode('sections/web'));

        $this->assertFalse($model->getNode('sections/cms'));
        $model->load('Mage_Cms');
        $this->assertInstanceOf('Mage_Core_Model_Config_Element', $model->getNode('sections/cms'));
    }
}
