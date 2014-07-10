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
jimport('joomla.application.component.modellist');

/**
 * 
 *
 * @package		Joomla.Administrator
 * @subpackage	com_demoapi
 */
class DemoapiModelActivationcodes extends JModelList
{
	protected function getListQuery()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$table = $this->getTable();

		$query->select(implode(', ',array_keys($table->getFields())))->from($table->getTableName())->where('created < NOW()');

		return $query;
	}

	public function getTable($type = 'Activationcode', $prefix = 'DemoapiTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
}