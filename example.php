<?php

/**
 * @name Demo Trial API PHP-SDK Example
 * @version 1.0
 * @author Cloudaccess.net
 *
 */
 
require_once (__DIR__.'/demoapi.php');

$dapi = new DemoApi(array(
	'client_id' => 'user@domain.com',
	'client_secret' => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXX',
	'verify_ssl' => false
));

try
{
	// Get the tokens
	var_dump($dapi->login());
	
	// Refresh if token is close to expiry
	// $dapi->refresh_token();
	
	// Make call
	var_dump($dapi->api('ListDatasets', array(
		'p_application' => 'joomla'
	)));
}
catch (DemoApiException $e)
{
	// Handle errors
	echo 'Error: '.$e->getCode().' -> '.$e->getMessage();
}
