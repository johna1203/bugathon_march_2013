<?php
/**
 * {license_notice}
 *
 * @category    Magento
 * @package     Magento_Catalog
 * @subpackage  integration_tests
 * @copyright   {copyright}
 * @license     {license_link}
 */

/**
 * @group module:Mage_Catalog
 */
class Mage_Catalog_Model_Product_ImageTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return Mage_Catalog_Model_Product_Image
     */
    public function testSetBaseFilePlaceholder()
    {
        $model = new Mage_Catalog_Model_Product_Image;
        $model->setDestinationSubdir('image')->setBaseFile('');
        $this->assertNotEmpty($model->getBaseFile());
        return $model;
    }

    /**
     * @param Mage_Catalog_Model_Product_Image $model
     * @depends testSetBaseFilePlaceholder
     */
    public function testGetUrlPlaceholder($model)
    {
        $this->assertStringMatchesFormat(
            'http://%s/media/catalog/product/cache/%s/image/%s/images/catalog/product/placeholder/image.jpg',
            $model->getUrl()
        );
    }
}
