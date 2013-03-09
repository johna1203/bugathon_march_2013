<?php
/**
 * {license_notice}
 *
 * @category    Magento
 * @package     Magento_Paypal
 * @subpackage  integration_tests
 * @copyright   {copyright}
 * @license     {license_link}
 */

/**
 * Test class for Mage_Paypal_Model_Payflowadvanced
 *
 * TODO: avoid copy-paste with extending Mage_Paypal_Model_PayflowlinkTest (problem with autoloading)
 * @group module:Mage_Paypal
 */
class Mage_Paypal_Model_PayflowadvancedTest extends PHPUnit_Framework_TestCase
{
    protected $_modelClass = 'Mage_Paypal_Model_Payflowadvanced';

    /**
     * Paypal sent request
     *
     * @var Varien_Object
     */
    static public $request;

    /**#@+
     *
     * Test response parameters
     */
    const PARAMETER_FIRSTNAME = 'Firstname';
    const PARAMETER_LASTNAME = 'Lastname';
    const PARAMETER_ADDRESS = '111 Streetname Street';
    const PARAMETER_CITY = 'City';
    const PARAMETER_STATE = 'State';
    const PARAMETER_ZIP = '11111';
    const PARAMETER_COUNTRY = 'Country';
    const PARAMETER_PHONE = '111-11-11';
    const PARAMETER_EMAIL = 'email@example.com';
    const PARAMETER_NAMETOSHIP = 'Name to ship';
    const PARAMETER_ADDRESSTOSHIP = '112 Streetname Street';
    const PARAMETER_CITYTOSHIP = 'City to ship';
    const PARAMETER_STATETOSHIP = 'State to ship';
    const PARAMETER_ZIPTOSHIP = '22222';
    const PARAMETER_COUNTRYTOSHIP = 'Country to ship';
    const PARAMETER_PHONETOSHIP = '222-22-22';
    const PARAMETER_EMAILTOSHIP = 'emailtoship@example.com';
    const PARAMETER_FAXTOSHIP = '333-33-33';
    const PARAMETER_METHOD = 'CC';
    const PARAMETER_CSCMATCH = 'Y';
    const PARAMETER_AVSADDR = 'X';
    const PARAMETER_AVSZIP = 'N';
    const PARAMETER_TYPE = 'A';
    /**#@-*/

