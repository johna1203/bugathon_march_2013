<?php
/**
 * {license_notice}
 *
 * @category    Magento
 * @package     Test
 * @subpackage  integration_tests
 * @copyright   {copyright}
 * @license     {license_link}
 */

/**
 * An ancestor class for integrity tests
 */
abstract class Magento_Test_TestCase_IntegrityAbstract extends PHPUnit_Framework_TestCase
{
    /**
     * Cached index of enabled modules
     *
     * @var array
     */
    protected $_enabledModules = null;

    /**
     * Returns array of enabled modules
     *
     * @return array
     */
    protected function _getEnabledModules()
    {
        if ($this->_enabledModules === null) {
            /** @var $helper Magento_Test_Helper_Config */
            $helper = Magento_Test_Helper_Factory::getHelper('config');
            $enabledModules = $helper->getEnabledModules();
            $this->_enabledModules = array_combine($enabledModules, $enabledModules);
        }
        return $this->_enabledModules;
    }

    /**
     * Checks resource file declaration - whether it is for disabled module (e.g. 'Disabled_Module::file.ext').
     *
     * @param string $file
     * @return bool
     */
    protected function _isFileForDisabledModule($file)
    {
        $enabledModules = $this->_getEnabledModules();
        if (preg_match('/^(.*)::/', $file, $matches)) {
            $module = $matches[1];
            if (!isset($enabledModules[$module])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns flat array of skins currently located in system
     *
     * @return array
     */
    protected function _getDesignSkins()
    {
        $result = array();
        $areas = array('adminhtml', 'frontend', 'install');
        foreach ($areas as $area) {
            $entities = Mage::getDesign()->getDesignEntitiesStructure($area, false);
            foreach ($entities as $package => $themes) {
                foreach ($themes as $theme => $skins) {
                    foreach (array_keys($skins) as $skin) {
                        $result[] = "{$area}/{$package}/{$theme}/{$skin}";
                    }
                }
            }
        }
        return $result;
    }

    /**
     * Returns design themes, present in system
     *
     * @return array
     */
    protected function _getDesignThemes()
    {
        $skins = $this->_getDesignSkins();
        $result = array();
        foreach ($skins as $skin) {
            list ($area, $package, $theme) = explode('/', $skin);
            $view = "{$area}/{$package}/{$theme}";
            $result[$view] = $view;
        }
        $result = array_values($result); // Return flat array without some special keys
        return $result;
    }
}
