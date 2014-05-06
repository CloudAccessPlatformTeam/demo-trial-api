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
$cids = JComponentHelper::getParams('com_demoregister')->get('cid');
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
}

if (JVERSION < 3.0) {
    $doc = JFactory::getDocument();
    $doc->addScript('modules/'.$module->module.'/assets/js/jquery.js');
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
    }

    $jversion = substr(JVERSION, 0, 3);
    // Build the template and base path for the layout
    $tPath = JPATH_THEMES . '/' . $template . '/html/' . $module . '/' . $layout . '.php';
    $bPath = JPATH_BASE . '/modules/' . $module . '/tmpl/' . $jversion . '/' . $defaultLayout . '.php';
    $dPath = JPATH_BASE . '/modules/' . $module . '/tmpl/' . $jversion . '/default.php';

    // If the template has a layout override use it
    if (file_exists($tPath) && ($temp[0] != '_')) {
        return $tPath;
    } elseif (file_exists($bPath) && ($temp[0] == '_')) {
        return $bPath;
    } else {
        return $dPath;
    }
}

require getModuleLayoutPath(substr(basename(__FILE__), 0, -4), $params->get('layout', 'default'));