<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_cloudaccessapi
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Banner table
 *
 * @package     Joomla.Administrator
 * @subpackage  com_cloudaccessapi
 * @since       1.5
 */
class CloudaccessapiTableActivationcode extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  &$_db  Database connector object
	 *
	 * @since   1.5
	 */
	public function __construct(&$_db)
	{
		parent::__construct('#__demoapi_activation_codes', 'code', $_db);
	}
}
