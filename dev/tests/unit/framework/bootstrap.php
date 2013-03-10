<?php
/**
 * {license_notice}
 *
 * @category    Magento
 * @package     unit_tests
 * @copyright   {copyright}
 * @license     {license_link}
 */

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

$includePaths = array(
    get_include_path(),
    dirname(__FILE__) . '/../testsuite',
    dirname(__FILE__) . '/../../../../lib',
    dirname(__FILE__) . '/../../../../app/code/core',
    dirname(__FILE__) . '/../../../../app/'
);
set_include_path(implode(PATH_SEPARATOR, $includePaths));

spl_autoload_register('magentoAutoloadForUnitTests');

function magentoAutoloadForUnitTests($class)
{
    $file = str_replace('_', '/', $class) . '.php';
    require_once $file;
}
