<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    tests
 * @package     selenium
 * @subpackage  tests
 * @author      Magento Core Team <core@magentocommerce.com>
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Impossible to save payment method configurations on the Default Config scope - MAGE-5774
 *
 * @package     selenium
 * @subpackage  tests
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Core_Mage_Various_ContactUsTest extends Mage_Selenium_TestCase
{
    /**
     * <p>Verifying that cannot save Contacts->Email Options without entering email address.</p>
     * <p>Test for solution of MAGE-4590</p>
     *
     * @test
     */
    public function contactUs()
    {
        //Data
        $email = $this->loadDataSet('Contacts', 'contacts_empty_email');
        //Steps
        $this->loginAdminUser();
        $this->navigate('system_configuration');
        $this->systemConfigurationHelper()->openConfigurationTab('general_contacts');
        $xpath = $this->_getControlXpath('link', 'email_options_link');
        if (!$this->isElementPresent($xpath . "[@class='open']")) {
            $this->clickControl('link', 'email_options_link', false);
        }
        $this->fillField('send_emails_to', $email['send_emails_to']);
        $this->clickButton('save_config', false);
        //Verify
        $this->assertMessagePresent('validation', 'required_field');
    }

    public function tearDownAfterTestClass()
    {
        $this->loginAdminUser();
        $this->navigate('system_configuration');
        $this->systemConfigurationHelper()->configure('Contacts/contacts_default');
    }
}