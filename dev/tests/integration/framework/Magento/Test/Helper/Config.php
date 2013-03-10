<?php
/**
 * {license_notice}
 *
 * @category    Magento
 * @package     Magento_Test
 * @subpackage  integration_tests
 * @copyright   {copyright}
 * @license     {license_link}
 */

/**
 * Helper with routines to work with Magento config
 */
class Magento_Test_Helper_Config
{
    /**
     * Returns enabled modules in the system
     *
     * @return array
     */
    public function getEnabledModules()
    {
        $result = array();
        foreach (Mage::getConfig()->getNode('modules')->children() as $moduleNode) {
            if ($moduleNode->is('active')) {
                $result[] = $moduleNode->getName();
            }
        }
        return $result;
    }
}
