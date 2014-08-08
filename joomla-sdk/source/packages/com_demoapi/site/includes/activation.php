<?php
/**
 * @package    Joomla.Platform
 * @subpackage Demoapi
 * @author     Cloud Access <gpl@CloudAccess.net>
 * @copyright  Cloud Access 2010 - All rights re-served.
 * @license    GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Activation class helper
 *
 * @package     Joomla.Platform
 * @subpackage  Demoapi
 * @since       3.0
 */
class Activation
{
    /**
     * Generates an activation code
     *
     * @return  string  Activation code
     *
     * @since   3.0
     */
	static function generate()
	{
		$dict = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		srand((double)microtime()*1000000);
		$pass = '';

		for($i = 0; $i < rand(16,20); $i++)
		{
			$pass .= $dict[rand(0, strlen($dict) - 1)];
		}

		return $pass;
	}

    /**
     * Generate activation url
     *
     * @param   string  $params  Array data
     *
     * @return  string  Activation url
     *
     * @since   3.0
     */
	static function generateURL($code)
	{
		$config = JComponentHelper::getParams('com_demoapi');
        $root_url = JFactory::getURI()->root();
        if (substr($root_url,-1) != '/') {
            $root_url .= '/';
        }
        $activation_link = $config->get('activation_url',$root_url);
        if (substr($activation_link,-1) != '/') {
            $activation_link .= '/';
        }
		return $activation_link.'index.php?option=com_demoapi&task=activate&code=' . urlencode($code);
	}

    /**
     * Method to get the name of the class.
     *
     * @param   string  $params  Array data
     *
     * @return  boolean  True if successfully loaded, false otherwise.
     *
     * @since   3.0
     */
	static function get($params)
	{
		$db = JFactory::getDBO();

		$code = self::generate();
		$strparams = serialize($params);

		$q = sprintf("INSERT INTO #__demoapi_activation_codes(`code`,`params`) VALUES(%s,%s);", $db->quote($code), $db->quote($strparams));
		$db->setQuery($q);
		if(!$db->query())
		{
			$f = fopen('/var/log/domaincreate.log', 'a');
			fwrite($f, 'Could not insert code "' . $code . '" into db: ' . $db->getErrorMsg() . "\n");
			fclose($f);
		}

		$rv = self::generateURL($code);
		return $rv;
	}

    /**
     * Find code in DB, delete it, return $params passed to get()
     *
     * @param   string  $code  Activation code string
     *
     * @return  boolean  params passed to get() loaded, false otherwise.
     *
     * @since   3.0
     */
	static function use_code($code)
	{
		$db = JFactory::getDBO();

		$q = sprintf("SELECT `params` FROM #__demoapi_activation_codes WHERE `code`= %s", $db->quote($code));
		$db->setQuery($q);
		$strparams = $db->loadResult();
		if($strparams)
		{
			$params = unserialize($strparams);
			$q = sprintf("DELETE FROM #__demoapi_activation_codes WHERE `code`= %s", $db->quote($code));
#			$db->setQuery($q);
#			$db->query();
			return $params;
		}
		else
		{
			return false;
		}
	}

	/**
     * Deletes code older than $age
     *
     * @param   string  $age  SQL interval expression (eg. '2 WEEK')
     *
     * @return  boolean  params passed to get() loaded, false otherwise.
     *
     * @since   3.0
     */
	static function delete_older_than($age = '2 WEEK')
	{
		$db = JFactory::getDBO();

		$q = sprintf("DELETE FROM #__demoapi_activation_codes WHERE `created` < DATE_SUB(NOW(), INTERVAL %s)",$age);
		$db->setQuery($q);
		$db->query();
		die(mysql_error());
	}
}

