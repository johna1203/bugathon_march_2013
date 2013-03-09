<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    tests
 * @package     selenium
 * @subpackage  tests
 * @author      Magento Core Team <core@magentocommerce.com>
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * Add address tests.
 *
 * @package     selenium
 * @subpackage  tests
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Helper class
 *
 * @package     selenium
 * @subpackage  tests
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Core_Mage_Email_Helper extends Mage_Selenium_TestCase
{
    /**
     * Create Template.
     * PreConditions: 'Manage Templates' page is opened.
     *
     * @param array $userData
     */
    public function createTemplate(array $data)
    {
        // Click 'Add New Template' button.
        $this->clickButton('add_new_template');

        // Fill in 'Template' form
        $this->fillFieldset($data, 'email_template_edit_form');

        $this->saveForm('save_template');
    }

    /**
     * Delete Template.
     *
     * @param string $templateCode
     */
    public function deleteTemplate($templateCode)
    {
        $this->addParameter('elementTitle', $templateCode);
        $this->searchAndOpen(array('code' => $templateCode));
        $this->clickControlAndConfirm('button', 'delete_template', 'confirmation_for_delete_template');
    }
}
