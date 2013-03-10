<?php
/**
 * {license_notice}
 *
 * @category    Magento
 * @package     Magento_Catalog
 * @subpackage  integration_tests
 * @copyright   {copyright}
 * @license     {license_link}
 */

/**
 * Test class for Mage_Catalog_IndexController.
 *
 * @group module:Mage_Catalog
 */
class Mage_Catalog_IndexControllerTest extends Magento_Test_TestCase_ControllerAbstract
{
    public function testIndexAction()
    {
        $this->dispatch('catalog/index');

        $this->assertRedirect();
    }
}
