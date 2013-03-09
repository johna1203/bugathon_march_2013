<?php
class Mage_Rating_Model_Resource_Rating_CollectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Rating_Model_Resource_Rating_Collection
     */
    protected $_model;

    protected function setUp()
    {
        $this->_model = new Mage_Rating_Model_Resource_Rating_Collection();
    }

    public function testWithoutFilter()
    {
        // in magento after installation there are three ratings
        // Price, Quality and Value
        // they are all hidden
        $this->_model->load();
        $this->assertEquals(3, $this->_model->count());
        return $this->_model;
    }

    /**
     * @depends testWithoutFilter
     */
    public function testWithStoreFilterNull($allRatings)
    {
        foreach($allRatings as $rating) {
            /* @var $rating Mage_Rating_Model_Rating */
            $rating->setStores(array(0,1));
            $rating->save();
        }

        // test
        $this->_model->setStoreFilter(null);
        $this->assertEquals(3, $this->_model->count());

        // reset all the ratings
        foreach($allRatings as $rating) {
            /* @var $rating Mage_Rating_Model_Rating */
            $rating->setStores(array());
            $rating->save();
        }
    }
}