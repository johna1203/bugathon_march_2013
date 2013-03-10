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
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Adminhtml store tree
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Adminhtml_Block_System_Store_Tree extends Mage_Adminhtml_Block_Widget
{

    /**
     * Constructor
     */
    public function _construct()
    {
        $this->setTemplate('system/store/tree.phtml');
        parent::_construct();
    }

    /**
     * Get table data
     *
     * @return array
     */
    public function getTableData()
    {
        $data = array();
        foreach (Mage::getModel('core/website')->getCollection() as $website) { /* @var $website Mage_Core_Model_Website */
            $data[$website->getId()] = array(
                '_object' => $website,
                '_storeGroups' => array()
            );
            foreach ($website->getGroupCollection() as $storeGroup) { /* @var $storeGroup Mage_Core_Model_Store_Group */
                $data[$website->getId()]['_storeGroups'][$storeGroup->getId()] = array(
                    '_object' => $storeGroup,
                    '_stores' => array()
                );
                foreach ($storeGroup->getStoreCollection() as $store) { /* @var $store Mage_Core_Model_Store */
                    $data[$website->getId()]['_storeGroups'][$storeGroup->getId()]['_stores'][$store->getId()] = array(
                        '_object' => $store
                    );
                }
            }
        }

        // set default store groups and stores
        foreach ($data as $websiteId => $webSiteData) {
            $website = $webSiteData['_object']; /* @var $website Mage_Core_Model_Website */

            $defaultGroupId = $website->getDefaultGroupId();

            if ($defaultGroupId) {
                $defaultStoreGroup = $data[$websiteId]['_storeGroups'][$defaultGroupId]['_object']; /* @var $defaultStoreGroup Mage_Core_Model_Store_Group */
                $defaultStoreGroup->setData('is_default_in_website', true);
            }

            foreach ($data[$websiteId]['_storeGroups'] as $storeGroupId => $storeGroupData) {
                $storeGroup = $storeGroupData['_object']; /* @var $storeGroup Mage_Core_Model_Store_Group */
                $defaultStoreId = $storeGroup->getDefaultStoreId();
                if ($defaultStoreId) {
                    $defaultStore = $data[$websiteId]['_storeGroups'][$storeGroupId]['_stores'][$defaultStoreId]['_object']; /* @var $defaultStore Mage_Core_Model_Store */
                    $defaultStore->setData('is_default_in_storegroup', true);
                }
            }
        }

        // update counts
        foreach ($data as $websiteId => $webSiteData) {
            $data[$websiteId]['_count'] = 0;
            foreach ($data[$websiteId]['_storeGroups'] as $storeGroupId => $storeGroupData) {
                $storeGroupCount = max(1, count($data[$websiteId]['_storeGroups'][$storeGroupId]['_stores']));
                $data[$websiteId]['_storeGroups'][$storeGroupId]['_count'] = $storeGroupCount;
                $data[$websiteId]['_count'] += $storeGroupCount;
            }
            $data[$websiteId]['_count'] = max(1, $data[$websiteId]['_count']);
        }

        return $data;
    }

    /**
     * Render website cell
     *
     * @param Mage_Core_Model_Website $website
     * @return string
     */
    public function renderWebsiteCell(Mage_Core_Model_Website $website)
    {
        $result = '<a title="Id: ' . $website->getId() . '" href="' . $this->getUrl('*/*/editWebsite', array('website_id' => $website->getWebsiteId())) . '">' . $website->getName() . '</a>';
        if ($website->getIsDefault()) {
            $result = '<strong>' . $result . '</strong>';
        }
        $result .= ' <br /><span class="additional-info">(' . $this->__('ID') . ': ' . $website->getId() . ' / ' . $this->__('Code') . ': ' . $website->getCode() . ')</span>';
        return $result;
    }

    /**
     * Render store group cell
     *
     * @param Mage_Core_Model_Store_Group $storeGroup
     * @return string
     */
    public function renderStoreGroupCell(Mage_Core_Model_Store_Group $storeGroup)
    {
        $result = '<a title="Id: ' . $storeGroup->getId() . '" href="' . $this->getUrl('*/*/editGroup', array('group_id' => $storeGroup->getGroupId())) . '">' . $storeGroup->getName() . '</a>';
        if ($storeGroup->getData('is_default_in_website')) {
            $result = '<strong>' . $result . '</strong>';
        }

        $rootCategory = Mage::getModel('catalog/category')->load($storeGroup->getRootCategoryId());

        $result .= ' <br /><span class="additional-info">(' . $this->__('ID') . ': ' . $storeGroup->getId() . ' / ' . $this->__('Root Category') . ': ' . $rootCategory->getName() . ')</span>';
        return $result;
    }

    /**
     * Render store cell
     *
     * @param Mage_Core_Model_Store $store
     * @return string
     */
    public function renderStoreCell(Mage_Core_Model_Store $store)
    {
        $result = '<a title="Id: ' . $store->getId() . '" href="' . $this->getUrl('*/*/editStore', array('store_id' => $store->getStoreId())) . '">' . $store->getName() . '</a>';
        if (!$store->getIsActive()) {
            $result = '<strike>' . $result . '</strike>';
        }
        if ($store->getData('is_default_in_storegroup')) {
            $result = '<strong>' . $result . '</strong>';
        }
        $result .= ' <br /><span class="additional-info">(' . $this->__('ID') . ': ' . $store->getId() . ' / ' . $this->__('Code') . ': ' . $store->getCode() . ')</span>';
        return $result;
    }

}
