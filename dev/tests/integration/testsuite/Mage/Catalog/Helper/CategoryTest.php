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

/**
 * @group module:Mage_Catalog
 */
class Mage_Catalog_Helper_CategoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Catalog_Helper_Category
     */
    protected $_helper;

    protected function setUp()
    {
        $this->_helper = new Mage_Catalog_Helper_Category;
    }

    /**
     * @magentoDataFixture Mage/Catalog/_files/categories.php
     */
    public function testGetStoreCategories()
    {
        $categories = $this->_helper->getStoreCategories();
        $this->assertInstanceOf('Varien_Data_Tree_Node_Collection', $categories);
        $index = 0;
        $expectedPaths = array(array(3, '1/2/3'), array(6, '1/2/6'), array(7, '1/2/7'));
        foreach ($categories as $category) {
            $this->assertInstanceOf('Varien_Data_Tree_Node', $category);
            $this->assertEquals($expectedPaths[$index][0], $category->getId());
            $this->assertEquals($expectedPaths[$index][1], $category->getData('path'));
            $index++;
        }
    }

    public function testGetCategoryUrl()
    {
        $url = 'http://example.com/';
        $category = new Mage_Catalog_Model_Category(array('url' => $url));
        $this->assertEquals($url, $this->_helper->getCategoryUrl($category));

        $category = new Varien_Object(array('url' => $url));
        $this->assertEquals($url, $this->_helper->getCategoryUrl($category));
    }

    /**
     * @magentoDataFixture Mage/Catalog/_files/categories.php
     */
    public function testCanShow()
    {
        // by ID of a category that is not a root
        $this->assertTrue($this->_helper->canShow(7));
    }

    public function testCanShowFalse()
    {
        $category = new Mage_Catalog_Model_Category;
        $this->assertFalse($this->_helper->canShow($category));
        $category->setId(1);
        $this->assertFalse($this->_helper->canShow($category));
        $category->setIsActive(true);
        $this->assertFalse($this->_helper->canShow($category));
    }

    public function testGetCategoryUrlSuffixDefault()
    {
        $this->assertEquals('.html', $this->_helper->getCategoryUrlSuffix());
    }

    /**
     * @magentoConfigFixture current_store catalog/seo/category_url_suffix .htm
     */
    public function testGetCategoryUrlSuffix()
    {
        $this->assertEquals('.htm', $this->_helper->getCategoryUrlSuffix());
    }

    public function testGetCategoryUrlPathDefault()
    {
        $this->assertEquals('http://example.com/category',
            $this->_helper->getCategoryUrlPath('http://example.com/category.html')
        );

        $this->assertEquals('http://example.com/category/',
            $this->_helper->getCategoryUrlPath('http://example.com/category.html/', true)
        );
    }

    /**
     * @magentoConfigFixture current_store catalog/seo/category_url_suffix .htm
     */
    public function testGetCategoryUrlPath()
    {
        $this->assertEquals('http://example.com/category.html',
            $this->_helper->getCategoryUrlPath('http://example.com/category.html')
        );
    }

    public function testCanUseCanonicalTagDefault()
    {
        $this->assertEquals(0, $this->_helper->canUseCanonicalTag());
    }

    /**
     * @magentoConfigFixture current_store catalog/seo/category_canonical_tag 1
     */
    public function testCanUseCanonicalTag()
    {
        $this->assertEquals(1, $this->_helper->canUseCanonicalTag());
    }
}
