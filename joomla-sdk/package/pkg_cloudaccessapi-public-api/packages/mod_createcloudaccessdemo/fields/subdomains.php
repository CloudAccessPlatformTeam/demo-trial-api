<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

JLoader::import('helpers.api', JPATH_ADMINISTRATOR.'/components/com_cloudaccessapi');
jimport('joomla.form.field.list');

/**
 * Form Field to display a list of the datasets
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldSubdomains extends JFormFieldList
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  11.1
     */
    protected $type = 'Subdomains';

    /**
     * Merge Api Options
     *
     * @return array
     */
    protected function getOptions()
    {
        $options = parent::getOptions();

        if (!class_exists('HelperCloudaccessApiApi')) {
            JFactory::getApplication()->enqueueMessage('Cant load api helper','error');
        }

        $token = HelperCloudaccessApiApi::getApiKey();
        if ($token) {
            $list = HelperCloudaccessApiApi::call(array('method' => 'ListAllowedSubdomains'));

            foreach ($list as $value) {
                $options[] = JHtml::_(
                    'select.option', $value,
                    JText::alt(trim((string) $value), preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)), 'value', 'text',
                    ''
                );
            }
        } else {
            JFactory::getApplication()->enqueueMessage('Please regenerate your token','error');
        }

        return $options;
    }
}