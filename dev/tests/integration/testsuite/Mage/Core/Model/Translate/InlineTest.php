<?php
/**
 * {license_notice}
 *
 * @category    Magento
 * @package     Mage_Core
 * @subpackage  integration_tests
 * @copyright   {copyright}
 * @license     {license_link}
 */

/**
 * @group module:Mage_Core
 */
class Mage_Core_Model_Translate_InlineTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Core_Model_Translate_Inline
     */
    protected $_model;

    public function setUp()
    {
        $this->_model = new Mage_Core_Model_Translate_Inline();
    }

    /**
     * @magentoConfigFixture current_store dev/translate_inline/active 1
     */
    public function testDisbaledByDefault()
    {
        $this->assertTrue($this->_model->isAllowed());
    }

    /**
     * @magentoConfigFixture current_store dev/translate_inline/active 1
     * @dataProvider dataStripInline
     * @param $body
     * @param $expectedResult
     */
    public function testStripInlineTranslations($body, $expectedResult)
    {
        $this->_model->stripInlineTranslations($body);
        $this->assertEquals($expectedResult, $body);
    }

    /**
     * @return array
     */
    public function dataStripInline()
    {
        return array(
            array("{{{1}}{{2}}{{3}}{{4}}}", "1"),
            array('<a title="{{{1}}{{2}}{{3}}{{4}}}">a</a>', '<a title="1">a</a>'),
            array(array('<a title="{{{1}}{{2}}{{3}}{{4}}}">a</a>', '{}'), array('<a title="1">a</a>', '{}')),
        );
    }

    /**
     * @magentoConfigFixture current_store dev/translate_inline/active 1
     * @dataProvider dataForProcessResponseBody
     * @param $body
     * @param $expectedResult
     */
    public function testProcessResponseBody($body, $expectedResult)
    {
        $this->_model->processResponseBody($body);
        $this->assertEquals($expectedResult, $body);
    }

    /**
     * Get translate data string
     *
     * @param $location
     * @return string
     */
    private function getTranslate($location)
    {
        return htmlspecialchars(json_encode(array(
            'shown' => '1',
            'translated' => '2',
            'original' => '3',
            'location' => $location,
            'scope' => '4',
        )));
    }

    /**
     * Get translate data string
     *
     * @param $text
     * @param $location
     * @return string
     */
    private function getTranslateText($text, $location)
    {
        return htmlspecialchars(json_encode(array(
            'shown' => $text,
            'translated' => $text,
            'original' => $text,
            'location' => $location,
            'scope' => $text,
        )));
    }

    /**
     * @return array
     */
    public function dataForProcessResponseBody()
    {
        return array(
            array(
            '<button id="reset_order_top_button" type="button" class="scalable cancel"'
                . ' onclick="deleteConfirm(\'{{{1}}'
                . '{{2}}{{3}}'
                . '{{4}}}\', \'\')"'
                . ' style="display:none"><span>{{{a}}{{a}}{{a}}{{a}}}</span></button>',
            '<button translate="[' . $this->getTranslate('Push button') . ','
                . $this->getTranslateText('a', 'Push button')
                . ']" id="reset_order_top_button" type="button" class="scalable cancel"'
                . ' onclick="deleteConfirm(\'1'
                . '\', \'\')"'
                . ' style="display:none"><span>a</span></button>'),
            array(
                '{{{1}}{{2}}{{3}}{{4}}}',
                '<span translate="['.$this->getTranslate('Text').']">1</span>'
            ),
            array(
                '<legend>legend</legend>{{{1}}{{2}}{{3}}{{4}}}<i><script>{{{1}}{{2}}{{3}}{{4}}}'
                    . '<b title="{{{1}}{{2}}{{3}}{{4}}}">{{{1}}{{2}}{{3}}{{4}}}</b></script></i>',
                '<legend>legend</legend>'
                    . '<span translate="['.$this->getTranslate('Text').']">1</span>'
                    . '<i><script>1<b title="1">1</b></script>'
                    . '<span class="translate-inline-script" translate="['
                    . $this->getTranslate('String in Javascript').']">SCRIPT</span></i>'
            ),

            // translate in attributes
            array(
                '<a title="{{{1}}{{2}}{{3}}{{4}}}">a</a>',
                '<a translate="['.$this->getTranslate('Link label').']" title="1">a</a>'
            ),
            array(
                '<a alt="{{{1}}{{2}}{{3}}{{4}}}" title="{{{a}}{{a}}{{a}}{{a}}}">a</a>',
                '<a translate="[' . $this->getTranslate('Link label') . ','
                    .  $this->getTranslateText('a', 'Link label') .']" alt="1" title="a">a</a>'
            ),

            array('<b>{{{1}}{{2}}{{3}}{{4}}}</b>', '<b translate="[' . $this->getTranslate('Bold text') . ']">1</b>'),
            array(
                '<b><i>{{{1}}{{2}}{{3}}{{4}}}</i></b>',
                '<b translate="['.$this->getTranslate('Bold text').']"><i>1</i></b>'
            ),

            array(
                '<title>{{{1}}{{2}}{{3}}{{4}}}</title>',
                '<title>1</title><span class="translate-inline-title" translate="['
                    . $this->getTranslate('Page title') . ']">TITLE</span>'
            ),
            array(
                '<script>{{{1}}{{2}}{{3}}{{4}}}</script>',
                '<script>1</script><span class="translate-inline-script" translate="['
                    . $this->getTranslate('String in Javascript') . ']">SCRIPT</span>'
            ),

            array(
                '<script>{{{1}}{{2}}{{3}}{{4}}}'
                    . 'a = "<b title=\"{{{1}}{{2}}{{3}}{{4}}}\">{{{1}}{{2}}{{3}}{{4}}}</b>";</script>',
                '<script>1a = "<b title=\"1\">1</b>";</script><span class="translate-inline-script" translate="['
                    . $this->getTranslate('String in Javascript') . ']">SCRIPT</span>'
            ),
        );
    }
}
