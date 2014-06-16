<?php
/**
 * @package    Joomla.Site
 * @subpackage Modules
 * @copyright  Copyright (C) 2000 - 2013 CloudAccess.net. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.helper');
jimport('legacy.component.helper');

require_once __DIR__.'/helper.php';
$helper = new createSiteHelper;
$helper->initialise();

$applicationsOptions = array();
$cids = $params->get('cid');
if (!empty($cids)) {
    foreach ($cids as $cid) {
        $parts = explode(';', $cid);
        $app_family = end($parts);
        $families = explode(',',$app_family);
        foreach ($families as $value) {
            $text = str_replace('-',' ',$value);
            $applicationsOptions[$value] = $text;
        }
    }
} else {
    $applicationsOptions['joomla-1.5'] = 'joomla 1.5';
    $applicationsOptions['joomla-2.5'] = 'joomla 2.5';
    $applicationsOptions['joomla-3.1'] = 'joomla 3.1';
    $applicationsOptions['joomla-3.2'] = 'joomla 3.2';
    $applicationsOptions['joomla-3.3'] = 'joomla 3.3';
}
$doc = JFactory::getDocument();
if (JVERSION < 3.0) {
    $doc->addScript('modules/'.$module->module.'/assets/js/jquery.js');
}

$form = $helper->getForm();
$caFormDefaultValues = array();
foreach ($form->getFieldset() as $field) {
    $caFormDefaultValues[$field->id] = $field->value;
}


/**
 * Function for support dif templates by joomla version
 *
 * @package     Joomla.Platform
 * @subpackage  createsite
 *
 * @param       string  $module  The module name
 * @param       string  $layout  The layout name
 *
 * @return      Path for default layout
 *
 * @since       3.0
 */
function getModuleLayoutPath($module, $layout = 'default')
{
    $template = JFactory::getApplication()->getTemplate();
    $defaultLayout = $layout;
    if (strpos($layout, ':') !== false) {
        // Get the template and file name from the string
        $temp = explode(':', $layout);
        $template = ($temp[0] == '_') ? $template : $temp[0];
        $layout = $temp[1];
        $defaultLayout = ($temp[1]) ? $temp[1] : 'default';
    } else {
        $temp = $layout;
    }

    $jversion = substr(JVERSION, 0, 3);
    // Build the template and base path for the layout
    $tPath = JPATH_THEMES . '/' . $template . '/html/' . $module . '/' . $layout . '.php';
    $jdPath = JPATH_BASE . '/modules/' . $module . '/tmpl/' . substr($jversion,0,1) . '.X/' . $defaultLayout . '.php';
    $jsPath = JPATH_BASE . '/modules/' . $module . '/tmpl/' . $jversion . '/' . $defaultLayout . '.php';
    $dPath = JPATH_BASE . '/modules/' . $module . '/tmpl/' . $jversion . '/default.php';

    // If the template has a layout override use it
    if (file_exists($tPath) && ($temp[0] != '_')) {
        return $tPath;
    } elseif (file_exists($jdPath) && ($temp[0] != '_')) {
        return $jdPath;
    } elseif (file_exists($jsPath) && ($temp[0] != '_')) {
        return $jsPath;
    } else {
        return $dPath;
    }
}

require getModuleLayoutPath(substr(basename(__FILE__), 0, -4), $params->get('layout', 'default'));