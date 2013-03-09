<?php

/**
 * @group module:Mage_Catalog
 */
class Mage_Catalog_Model_Resource_Product_Configurable_CollectionTest
    extends PHPUnit_Framework_TestCase
{
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
     * @magentoDataFixture Mage/Catalog/_files/product_large_configurables.php
     * @dataProvider productCollectionDataProvider
     */
    public function loadProductCollection($numConfigurables, $numActiveAssociatedSimples)
    {
        $collection = new Mage_Catalog_Model_Resource_Product_Collection();
        $collection->addFieldToFilter('type_id', Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE)
            ->addStoreId(Mage::app()->getDefaultStoreView()->getId());

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

    public function loadProductCollectionWithFlagIsFaster()
    {
        $collectionWithoutFlag = new Mage_Catalog_Model_Resource_Product_Collection();
        $collectionWithoutFlag->addFieldToFilter('type_id', Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE);
        $collectionWithoutFlag->addStoreId(Mage::app()->getDefaultStoreView()->getId());

        $collectionWithFlag = clone $collectionWithoutFlag;

        $collectionWithFlag->setFlag('load_associated_products', true);
        $startWithFlagLoad = microtime(true);
        $collectionWithFlag->load();
        $withFlagLoadTime = microtime(true) - $startWithFlagLoad;

        $startWithoutFlagLoad = microtime(true);
        $collectionWithoutFlag->load();
        $collectionWithoutFlag= microtime(true) - $startWithoutFlagLoad;

        $this->assertLessThan()
    }
}
