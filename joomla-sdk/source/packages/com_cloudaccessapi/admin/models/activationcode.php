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
 * @subpackage	com_cloudaccessapi
 */
class CloudaccessapiModelActivationcode extends JModelAdmin
{
	protected $text_prefix = 'COM_CLOUDACCESSAPI';

	public function getForm($data = array(), $loadData = true)
	{
		return JModelForm::getForm($data, $loadData);
	}

	public function getTable($type = 'Activationcode', $prefix = 'CloudaccessapiTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
}