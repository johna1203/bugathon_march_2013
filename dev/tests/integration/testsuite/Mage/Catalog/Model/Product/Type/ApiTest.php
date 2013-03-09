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
class Mage_Catalog_Model_Product_Type_ApiTest extends PHPUnit_Framework_TestCase
{
    /**
     * @param string $class
     * @dataProvider itemsDataProvider
     */
    public function testItems($class)
    {
        $model = new $class;
        $result = $model->items();
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        foreach ($result as $item) {
            $this->assertArrayHasKey('type', $item);
            $this->assertArrayHasKey('label', $item);
        }
    }

    public function itemsDataProvider()
    {
        return array(
            array('Mage_Catalog_Model_Product_Type_Api'),
            array('Mage_Catalog_Model_Product_Type_Api_V2'), // a dummy class, doesn't require separate test suite
        );
    }
}
