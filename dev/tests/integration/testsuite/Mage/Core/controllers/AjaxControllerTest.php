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
class Mage_Core_AjaxControllerTest extends Magento_Test_TestCase_ControllerAbstract
{
    public function testTranslateAction()
    {
        $this->getRequest()->setPost('translate', 'test');
        $this->dispatch('core/ajax/translate');
        $this->assertEquals('{success:true}', $this->getResponse()->getBody());
    }
}
