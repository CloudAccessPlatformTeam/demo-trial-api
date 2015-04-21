<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_config
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');

require_once JPATH_COMPONENT.'/helpers/api.php';

/**
 * @package		Joomla.Administrator
 * @subpackage	com_cloudaccessapi
 */
class CloudaccessApiModelCloudaccessApi extends JModelForm
{
    /**
     * JHttp
     *
     * @var     JHttp
     * @since   1.1
     */
    protected $http;

    /**
     * @var     JApplication
     * @since   1.1
     */
    protected $application;

    /**
     * JCache
     *
     * @var     JCache
     * @since   1.1
     */
    protected $cache;

    /**
     * Override constructor
     *
     * @param   array $config
     *
     * @since   1.1
     */
    public function __construct($config = array())
    {
        parent::__construct($config);

        // Initinalise vars
        $options            = new JRegistry;
        $transport          = new JHttpTransportCurl($options);
        $this->http         = new JHttp($options, $transport);
        $this->application  = JFactory::getApplication();
        $this->cache        = JCache::getInstance('output',array('defaultgroup' => 'com_cloudaccessapi'));

        // Set api_key
        $this->setState('api_key', HelperCloudaccessApiApi::getApiKey());
    }

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return	void
	 * @since	1.6
	 */
	protected function populateState()
	{
		// Set the component (option) we are dealing with.
		$component = JFactory::getApplication()->input->get('option');
		$this->setState('component.option', $component);

		// Set an alternative path for the configuration file.
		if ($path = JFactory::getApplication()->input->get('path')) {
			$path = JPath::clean(JPATH_SITE . '/' . $path);
			JPath::check($path);
			$this->setState('component.path', $path);
		}
	}

	/**
	 * Method to get a form object.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 *
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		if ($path = $this->getState('component.path')) {
			// Add the search path for the admin component config.xml file.
			JForm::addFormPath($path);
		}
		else {
			// Add the search path for the admin component config.xml file.
			JForm::addFormPath(JPATH_ADMINISTRATOR.'/components/'.$this->getState('component.option'));
		}

		// Get the form.
		$form = $this->loadForm(
				'com_cloudaccessapi.component',
				'config',
				array('control' => 'jform', 'load_data' => $loadData),
				false,
				'/config'
			);

		if (empty($form)) {
			return false;
		}
		
		$result = $this->getComponent();
		$form->bind($result->params);

		return $form;
	}

	/**
	 * Get the component information.
	 *
	 * @return	object
	 * @since	1.6
	 */
	public function getComponent()
	{
		// Initialise variables.
		$option = $this->getState('component.option');

		// Load common and local language files.
		$lang = JFactory::getLanguage();
			$lang->load($option, JPATH_BASE, null, false, false)
		||	$lang->load($option, JPATH_BASE . "/components/$option", null, false, false)
		||	$lang->load($option, JPATH_BASE, $lang->getDefault(), false, false)
		||	$lang->load($option, JPATH_BASE . "/components/$option", $lang->getDefault(), false, false);

        //load component
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('extension_id AS id, element AS "option", params, enabled')
            ->from('#__extensions')
            ->where($db->quoteName('type') . ' = ' . $db->quote('component'))
            ->where($db->quoteName('element') . ' = ' . $db->quote($option));
        $db->setQuery($query);
        $result = $db->loadObject();

        $result->params = json_decode($result->params, true);

		return $result;
	}

	/**
	 * Method to save the configuration data.
	 *
	 * @param	array	An array containing all global config data.
	 *
	 * @return	bool	True on success, false on failure.
	 * @since	1.6
	 */
	public function save($data)
	{
		$table	= JTable::getInstance('extension');

		// Save the rules.
		if (isset($data['params']) && isset($data['params']['rules'])) {
			$rules	= new JAccessRules($data['params']['rules']);
			$asset	= JTable::getInstance('asset');

			if (!$asset->loadByName($data['option'])) {
				$root	= JTable::getInstance('asset');
				$root->loadByName('root.1');
				$asset->name = $data['option'];
				$asset->title = $data['option'];
				$asset->setLocation($root->id, 'last-child');
			}
			$asset->rules = (string) $rules;

			if (!$asset->check() || !$asset->store()) {
				$this->setError($asset->getError());
				return false;
			}

			// We don't need this anymore
			unset($data['option']);
			unset($data['params']['rules']);
		}

		// Load the previous Data
		if (!$table->load($data['id'])) {
			$this->setError($table->getError());
			return false;
		}

		unset($data['id']);

		$data['params']['api_host'] = trim($data['params']['api_host']);
		$parse_url = parse_url($data['params']['api_host']);
		// add port check for api.cloudaccess.net
		if (strpos($data['params']['api_host'],'api.cloudaccess.net') !== false) {
			if (empty($parse_url['port'])) {
				$parse_url['port'] = 9000;
			}
		}
		$data['params']['api_host'] = http_build_url('', $parse_url);

		// Bind the data.
		if (!$table->bind($data)) {
			$this->setError($table->getError());
			return false;
		}

		// Check the data.
		if (!$table->check()) {
			$this->setError($table->getError());
			return false;
		}

        $api_key = $this->getState('api_key');
        if (is_null($api_key) && !empty($parse_url['scheme'])) {
    		if (!HelperCloudaccessApiApi::createApiKey($data)) {
    			JFactory::getApplication()->enqueueMessage('Cant create API keys','error');
    		} else {
    			JFactory::getApplication()->enqueueMessage('Your Token has been created','success');
    		}
        }

		// Store the data.
		if (!$table->store()) {
			$this->setError($table->getError());
			return false;
		}

		// Clean the component cache.
		$this->cleanCache('_system');

		return true;
	}
}