    public function testSetResponseData()
    {
        // Setting legacy parameters
        /** @var $model Mage_Paypal_Model_Payflowadvanced */
        $model = new $this->_modelClass();
        $model->setResponseData(array(
            'NAME' => self::PARAMETER_FIRSTNAME . ' ' . self::PARAMETER_LASTNAME,
            'FIRSTNAME' => self::PARAMETER_FIRSTNAME,
            'LASTNAME' => self::PARAMETER_LASTNAME,
            'ADDRESS' => self::PARAMETER_ADDRESS,
            'CITY' => self::PARAMETER_CITY,
            'STATE' => self::PARAMETER_STATE,
            'ZIP' => self::PARAMETER_ZIP,
            'COUNTRY' => self::PARAMETER_COUNTRY,
            'PHONE' => self::PARAMETER_PHONE,
            'EMAIL' => self::PARAMETER_EMAIL,
            'NAMETOSHIP' => self::PARAMETER_NAMETOSHIP,
            'ADDRESSTOSHIP' => self::PARAMETER_ADDRESSTOSHIP,
            'CITYTOSHIP' => self::PARAMETER_CITYTOSHIP,
            'STATETOSHIP' => self::PARAMETER_STATETOSHIP,
            'ZIPTOSHIP' => self::PARAMETER_ZIPTOSHIP,
            'COUNTRYTOSHIP' => self::PARAMETER_COUNTRYTOSHIP,
            'PHONETOSHIP' => self::PARAMETER_PHONETOSHIP,
            'EMAILTOSHIP' => self::PARAMETER_EMAILTOSHIP,
            'FAXTOSHIP' => self::PARAMETER_FAXTOSHIP,
            'METHOD' => self::PARAMETER_METHOD,
            'CSCMATCH' => self::PARAMETER_CSCMATCH,
            'AVSDATA' => self::PARAMETER_AVSADDR . self::PARAMETER_AVSZIP,
            'TYPE' => self::PARAMETER_TYPE,
        ));
        $this->_assertResponseData($model);

        // Setting new parameters
        /** @var $model Mage_Paypal_Model_Payflowadvanced */
        $model = new $this->_modelClass();
        $model->setResponseData(array(
            'BILLTOFIRSTNAME' => self::PARAMETER_FIRSTNAME,
            'BILLTOLASTNAME' => self::PARAMETER_LASTNAME,
            'BILLTOSTREET' => self::PARAMETER_ADDRESS,
            'BILLTOCITY' => self::PARAMETER_CITY,
            'BILLTOSTATE' => self::PARAMETER_STATE,
            'BILLTOZIP' => self::PARAMETER_ZIP,
            'BILLTOCOUNTRY' => self::PARAMETER_COUNTRY,
            'BILLTOPHONE' => self::PARAMETER_PHONE,
            'BILLTOEMAIL' => self::PARAMETER_EMAIL,
            'SHIPTOFIRSTNAME' => self::PARAMETER_NAMETOSHIP,
            'SHIPTOSTREET' => self::PARAMETER_ADDRESSTOSHIP,
            'SHIPTOCITY' => self::PARAMETER_CITYTOSHIP,
            'SHIPTOSTATE' => self::PARAMETER_STATETOSHIP,
            'SHIPTOZIP' => self::PARAMETER_ZIPTOSHIP,
            'SHIPTOCOUNTRY' => self::PARAMETER_COUNTRYTOSHIP,
            'SHIPTOPHONE' => self::PARAMETER_PHONETOSHIP,
            'SHIPTOEMAIL' => self::PARAMETER_EMAILTOSHIP,
            'SHIPTOFAX' => self::PARAMETER_FAXTOSHIP,
            'TENDER' => self::PARAMETER_METHOD,
            'CVV2MATCH' => self::PARAMETER_CSCMATCH,
            'AVSADDR' => self::PARAMETER_AVSADDR,
            'AVSZIP' => self::PARAMETER_AVSZIP,
            'TRXTYPE' => self::PARAMETER_TYPE,
        ));
        $this->_assertResponseData($model);
    }

    public function testDefaultRequestParameters()
    {
        /** @var $model Mage_Paypal_Model_Payflowadvanced */
        $model = $this->getMock($this->_modelClass, array('_postRequest', '_processTokenErrors'));
        $this->_prepareRequest($model);

        // check whether all parameters were sent
        $request = Mage_Paypal_Model_PayflowadvancedTest::$request;
        $this->_assertRequestBaseParameters($model);
        $this->assertEquals('TRUE', $request->getCscrequired());
        $this->assertEquals('TRUE', $request->getCscedit());
        $this->assertEquals('FALSE', $request->getEmailcustomer());
        $this->assertEquals('GET', $request->getUrlmethod());
    }

    /**
     * @magentoConfigFixture current_store payment/payflow_advanced/csc_required 0
     * @magentoConfigFixture current_store payment/payflow_advanced/csc_editable 0
     * @magentoConfigFixture current_store payment/payflow_advanced/url_method POST
     * @magentoConfigFixture current_store payment/payflow_advanced/email_confirmation 1
     */
    public function testConfiguredRequestParameters()
    {
        /** @var $model Mage_Paypal_Model_Payflowadvanced */
        $model = $this->getMock($this->_modelClass, array('_postRequest', '_processTokenErrors'));
        $this->_prepareRequest($model);

        // check whether all parameters were sent
        $request = Mage_Paypal_Model_PayflowadvancedTest::$request;
        $this->_assertRequestBaseParameters($model);
        $this->assertEquals('FALSE', $request->getCscrequired());
        $this->assertEquals('FALSE', $request->getCscedit());
        $this->assertEquals('TRUE', $request->getEmailcustomer());
        $this->assertEquals('POST', $request->getUrlmethod());
    }

