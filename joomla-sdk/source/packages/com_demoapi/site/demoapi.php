<?php
/**
 * @package Demo Register Component for Joomla!
 * @author CloudAccess.net LCC
 * @copyright (C) 2010 - CloudAccess.net LCC
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
ini_set('display_errors', false);
// no direct access
defined('_JEXEC') or die;

require_once JPATH_COMPONENT_ADMINISTRATOR.'/includes/compatibility.php';

// Execute the task.
$controller	= DRController::getInstance('demoapi');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();