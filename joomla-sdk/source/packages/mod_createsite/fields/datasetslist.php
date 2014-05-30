<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

JLoader::import('helpers.api', JPATH_ADMINISTRATOR.'/components/com_demoregister');

/**
 * Form Field to display a list of the datasets
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldDatasetslist extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'Datasetslist';

	/**
	 * Method to get the field input for module layouts.
	 *
	 * @return  string  The field input.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		$html = array();

		if (!class_exists('HelperDemoRegisterApi')) {
			JFactory::getApplication()->enqueueMessage('Cant load api helper','error');
			return implode($html);
		}

		$token = HelperDemoRegisterApi::getApiKey();
       if ($token) {
            $list = HelperDemoRegisterApi::call(array('method' => 'ListDatasets', 'p_application' => 'joomla'));

            $cids = !empty($this->value) ? $this->value :  array() ;

            if (!empty($this->element['description'])) {
            	$html[] = '<hr>';
			$html[] = sprintf('<p>%s</p>', $this->element['description']);
            }

            $html[] = '<hr><table class="table table-striped">';
            $html[] = '<thead>';
            $html[] = '<tr>';
            $html[] = '<th>' . JHtml::_('grid.checkall') . '</th>';
            $html[] = '<th>Pkg. Name</th>';
            $html[] = '<th>Tags</th>';
            $html[] = '<th>Pkg. Version</th>';
            $html[] = '<th>Joomla Version</th>';
            $html[] = '<th>Pkg. Date</th>';
            $html[] = '<th>ID</th>';
            $html[] = '</tr>';
            $html[] = '</thead>';
            $html[] = '<tbody>';
            foreach ($list['datasets']['joomla'] as $key => $row) {
                  $row['tags'] = !empty($row['tags']) ? $row['tags'] : '' ;
            	$checked = false;
            	foreach ($cids as $cid) {
            		if ($cid == sprintf('%s;%s;%s',$row['datasetid'],$row['name'],$row['app_family'])) { $checked = true; break; }
            	}
            	
            	$html[] = '<tr>';

            	$input = sprintf('<td><input type="checkbox" id="cb%s"',$key);
            	$input .= ($checked) ? ' checked ' : '';
            	$input .= ' ' . sprintf('name="%s[]"',$this->name);
            	$input .= ' ' . sprintf('value="%s"',$row['datasetid'].';'.$row['name'].';'.$row['app_family']);
            	$input .= '>';


				$html[] = $input;
				$html[] = '</td>';

            		$html[] = sprintf('<td>%s</td>',$row['name']);
                        $html[] = sprintf('<td>%s</td>',$row['tags']);
            		$html[] = sprintf('<td>%s</td>',$row['version']);
            		$html[] = sprintf('<td>%s</td>',$row['app_family']);
            		$html[] = sprintf('<td>%s</td>',$row['date_added']);
            		$html[] = sprintf('<td>%s</td>',$row['datasetid']);
            	$html[] = '</tr>';
            }
            $html[] = '</tbody>';
            $html[] = '</table>';
        } else {
            JFactory::getApplication()->enqueueMessage('Please regenerate your token','error');
		return implode($html);
        }

	     return implode($html);
	}
}