    /**
     * Prepare request for test
     *
     * @param Mage_Paypal_Model_Payflowadvanced $model
     */
    protected function _prepareRequest(Mage_Paypal_Model_Payflowadvanced $model)
    {
        $payment = new Mage_Payment_Model_Info();
        $payment->setOrder(new Varien_Object());
        $model->setInfoInstance($payment);
        $checkRequest = create_function('$request', 'Mage_Paypal_Model_PayflowadvancedTest::$request = $request;');
        $model->expects($this->any())->method('_postRequest')->will($this->returnCallback($checkRequest));
        Mage_Paypal_Model_PayflowadvancedTest::$request = null;
        $model->initialize(Mage_Paypal_Model_Config::PAYMENT_ACTION_AUTH, new Varien_Object());
    }

    /**
     * Assert request not configurable parameters
     *
     * @param Mage_Paypal_Model_Payflowadvanced $model
     */
    protected function _assertRequestBaseParameters(Mage_Paypal_Model_Payflowadvanced $model)
    {
        $controllerPath = '/paypal/' . $model->getCallbackController() . '/';
        $request = Mage_Paypal_Model_PayflowadvancedTest::$request;
        $this->assertEquals(1, preg_match('|' . $controllerPath . 'cancelPayment$|', $request->getCancelurl()));
        $this->assertEquals(1, preg_match('|' . $controllerPath . 'returnUrl$|', $request->getErrorurl()));
        $this->assertEquals(1, preg_match('|' . $controllerPath . 'silentPost$|', $request->getSilentposturl()));
        $this->assertEquals(1, preg_match('|' . $controllerPath . 'returnUrl$|', $request->getReturnurl()));
        $this->assertEquals('TRUE', $request->getSilentpost());
        $this->assertEquals(Mage_Paypal_Model_Payflowadvanced::LAYOUT_TEMPLATE, $request->getTemplate());
        $this->assertEquals('TRUE', $request->getSilentpost());
        $this->assertEquals('TRUE', $request->getDisablereceipt());
    }

    /**
     * Assert response data
     *
     * @param Mage_Paypal_Model_Payflowadvanced $model
     */
    protected function _assertResponseData(Mage_Paypal_Model_Payflowadvanced $model)
    {
        $data = $model->getResponse()->getData();
        $this->assertEquals(self::PARAMETER_FIRSTNAME . ' ' . self::PARAMETER_LASTNAME, $data['name']);
        $this->assertEquals(self::PARAMETER_FIRSTNAME, $data['firstname']);
        $this->assertEquals(self::PARAMETER_LASTNAME, $data['lastname']);
        $this->assertEquals(self::PARAMETER_ADDRESS, $data['address']);
        $this->assertEquals(self::PARAMETER_CITY, $data['city']);
        $this->assertEquals(self::PARAMETER_STATE, $data['state']);
        $this->assertEquals(self::PARAMETER_ZIP, $data['zip']);
        $this->assertEquals(self::PARAMETER_COUNTRY, $data['country']);
        $this->assertEquals(self::PARAMETER_PHONE, $data['phone']);
        $this->assertEquals(self::PARAMETER_EMAIL, $data['email']);
        $this->assertEquals(self::PARAMETER_NAMETOSHIP, $data['nametoship']);
        $this->assertEquals(self::PARAMETER_ADDRESSTOSHIP, $data['addresstoship']);
        $this->assertEquals(self::PARAMETER_CITYTOSHIP, $data['citytoship']);
        $this->assertEquals(self::PARAMETER_STATETOSHIP, $data['statetoship']);
        $this->assertEquals(self::PARAMETER_ZIPTOSHIP, $data['ziptoship']);
        $this->assertEquals(self::PARAMETER_COUNTRYTOSHIP, $data['countrytoship']);
        $this->assertEquals(self::PARAMETER_PHONETOSHIP, $data['phonetoship']);
        $this->assertEquals(self::PARAMETER_EMAILTOSHIP, $data['emailtoship']);
        $this->assertEquals(self::PARAMETER_FAXTOSHIP, $data['faxtoship']);
        $this->assertEquals(self::PARAMETER_METHOD, $data['method']);
        $this->assertEquals(self::PARAMETER_CSCMATCH, $data['cscmatch']);
        $this->assertEquals(self::PARAMETER_AVSADDR . self::PARAMETER_AVSZIP, $data['avsdata']);
        $this->assertEquals(self::PARAMETER_TYPE, $data['type']);
    }
}
