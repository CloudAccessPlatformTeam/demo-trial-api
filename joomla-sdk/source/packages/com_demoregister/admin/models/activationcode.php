<?php
/**
* @package Cloud Panel Component for Joomla!
* @author CloudAccess.net LCC
* @copyright (C) 2010 - CloudAccess.net LCC
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined( '_JEXEC' ) or die('Restricted access');

jimport('joomla.table.table');
jimport('joomla.application.component.modeladmin');

/**
 * 
 *
 * @package		Joomla.Administrator
 * @subpackage	com_demoregister
 */
class DemoregisterModelActivationcode extends JModelAdmin
{
	protected $text_prefix = 'COM_DEMOREGISTER';

	public function getForm($data = array(), $loadData = true)
	{
		return JModelForm::getForm($data, $loadData);
	}

	public function getTable($type = 'Activationcode', $prefix = 'DemoregisterTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
}