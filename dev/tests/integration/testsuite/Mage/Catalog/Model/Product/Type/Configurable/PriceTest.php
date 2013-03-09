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
class Mage_Catalog_Model_Product_Type_Configurable_PriceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @magentoDataFixture Mage/Catalog/_files/product_configurable.php
     */
    public function testGetFinalPrice()
    {
        $product = new Mage_Catalog_Model_Product;
        $product->load(1); // fixture
        $model = new Mage_Catalog_Model_Product_Type_Configurable_Price;

        // without configurable options
        $this->assertEquals(100.0, $model->getFinalPrice(1, $product));

        // with configurable options
        $attributes = $product->getTypeInstance()->getConfigurableAttributes($product);
        foreach ($attributes as $attribute) {
            $prices = $attribute->getPrices();
            $product->addCustomOption(
                'attributes',
                serialize(array($attribute->getProductAttribute()->getId() => $prices[0]['value_index']))
            );
            break;
        }
        $this->assertEquals(105.0, $model->getFinalPrice(1, $product));
    }
}
