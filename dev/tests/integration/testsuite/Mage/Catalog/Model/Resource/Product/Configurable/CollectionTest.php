<?php

/**
 * @group module:Mage_Catalog
 * @magentoDataFixture Mage/Catalog/_files/product_large_configurables.php
 */
class Mage_Catalog_Model_Resource_Product_Configurable_CollectionTest
    extends PHPUnit_Framework_TestCase
{
    /**
     * These values should match the settings in the fixture
     * Mage/Catalog/_files/product_large_configurables.php
     *
     * @return array
     */
    public function productCollectionDataProvider()
    {
        $numConfigurables = 16;
        $numAssociatedSimples = 20;
        $disabledModulus = 10;
        return array(
            array($numConfigurables, $numAssociatedSimples - floor($numAssociatedSimples / $disabledModulus))
        );
    }

    /**
     * @test
     * @dataProvider productCollectionDataProvider
     */
    public function loadProductCollection($numConfigurables, $numActiveAssociatedSimples)
    {
        $collection = new Mage_Catalog_Model_Resource_Product_Collection();
        $collection->addFieldToFilter('type_id', Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE)
            ->setStoreId(Mage::app()->getDefaultStoreView()->getId());

        $this->assertEquals(
            $numConfigurables, $collection->count(),
            "Expected $numConfigurables products in the collection, found {$collection->count()}."
        );

        foreach ($collection as $product) {
            /** @var $product Mage_Catalog_Model_Product */
            $usedProducts = (array) $product->getTypeInstance()->getUsedProducts($product);
            $this->assertEquals(
                count($usedProducts), $numActiveAssociatedSimples,
                "Expected $numActiveAssociatedSimples associated simple products, found "  . count($usedProducts)
            );
        }
    }

    /**
     * @test
     */
    public function loadTimeIsFasterForProductCollectionWithFlagThenWithoutFlag()
    {
        $collectionWithoutFlag = new Mage_Catalog_Model_Resource_Product_Collection();
        $collectionWithoutFlag->addFieldToFilter('type_id', Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE);
        $collectionWithoutFlag->setStoreId(Mage::app()->getDefaultStoreView()->getId());

        $collectionWithFlag = clone $collectionWithoutFlag;
        $collectionWithFlag->setFlag('load_associated_products', true);

        /** @var $product Mage_Catalog_Model_Product */

        $withFlagLoadStart = microtime(true);
        foreach ($collectionWithFlag->load() as $product) {
            // Load the used products
            $product->getTypeInstance(true)->getUsedProducts(null, $product);
        }
        $withFlagLoadTime = microtime(true) - $withFlagLoadStart;

        $withoutFlagLoadStart = microtime(true);
        foreach ($collectionWithoutFlag->load() as $product) {
            // Load the used products
            $product->getTypeInstance(true)->getUsedProducts(null, $product);
        }
        $withoutLoadTime= microtime(true) - $withoutFlagLoadStart;

        $this->assertLessThan(
            $withoutLoadTime, $withFlagLoadTime,
            "Collection load time with set flag ($withFlagLoadTime) is not slower then load time without the flag ($withoutLoadTime)"
        );
    }
}
