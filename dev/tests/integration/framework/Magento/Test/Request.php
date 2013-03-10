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
 * HTTP request implementation that is used instead core one for testing
 */
class Magento_Test_Request extends Mage_Core_Controller_Request_Http
{
    /**
     * Retrieve HTTP HOST.
     * This method is a stub - all parameters are ignored, just static value returned.
     *
     * @param bool $trimPort
     * @return string
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getHttpHost($trimPort = true)
    {
        return 'localhost';
    }
}
