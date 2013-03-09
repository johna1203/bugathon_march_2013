<?php
/**
 * {license_notice}
 *
 * @category    Magento
 * @package     Magento
 * @subpackage  integration_tests
 * @copyright   {copyright}
 * @license     {license_link}
 */

$rootDir = realpath(dirname(__FILE__) . '/../../../../../../../');
$codeDirs = array(
    $rootDir . '/lib/',
    $rootDir . '/app/', // locate Mage.php
    $rootDir . '/app/code/core/',
    $rootDir . '/dev/tests/integration/framework/',
);

set_include_path(implode(PATH_SEPARATOR, $codeDirs) . PATH_SEPARATOR . get_include_path());

function magentoAutoloadForIntegrationTests($class)
{
    $file = str_replace('_', '/', $class) . '.php';
    require_once $file;
}

spl_autoload_register('magentoAutoloadForIntegrationTests');

