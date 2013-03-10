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
 * HTTP response implementation that is used instead core one for testing
 */
class Magento_Test_Response extends Mage_Core_Controller_Response_Http
{
    /**
     * Redefined in order to prevent exception in parent class.
     * All functionality that depend on headers validation should be covered with unit tests by mocking response.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function canSendHeaders($throw = false)
    {
        return true;
    }

    public function sendResponse()
    {
        Mage::dispatchEvent('http_response_send_before', array('response'=>$this));
    }
}
