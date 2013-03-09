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


/**
 * Template db resource
 *
 * @category    Mage
 * @package     Mage_Core
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Core_Model_Resource_Email_Template extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize email template resource model
     *
     */
    protected function _construct()
    {
        $this->_init('core/email_template', 'template_id');
    }

    /**
     * Process template data before deleting
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Mage_Core_Model_Resource_Email_Template
     */
    protected function _beforeDelete(Mage_Core_Model_Abstract $object)
    {
        $condition = array(
            'template_id = ?' => (int) $object->getId(),
        );

        $this->_getWriteAdapter()->delete($this->getTable('core/email_template_store'), $condition);

        return parent::_beforeDelete($object);
    }

    /**
     * Assign email template to store views
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Mage_Core_Model_Resource_Email_Template
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array) $object->getStores();
        if (empty($newStores)) {
            $newStores = (array) $object->getStoreId();
        }
        $table  = $this->getTable('core/email_template_store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);

        if ($delete) {
            $where = array(
                'template_id = ?' => (int) $object->getId(),
                'store_id IN (?)' => $delete
            );

            $this->_getWriteAdapter()->delete($table, $where);
        }

        if ($insert) {
            $data = array();

            foreach ($insert as $storeId) {
                $data[] = array(
                    'template_id' => (int) $object->getId(),
                    'store_id' => (int) $storeId
                );
            }

            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }

        return parent::_afterSave($object);
    }

    /**
     * Perform operations after object load
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Mage_Core_Model_Resource_Email_Template
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());

            $object->setData('store_id', $stores);
        }

        return parent::_afterLoad($object);
    }

    /**
     * Retrieve load select with filter by template code and store.
     *
     * @param string $templateCode
     * @param int|array $store
     * @return Varien_Db_Select
     */
    protected function _getLoadByCodeSelect($templateCode, $store)
    {
        $select = $this->_getReadAdapter()->select()
            ->from(array('t' => $this->getMainTable()))
            ->join(
                array('ts' => $this->getTable('core/email_template_store')),
                't.template_id = ts.template_id',
                array())
            ->where('t.template_code = ?', $templateCode)
            ->where('ts.store_id IN (?)', $store);

        return $select;
    }

    /**
     * Load by template code from DB.
     *
     * @param string $templateCode
     * @param int|array $store
     * @return array
     */
    public function loadByCode($templateCode, $store = Mage_Core_Model_App::ADMIN_STORE_ID)
    {
        $select = $this->_getLoadByCodeSelect($templateCode, $store);
        $result = $this->_getReadAdapter()->fetchRow($select);

        if (!$result) {
            return array();
        }
        return $result;
    }

    /**
     * Check usage of template code in other templates
     *
     * @param Mage_Core_Model_Email_Template $template
     * @return boolean
     */
    public function checkCodeUsage(Mage_Core_Model_Email_Template $template)
    {
        if ($template->getTemplateActual() != 0 || is_null($template->getTemplateActual())) {

            if (Mage::app()->isSingleStoreMode() || !$template->hasStores()) {
                $stores = array(Mage_Core_Model_App::ADMIN_STORE_ID);
            } else {
                $stores = (array) $template->getData('stores');
            }

            $select = $this->_getLoadByCodeSelect($template->getData('template_code'), $stores);

            $templateId = $template->getId();
            if ($templateId) {
                $select->where('t.template_id != ?', $template->getId());
            }

            if ($this->_getWriteAdapter()->fetchRow($select)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $templateId
     * @return array
     */
    public function lookupStoreIds($templateId)
    {
        $adapter = $this->_getReadAdapter();

        $select  = $adapter->select()
            ->from($this->getTable('core/email_template_store'), 'store_id')
            ->where('template_id = ?',(int)$templateId);

        return $adapter->fetchCol($select);
    }

    /**
     * Set template type, added at and modified at time
     *
     * @param Mage_Core_Model_Email_Template $object
     * @return Mage_Core_Model_Resource_Email_Template
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        if ($object->isObjectNew()) {
            $object->setCreatedAt($this->formatDate(true));
        }
        $object->setModifiedAt($this->formatDate(true));
        $object->setTemplateType((int)$object->getTemplateType());

        return parent::_beforeSave($object);
    }

    /**
     * Retrieve config scope and scope id of specified email template by email pathes
     *
     * @param array $paths
     * @param int|string $templateId
     * @return array
     */
    public function getSystemConfigByPathsAndTemplateId($paths, $templateId)
    {
        $orWhere = array();
        $pathesCounter = 1;
        $bind = array();
        foreach ($paths as $path) {
            $pathAlias = 'path_' . $pathesCounter;
            $orWhere[] = 'path = :' . $pathAlias;
            $bind[$pathAlias] = $path;
            $pathesCounter++;
        }
        $bind['template_id'] = $templateId;
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('core/config_data'), array('scope', 'scope_id', 'path'))
            ->where('value LIKE :template_id')
            ->where(join(' OR ', $orWhere));

        return $this->_getReadAdapter()->fetchAll($select, $bind);
    }
}
