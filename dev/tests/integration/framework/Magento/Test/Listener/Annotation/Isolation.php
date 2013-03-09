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

/**
 * Implementation of the @magentoAppIsolation doc comment directive
 */
class Magento_Test_Listener_Annotation_Isolation
{
    /**
     * @var Magento_Test_Listener
     */
    protected $_listener;

    /**
     * Flag to prevent an excessive test case isolation if the last test has been just isolated
     *
     * @var bool
     */
    private $_hasNonIsolatedTests = true;

    /**
     * Constructor
     *
     * @param Magento_Test_Listener $listener
     */
    public function __construct(Magento_Test_Listener $listener)
    {
        $this->_listener = $listener;
    }

    /**
     * Isolate global application objects
     */
    protected function _isolateApp()
    {
        if ($this->_hasNonIsolatedTests) {
            Magento_Test_Bootstrap::getInstance()->cleanupCache();
            Magento_Test_Bootstrap::getInstance()->initialize();
            $this->_hasNonIsolatedTests = false;
        }
    }

    /**
     * Isolate application before running test case
     */
    public function startTestSuite()
    {
        $this->_isolateApp();
    }

    /**
     * Handler for 'endTest' event
     */
    public function endTest()
    {
        $test = $this->_listener->getCurrentTest();

        $this->_hasNonIsolatedTests = true;

        /* Determine an isolation from doc comment */
        $annotations = $test->getAnnotations();
        if (isset($annotations['method']['magentoAppIsolation'])) {
            $isolation = $annotations['method']['magentoAppIsolation'];
            if ($isolation !== array('enabled') && $isolation !== array('disabled')) {
                throw new Exception('Invalid "@magentoAppIsolation" annotation, can be "enabled" or "disabled" only.');
            }
            $isIsolationEnabled = ($isolation === array('enabled'));
        } else {
            /* Controller tests should be isolated by default */
            $isIsolationEnabled = ($test instanceof Magento_Test_TestCase_ControllerAbstract);
        }

        if ($isIsolationEnabled) {
            $this->_isolateApp();
        }
    }
}
