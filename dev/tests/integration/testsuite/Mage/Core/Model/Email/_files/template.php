<?php
/**
 * {license_notice}
 *
 * @category    Magento
 * @package     Mage_Core
 * @subpackage  integration_tests
 * @copyright   {copyright}
 * @license     {license_link}
 */

$template = new Mage_Core_Model_Email_Template();
$template->setTemplateCode('customer_password_forgot_email_template');
$template->setTemplateText('<strong>Your new password is:</strong> {{htmlescape var=$customer.password}}');
$template->setTemplateType(Mage_Core_Model_Email_Template::TYPE_HTML);
$template->setTemplateSubject('New password for {{var customer.name}}');
$template->setStores(array(Mage_Core_Model_App::ADMIN_STORE_ID));
$template->save();
