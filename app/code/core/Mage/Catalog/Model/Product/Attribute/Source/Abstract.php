<?php

abstract class Mage_Catalog_Model_Product_Attribute_Source_Abstract extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    /**
     * Add Value Sort To Collection Select
     *
     * @param Mage_Catalog_Model_Resource_Product_Collection $collection
     * @param string $dir direction
     * @return Mage_Catalog_Model_Product_Attribute_Source_Abstract
     */
    public function addValueSortToCollection($collection, $dir = Varien_Data_Collection::SORT_ORDER_DESC)
    {
        if (method_exists($collection, 'isEnabledFlat') && $collection->isEnabledFlat()) {
            $collection->addAttributeToSelect($this->getAttribute()->getAttributeCode())
                ->getSelect()
                    ->order("{$this->getAttribute()->getAttributeCode()} {$dir}");
        } else {
            return parent::addValueSortToCollection($collection, $dir);
        }
        return $this;
    }
}