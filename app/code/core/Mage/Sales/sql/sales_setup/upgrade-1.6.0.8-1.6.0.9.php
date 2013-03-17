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

/* @var $installer Mage_Sales_Model_Entity_Setup */
$installer = $this;
$installer->startSetup();

$installer->run("

ALTER TABLE {$installer->getTable('sales/billing_agreement')}
  MODIFY COLUMN `created_at` TIMESTAMP NULL DEFAULT NULL;

ALTER TABLE {$installer->getTable('sales/order_item')}
  MODIFY COLUMN `created_at` TIMESTAMP NULL DEFAULT NULL,
  MODIFY COLUMN `updated_at` TIMESTAMP NULL DEFAULT NULL;

ALTER TABLE {$installer->getTable('sales/quote')}
  MODIFY COLUMN `created_at` TIMESTAMP NULL DEFAULT NULL,
  MODIFY COLUMN `updated_at` TIMESTAMP NULL DEFAULT NULL;

ALTER TABLE {$installer->getTable('sales/quote_address')}
  MODIFY COLUMN `created_at` TIMESTAMP NULL DEFAULT NULL,
  MODIFY COLUMN `updated_at` TIMESTAMP NULL DEFAULT NULL;

ALTER TABLE {$installer->getTable('sales/quote_address_item')}
  MODIFY COLUMN `created_at` TIMESTAMP NULL DEFAULT NULL,
  MODIFY COLUMN `updated_at` TIMESTAMP NULL DEFAULT NULL;

ALTER TABLE {$installer->getTable('sales/quote_item')}
  MODIFY COLUMN `created_at` TIMESTAMP NULL DEFAULT NULL,
  MODIFY COLUMN `updated_at` TIMESTAMP NULL DEFAULT NULL;

ALTER TABLE {$installer->getTable('sales/quote_payment')}
  MODIFY COLUMN `created_at` TIMESTAMP NULL DEFAULT NULL,
  MODIFY COLUMN `updated_at` TIMESTAMP NULL DEFAULT NULL;

ALTER TABLE {$installer->getTable('sales/quote_address_shipping_rate')}
  MODIFY COLUMN `created_at` TIMESTAMP NULL DEFAULT NULL,
  MODIFY COLUMN `updated_at` TIMESTAMP NULL DEFAULT NULL;

ALTER TABLE {$installer->getTable('sales/recurring_profile')}
  MODIFY COLUMN `created_at` TIMESTAMP NULL DEFAULT NULL;

");

$installer->endSetup();