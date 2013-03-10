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
 * @category    Mage
 * @package     Mage_Sales
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$configValuesMap = array(
    'catalog/productalert/email_stock_template'         => 'catalog_productalert_email_stock_template',
    'catalog/productalert/email_price_template'         => 'catalog_productalert_email_price_template',
    'catalog/productalert_cron/error_email_template'    => 'catalog_productalert_cron_error_email_template',
    'contacts/email/email_template'                     => 'contacts_email_email_template',
    'currency/import/error_email_template'              => 'currency_import_error_email_template',
    'checkout/payment_failed/template'                  => 'checkout_payment_failed_template',
    'admin/emails/forgot_email_template'                => 'admin_emails_forgot_email_template',
    'customer/create_account/email_template'            => 'customer_create_account_email_template',
    'customer/create_account/email_confirmation_template' => 'customer_create_account_email_confirmation_template',
    'customer/create_account/email_confirmed_template'  => 'customer_create_account_email_confirmed_template',
    'customer/password/forgot_email_template'           => 'customer_password_forgot_email_template',
    'customer/password/remind_email_template'           => 'customer_password_remind_email_template',
    'customer/password_forgot/email_template'           => 'customer_password_forgot_email_template',
    'newsletter/subscription/confirm_email_template'    => 'newsletter_subscription_confirm_email_template',
    'newsletter/subscription/success_email_template'    => 'newsletter_subscription_success_email_template',
    'newsletter/subscription/un_email_template'         => 'newsletter_subscription_un_email_template',
    'sales_email/order/template'                        => 'sales_email_order_template',
    'sales_email/order/guest_template'                  => 'sales_email_order_guest_template',
    'sales_email/order_comment/template'                => 'sales_email_order_comment_template',
    'sales_email/order_comment/guest_template'          => 'sales_email_order_comment_guest_template',
    'sales_email/invoice/template'                      => 'sales_email_invoice_comment_template',
    'sales_email/invoice/guest_template'                => 'sales_email_invoice_comment_guest_template',
    'sales_email/invoice_comment/template'              => 'sales_email_invoice_comment_template',
    'sales_email/invoice_comment/guest_template'        => 'sales_email_invoice_comment_guest_template',
    'sales_email/creditmemo/template'                   => 'sales_email_creditmemo_template',
    'sales_email/creditmemo/guest_template'             => 'sales_email_creditmemo_guest_template',
    'sales_email/creditmemo_comment/template'           => 'sales_email_creditmemo_comment_template',
    'sales_email/creditmemo_comment/guest_template'     => 'sales_email_creditmemo_comment_guest_template',
    'sales_email/shipment/template'                     => 'sales_email_shipment_template',
    'sales_email/shipment/guest_template'               => 'sales_email_shipment_guest_template',
    'sales_email/shipment_comment/template'             => 'sales_email_shipment_comment_template',
    'sales_email/shipment_comment/guest_template'       => 'sales_email_shipment_comment_guest_template',
    'sendfriend/email/template'                         => 'sendfriend_email_template',
    'system/log/error_email_template'                   => 'system_log_error_email_template',
    'sitemap/generate/error_email_template'             => 'sitemap_generate_error_email_template',
    'wishlist/email/email_template'                     => 'wishlist_email_email_template',
    'oauth/email/template'                              => 'oauth_email_template',
);

$select = $installer->getConnection()->select()
    ->from($installer->getTable('core/config_data'))
    ->where('path IN (?)', array_keys($configValuesMap))
    ->order(array(
        new Zend_Db_Expr("FIELD(`scope`, 'stores', 'websites', 'default')")
    ));

$configs = $installer->getConnection()->fetchAll($select);

$usedTemplates = array();

foreach ($configs as $config) {
    if (!is_numeric($config['value'])) {
        continue;
    }

    // Mapping Stores
    $stores = array();
    if ($config['scope'] === 'stores') {
        $stores[] = $config['scope_id'];
    } else if ($config['scope'] === 'websites') {
        $website = Mage::getModel('core/website')->load($config['scope_id']);
        foreach ($website->getGroups() as $group) {
            foreach ($group->getStores() as $store) {
                $stores[] = $store->getId();
            }
        }
    } else if ($config['scope'] === 'default') {
        $stores[] = Mage_Core_Model_App::ADMIN_STORE_ID;
    }

    // Save Template
    foreach ($stores as $store) {
        try {
            $model = Mage::getModel('core/email_template')->load($config['value']);
            
            if (!$model->getId()) {
                // skip non-existing templates
                continue;
            }

            $storeIds = $model->getStoreId();
            if (in_array($config['value'], $usedTemplates)) {
                $model->setTemplateId(null);
                $storeIds = array();
            }

            $code = isset($configValuesMap[$config['path']]) ? $configValuesMap[$config['path']] : 'foobar';
            $model->setTemplateCode($code);

            $storeIds[] = $store;
            $model->setStores($storeIds);
            $model->save();
        } catch (Mage_Core_Exception $e) {
            // ignore conflicts, handled by order of scope in SQL select
        }
    }

    $usedTemplates[] = $config['value'];

    // remove old config setting
    $installer->getConnection()->delete(
        $installer->getTable('core/config_data'),
        array('id = ?' => $config['id'])
    );
}
