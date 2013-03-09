<?php
class Core_Mage_Core_DataHelperTest extends Mage_Selenium_TestCase
{
    public function setUpBeforeTests()
    {
        require_once '../../../../app/Mage.php';
        Mage::app();
    }

    /**
     * @test
     */
    public function removeAccentsWithGermanUmlauts()
    {
        /* @var $helper Mage_Core_Helper_Data */
        $helper = Mage::helper('core');
        $umlauts = 'äöüÄÖÜ';
        $this->assertEquals($helper->removeAccents($umlauts, true), 'aeoeueAeOeUe');
    }
}