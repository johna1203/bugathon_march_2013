<?php
class Core_Mage_SystemConfiguration_SaveAndDisplayConfiguration extends Mage_Selenium_TestCase
{
    /**
     * @test
     */
    public function displayEmptyValuesWithoutInheritance()
    {
        $setData = $this->loadDataSet('General', 'default');
        $this->loginAdminUser();
        $this->navigate('system_configuration');
        $this->openTab('general_general');
        $this->fillTab($setData, 'general_store_information');

    }

}