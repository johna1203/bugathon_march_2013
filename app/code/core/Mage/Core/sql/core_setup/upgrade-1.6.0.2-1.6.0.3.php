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
 * @package     Mage_Core
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->getConnection()->dropIndex(
    $installer->getTable('core/email_template'),
    $installer->getIdxName('core/email_template', array('template_code'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
);

$installer->getConnection()->addIndex(
    $installer->getTable('core/email_template'),
    $installer->getIdxName('core/email_template', array('template_code')),
    array('template_code')
);

/**
 * Create table 'core/email_template_store'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('core/email_template_store'))
    ->addColumn('template_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Template ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Store ID')
    ->addIndex($installer->getIdxName('core/email_template_store', array('store_id')),
        array('store_id'))
    ->addForeignKey($installer->getFkName('core/email_template_store', 'template_id', 'core/email_template', 'template_id'),
        'template_id', $installer->getTable('core/email_template'), 'template_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('core/email_template_store', 'store_id', 'core/store', 'store_id'),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Email Template To Store Linkage Table');
$installer->getConnection()->createTable($table);

$installer->endSetup();
