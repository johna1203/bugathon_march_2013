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
        //Data
        $searchQuery = $this->generate('string', 20);
        //Steps
        $this->loginAdminUser();
        $this->navigate('manage_products');
        //Creating products
        for ($i = 0; $i <= 9; $i++) {
            $name = $searchQuery . ' Simple Product ' . $i;
            $sku = strtolower($searchQuery) . '_simple_sku_' . $i;
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
            'productName' => $searchQuery . ' Simple Product 9'
        );
    }

    /**
     * Add a product from the second page of a catalogsearch result to
     * compare product list.
     *
     * @param array $data
     *
     * @test
     * @depends preconditionsForTests
     */
    public function addProductToCompareFromSecondCatalogSeachPage(array $data)
    {
        //Data
        $searchQueryData = $data['searchQuery'];
        $productName = $data['productName'];
        $searchQuery = 'q=' . $searchQueryData;
        $customSearch = '?';
        //Steps
        $this->frontend();
        $this->fillField('quick_search_input', $searchQueryData);
        $this->addParameter('searchQuery', $searchQueryData);
        $this->clickButton('quick_search_submit');
        if ($this->getControlAttribute('dropdown', 'show_per_page', 'selectedValue') != '9') {
            $customSearch =  $customSearch . '&limit=9&';
            $this->addParameter('customSearch', $customSearch . $searchQuery);
            $this->fillDropdown('show_per_page', 9);
        }
        if ($this->getControlAttribute('dropdown', 'sort_by', 'selectedValue') != 'name') {
            $customSearch .= 'order=name&';
            $this->addParameter('customSearch', $customSearch . $searchQuery);
            $this->fillDropdown('sort_by', 'Name');
        }
        $customSearch .= 'p=2&dir=asc&';
        $this->addParameter('customSearch', $customSearch . $searchQuery);
        $this->addParameter('productName', $productName);
        $this->clickControl('link', 'next_page');
        // Verifying
        $this->assertTrue($this->controlIsVisible('pageelement', 'product_name_header'));
        $this->clickControl('link', 'add_to_compare');
        $this->assertTrue($this->controlIsVisible('pageelement', 'product_name_header'));
    }
}
