<?php
/**
 * @package    Joomla.Platform
 *
 * @copyright  Copyright (C) 2000 - 2013 CloudAccess.net. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Create Site Helper class
 *
 * @package  Joomla.Platform
 * @since    11.1
 */
class createSiteHelper
{
	/**
	 * Get a JForm object.
	 *
	 * Returns the global {@link JForm} object, only creating it if it doesn't already exist.
	 *
	 * @param   name    $name    A form identifier or name.
	 *
	 * @return  JForm object
	 *
	 * @see     JForm
	 * @since   
	 */
	public function getForm()
	{
		// add form paths
		JForm::addFormPath(dirname(__FILE__).'/forms');
		JForm::addFieldPath(dirname(__FILE__).'/fields');

		$jForm = JForm::getInstance('site','site');

		//bind data to form
		$jForm->bind( JFactory::getSession()->get('demoapi') );

		return $jForm;
	}

	/**
	 * Initialise jQuery
	 * 
	 * @return  void
	 * 
	 * @since   
	 */
	public function initialise()
	{
		
	}

	/**
	 * Return 1 if catpcha is enabled
	 * 
	 * @return  integer
	 * 
	 * @since   
	 */
	public function captchaIsEnabled()
	{
		$comParams = JComponentHelper::getParams('com_demoapi');
		return $comParams->get('captcha_enabled',0);
	}

	/**
	 * Return activation url
	 * 
	 * @return  integer
	 * 
	 * @since   
	 */
	public function getActivationURL()
	{
		$comParams = JComponentHelper::getParams('com_demoapi');
		return $comParams->get('activation_url',(string)JFactory::getUri());
	}

	/**
	 * Return captcha public key
	 * 
	 * @return  string
	 * 
	 * @since   
	 */
	public function getCaptchaPublicKey()
	{
		$comParams = JComponentHelper::getParams('com_demoapi');
		return trim( $comParams->get('captcha_publickey') );
	}

	/**
	 * Return captcha width
	 * 
	 * @return  integer
	 * 
	 * @since   
	 */
	public function getCaptchaWidth()
	{
		$comParams = JComponentHelper::getParams('com_demoapi');
		return $comParams->get('captcha_width');
	}

	/**
	 * Return captcha height
	 * 
	 * @return  integer
	 * 
	 * @since   
	 */
	public function getCaptchaHeight()
	{
		$comParams = JComponentHelper::getParams('com_demoapi');
		return $comParams->get('captcha_height');
	}
}