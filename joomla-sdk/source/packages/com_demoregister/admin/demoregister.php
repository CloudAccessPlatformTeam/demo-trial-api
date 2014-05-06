<?php
/**
 * @package Demo Register Component for Joomla!
 * @author CloudAccess.net LCC
 * @copyright (C) 2010 - CloudAccess.net LCC
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

require_once 'includes/compatibility.php';

// Execute the task.
$controller	= DRController::getInstance('demoregister');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();