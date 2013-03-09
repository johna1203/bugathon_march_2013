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
 * Factory for helpers, used in Magento testing framework
 */
class Magento_Test_Helper_Factory
{
    /**
     * @var array
     */
    static protected $_instances = array();

    /**
     * Retrieves singleton instance of helper
     *
     * @param string $name
     * @return mixed
     */
    static public function getHelper($name)
    {
        if (!isset(self::$_instances[$name])) {
            $className = preg_replace('/[^_]*$/', ucfirst($name), __CLASS__, 1);
            self::$_instances[$name] = new $className();
        }
        return self::$_instances[$name];
    }

    /**
     * Sets custom helper instance to be used for specific name, or null to clear instance.
     * Returns previous instance (if any) or null (if no helper was defined).
     *
     * @param string $name
     * @param mixed $helper
     * @return mixed
     */
    static public function setHelper($name, $helper)
    {
        $old = isset(self::$_instances[$name]) ? self::$_instances[$name] : null;
        self::$_instances[$name] = $helper;
        return $old;
    }
}
