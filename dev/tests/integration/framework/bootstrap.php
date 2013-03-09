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

ini_set('display_errors', true);
error_reporting(E_ALL | E_STRICT);

require dirname(__FILE__) . '/Magento/Test/Bootstrap.php';

$baseDir = dirname(dirname(__FILE__));

/*
 * Setup include path for autoload purpose.
 * Include path setup is intentionally moved out from the phpunit.xml to simplify maintenance of CI builds.
 */
set_include_path(implode(
    PATH_SEPARATOR,
    array(
        "$baseDir/framework",
        get_include_path()
    )
));

if (defined('TESTS_SHUTDOWN_METHOD') && TESTS_SHUTDOWN_METHOD) {
    Magento_Test_Bootstrap::setShutdownAction(TESTS_SHUTDOWN_METHOD);
}

if (defined('TESTS_LOCAL_CONFIG_FILE') && TESTS_LOCAL_CONFIG_FILE) {
    $localXmlFile = "$baseDir/" . TESTS_LOCAL_CONFIG_FILE;
    if (!is_file($localXmlFile) && substr($localXmlFile, -5) != '.dist') {
        $localXmlFile .= '.dist';
    }
} else {
    $localXmlFile = "$baseDir/etc/local-mysql.xml";
}

if (defined('TESTS_GLOBAL_CONFIG_FILES') && TESTS_GLOBAL_CONFIG_FILES) {
    $globalEtcFiles = TESTS_GLOBAL_CONFIG_FILES;
} else {
    $globalEtcFiles = "../../../app/etc/*.xml";
}
$globalEtcFiles .= ';etc/integration-tests-config.xml';

if (defined('TESTS_MODULE_CONFIG_FILES') && TESTS_MODULE_CONFIG_FILES) {
    $moduleEtcFiles = TESTS_MODULE_CONFIG_FILES;
} else {
    $moduleEtcFiles = "../../../app/etc/modules/*.xml";
}

Magento_Test_Bootstrap::setInstance(new Magento_Test_Bootstrap(
    realpath("$baseDir/../../../"),
    $localXmlFile,
    $globalEtcFiles,
    $moduleEtcFiles,
    "$baseDir/tmp"
));

/* Enable profiler if necessary */
if (defined('TESTS_PROFILER_FILE') && TESTS_PROFILER_FILE) {
    Magento_Profiler::registerOutput(
        new Magento_Profiler_Output_Csvfile($baseDir . DIRECTORY_SEPARATOR . TESTS_PROFILER_FILE)
    );
}

/* Enable profiler with bamboo friendly output format */
if (defined('TESTS_BAMBOO_PROFILER_FILE') && defined('TESTS_BAMBOO_PROFILER_METRICS_FILE')) {
    Magento_Profiler::registerOutput(new Magento_Test_Profiler_OutputBamboo(
        $baseDir . DIRECTORY_SEPARATOR . TESTS_BAMBOO_PROFILER_FILE,
        require($baseDir . DIRECTORY_SEPARATOR . TESTS_BAMBOO_PROFILER_METRICS_FILE)
    ));
}

/* Activate custom annotations in doc comments */
/*
 * Note: order of registering (and applying) annotations is important.
 * To allow config fixtures to deal with fixture stores, data fixtures should be processed before config fixtures.
 */
Magento_Test_Listener::registerObserver('Magento_Test_Listener_Annotation_Isolation');
Magento_Test_Listener::registerObserver('Magento_Test_Listener_Annotation_Fixture');
Magento_Test_Listener::registerObserver('Magento_Test_Listener_Annotation_Config');

/* Unset declared global variables to release PHPUnit from maintaining their values between tests */
unset($baseDir, $localXmlFile, $globalEtcFiles, $moduleEtcFiles);
