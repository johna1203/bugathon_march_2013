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
 * CatalogSearch tests
 *
 * @package     selenium
 * @subpackage  tests
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Core_Mage_CatalogSearch_CatalogSearchTest extends Mage_Selenium_TestCase
{
    /**
     * Create a bunch of visible simple products and specify a random
     * search query.
     *
     * @return array
     * @test
     * @skipTearDown
     */
    public function preconditionsForTests()
    {
        $searchQuery = $this->generate('string', 20);

        $this->loginAdminUser();
        $this->navigate('manage_products');

        for ($i=0; $i<=15; $i++) {
            $name = $searchQuery.' Simple Product '.$i;
            $sku = strtolower($searchQuery).'_simple_sku_'.$i;

            $simple = $this->loadDataSet(
                'Products',
                'simple_product_visible',
                array(
                    'general_name' => $name,
                    'general_sku' => $sku
                )
            );
            $this->productHelper()->createProduct($simple);
            $this->assertMessagePresent('success', 'success_saved_product');
        }

        $this->reindexAllData();
        $this->flushCache();

        return array(
            'searchQuery' => $searchQuery,
            'productName' => $searchQuery.' Simple Product 9'
        );
    }

    /**
     * Add a product from the second page of a catalogsearch result to
     * compare product list.
     *
     * @param array $data
     * @test
     * @depends preconditionsForTests
     */
    public function addProductToCompareFromSecondCatalogSeachPage($data)
    {
        // Data
        $searchQuery = $data['searchQuery'];
        $productName = $data['productName'];

        // Steps
        $this->frontend();
        $this->fillField('quick_search_input', $searchQuery);
        $this->addParameter('searchQuery', $searchQuery);
        $this->clickButton('quick_search_submit');

        $this->addParameter('pageNumber', 2);
        $this->assertTrue($this->controlIsVisible('link', 'page_number'), 'Page 2 does not exist in pagination.');
        $this->clickControl('link', 'page_number');

        $this->addParameter('productName', $productName);
        $this->assertTrue($this->controlIsVisible('fieldset', 'catalogsearch_product'));

        $this->assertTrue($this->controlIsVisible('link', 'add_to_compare'));
        $this->clickControl('link', 'add_to_compare');

        $this->addParameter('productName', $productName);
        $this->assertTrue($this->controlIsVisible('fieldset', 'catalogsearch_product'));
    }
}
