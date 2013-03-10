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
 * Test class for Mage_Catalog_Model_Layer_Filter_Price.
 *
 * @group module:Mage_Catalog
 * @magentoDataFixture Mage/Catalog/Model/Layer/Filter/Price/_files/products_base.php
 */
class Mage_Catalog_Model_Layer_Filter_Price_AlgorithmBaseTest extends PHPUnit_Framework_TestCase
{
    /**
     * Algorithm model
     *
     * @var Mage_Catalog_Model_Layer_Filter_Price_Algorithm
     */
    protected $_model;

    /**
     * Layer model
     *
     * @var Mage_Catalog_Model_Layer
     */
    protected $_layer;

    /**
     * Price filter model
     *
     * @var Mage_Catalog_Model_Layer_Filter_Price
     */
    protected $_filter;

    protected function setUp()
    {
        $this->_model = new Mage_Catalog_Model_Layer_Filter_Price_Algorithm();
        $this->_layer = new Mage_Catalog_Model_Layer();
        $this->_filter = new Mage_Catalog_Model_Layer_Filter_Price();
        $this->_filter
            ->setLayer($this->_layer)
            ->setAttributeModel(new Varien_Object(array('attribute_code' => 'price')));
    }

    /**
     * @dataProvider pricesSegmentationDataProvider
     */
    public function testPricesSegmentation($categoryId, $intervalsNumber, $intervalItems)
    {
        $this->_layer->setCurrentCategory($categoryId);
        $collection = $this->_layer->getProductCollection();

        $memoryUsedBefore = memory_get_usage();
        $this->_model->setPricesModel($this->_filter)->setStatistics(
            $collection->getMinPrice(),
            $collection->getMaxPrice(),
            $collection->getPriceStandardDeviation(),
            $collection->getSize()
        );
        if (!is_null($intervalsNumber)) {
            $this->assertEquals($intervalsNumber, $this->_model->getIntervalsNumber());
        }

        $items = $this->_model->calculateSeparators();
        $this->assertEquals(array_keys($intervalItems), array_keys($items));

        for ($i = 0; $i < count($intervalItems); ++$i) {
            $this->assertInternalType('array', $items[$i]);
            $this->assertEquals($intervalItems[$i]['from'],  $items[$i]['from']);
            $this->assertEquals($intervalItems[$i]['to'],    $items[$i]['to']);
            $this->assertEquals($intervalItems[$i]['count'], $items[$i]['count']);
        }

        // Algorythm should use less than 10M
        $this->assertLessThan(10 * 1024 * 1024, memory_get_usage() - $memoryUsedBefore);
    }

    public function pricesSegmentationDataProvider()
    {
        $testCases = include(dirname(__FILE__) . '/_files/_algorithm_base_data.php');
        $result = array();
        foreach ($testCases as $index => $testCase) {
            $result[] = array(
                $index + 4, //category id
                $testCase[1],
                $testCase[2]
            );
        }

        return $result;
    }
}
