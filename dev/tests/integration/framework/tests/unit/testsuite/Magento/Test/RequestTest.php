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

class Magento_Test_RequestTest extends PHPUnit_Framework_TestCase
{
    public function testGetHttpHost()
    {
        $request = new Magento_Test_Request();
        $this->assertEquals('localhost', $request->getHttpHost());
        $this->assertEquals('localhost', $request->getHttpHost(false));
    }
}

