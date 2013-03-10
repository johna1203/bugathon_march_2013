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
class Mage_Catalog_Helper_Product_UrlTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Catalog_Helper_Product_Url
     */
    protected $_helper;

    public static function setUpBeforeClass()
    {
        $default = Mage::getConfig()->getNode('default');
        $convert = $default->addChild('url')->addChild('convert')->addChild('char8482');
        $convert->addChild('from', '™');
        $convert->addChild('to', 'TM');
    }

    protected function setUp()
    {
        $this->_helper = new Mage_Catalog_Helper_Product_Url;
    }

    public function testGetConvertTable()
    {
        $convertTable = $this->_helper->getConvertTable();
        $this->assertInternalType('array', $convertTable);
        $this->assertNotEmpty($convertTable);
    }

    public function testFormat()
    {
        $this->assertEquals('TM', $this->_helper->format('™'));
    }
}
