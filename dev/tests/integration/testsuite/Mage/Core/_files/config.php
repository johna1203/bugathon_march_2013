<?php


$numberOfWebsites = 2;
$numberOfStoreGroupsPerWebsite = 2;
$numberOfStoresPerStoreGroup = 2;

$websites = array();
$stores = array();

for ($websiteNumber = 0; $websiteNumber < $numberOfWebsites; $websiteNumber++) {
    $websiteCode = 'ws_' . $websiteNumber;
    $website = createWebsite(
        $websiteCode,
        'Website #' . $websiteNumber
    );
    $websites[$websiteCode] = $website;
    for ($storeGroupNumber = 0; $storeGroupNumber < $numberOfStoreGroupsPerWebsite; $storeGroupNumber++) {
        $storeGroup = createStoreGroupForWebsite(
            $website,
            'Store group #' . $websiteNumber . '.' . $storeGroupNumber
        );
        for ($storeNumber = 0; $storeNumber < $numberOfStoresPerStoreGroup; $storeNumber++) {
            $storeCode = 'store_' . $websiteNumber . '_' . $storeGroupNumber . '_' . $storeNumber;
            $store = createStoreForStoreGroup(
                $storeGroup,
                'Store #' . $websiteNumber . '.' . $storeGroupNumber . '.' . $storeNumber,
                $storeCode
            );
            $stores[$storeCode] = $store;
        }
    }
}

/*
 * ws_0
     store_0_0_1
     store_0_1_0
     store_0_0_1
     store_0_1_1
 ws_1
     store_1_0_1
     store_1_1_0
     store_1_0_1
     store_1_1_1
 */

$config = Mage::getConfig();
$config->saveConfig('aaa/bbb/ccc', '2', 'default');
$config->saveConfig('aaa/bbb/ccc', '3', 'websites', $websites['ws_0']->getId());
$config->saveConfig('aaa/bbb/ccc', '4', 'stores', $stores['store_0_0_1']->getId());
$config->saveConfig('aaa/bbb/ccc', '5', 'websites', $websites['ws_1']->getId());

$config->removeCache();

unset($websites, $stores);


/**
 * @param $websiteCode
 * @param $websiteName
 * @return Mage_Core_Model_Website
 */
function createWebsite($websiteCode, $websiteName) {
        $website = Mage::getModel('core/website');
    /* @var $website Mage_Core_Model_Website */
    $website->load($websiteCode); // try loading existing store first
    $website->setCode($websiteCode)
        ->setName($websiteName)
        ->save();
    return $website;
}

/**
 * @param Mage_Core_Model_Website $website
 * @param $storeGroupName
 * @param int $categoryRootId
 * @return Mage_Core_Model_Store_Group
 */
function createStoreGroupForWebsite(Mage_Core_Model_Website $website, $storeGroupName, $categoryRootId = 3) {
    $storeGroup = Mage::getModel('core/store_group');
    /* @var $storeGroup Mage_Core_Model_Store_Group */
    $storeGroup->setWebsiteId($website->getId())
        ->setName($storeGroupName)
        ->setRootCategoryId($categoryRootId)
        ->save();
    return $storeGroup;
}

/**
 * Create store
 *
 * @param Mage_Core_Model_Store_Group $storeGroup
 * @param $storeName
 * @param $storeCode
 * @return Mage_Core_Model_Store
 */
function createStoreForStoreGroup(Mage_Core_Model_Store_Group $storeGroup, $storeName, $storeCode) {
    $store = Mage::getModel('core/store');
    /* @var $store Mage_Core_Model_Store */
    $store->setCode($storeCode)
        ->setWebsiteId($storeGroup->getWebsiteId())
        ->setGroupId($storeGroup->getId())
        ->setName($storeName)
        ->setIsActive(1)
        ->save();
    return $store;
}
