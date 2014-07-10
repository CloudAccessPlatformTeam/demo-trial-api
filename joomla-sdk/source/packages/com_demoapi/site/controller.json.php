<?php

/**
 * @package   DemoApi
 * @author    CloudAccess.net LCC
 * @copyright (C) 2010 - CloudAccess.net LCC
 * @license   GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/api.php';

/**
 * Demo Register Controller for JSON format
 * 
 * @package     DemoApi
 * @subpackage  GitHub
 * @since       3.0
 */
class DemoApiController extends DRController
{
	/**
	 * check captcha validation
	 * 
	 * @return  interger code
	 */
	public function checkCaptcha()
	{
		$input = JFactory::getApplication()->input;
		$response_field = $input->get('response_field','','string');
		$challenge_field = $input->get('challenge_field','','string');

		if (isset($_SESSION['recaptcha']) && isset($_SESSION['challenge']) && $_SESSION['challenge'] == $challenge_field)
		{
			$code = 2;
		}
		else
		{
			if (!empty($challenge_field))
			{
				if (isset($_SESSION['challenge']))
				{
					$_SESSION['challenge'] = $challenge_field;
				}
				else if (isset($_SESSION['challenge']) && $_SESSION['challenge'] != $challenge_field)
				{
					$_SESSION['challenge'] = $challenge_field;
				}	
			}
			if (!empty($response_field))
			{
				jimport('joomla.application.component.helper');
	            $config = JComponentHelper::getParams('com_demoapi');
	            
	            require_once JPATH_COMPONENT.'/includes/recaptchalib.php';
	            $resp = recaptcha_check_answer($config->get('captcha_privatekey'),
				$_SERVER["REMOTE_ADDR"],
				$challenge_field,
				$response_field);
				if (!$resp->is_valid) 
				{
						# set the error code so that we can display it
						$code = 'Incorrect captcha words';
				}
				else {
					//ok
					$code = 2;
					$_SESSION['recaptcha'] = true;
				}
			}
			else 
			{
				$code = 1;
			}
		}
		
		echo json_encode($code);
		
		JFactory::getApplication()->close();
	}

    public function checkStatus()
    {
        $session = JFactory::getSession();
        $processes = $session->get('demoapi.process',array());
        $response = array();
        $remove = array();
        foreach ($processes as $id => $data) {
            if (empty($id)) {
                $remove[] = $id;
                continue;
            }
            $api_call = sprintf('%s/api',JComponentHelper::getParams('com_demoapi')->get('api_host'));
            $postData = array(
                'client_id' => JComponentHelper::getParams('com_demoapi')->get('api_user'),
                'token_type' => 'Bearer',
                'scope' => 'global',
                'method' => 'GetAsyncOpStatus',
                'p_opid' => $id,
                'access_token' => HelperDemoApiApi::getApiKey()
            );
            // Initinalise vars
            $options            = new JRegistry;
            $transport          = new JHttpTransportCurl($options);
            $http               = new JHttp($options, $transport);

            $httpResponse = $http->post($api_call, $postData);
            if ($httpResponse->code == 200) {
                $json = json_decode($httpResponse->body, true);

                if ($json['result']['status'] == 'failed') {
                    $remove[] = $id;
                    $processes[$id] = array_merge($data,array('status' => $json['result']['error']));
                } elseif ($json['result']['status'] == 'succeeded') {
                    $remove[] = $id;
                    $processes[$id] = array_merge($data,array('status' => $json['result']['status']));
                }
            } else {
                $remove[] = $id;
            }
        }

        $response = $processes;

        foreach ($remove as $id) {
            unset($processes[$id]);
        }
        $session->set('demoapi.process',$processes);

        echo json_encode($response);
        JFactory::getApplication()->close();
    }

    public function checkDomain()
    {
        $input = JFactory::getApplication()->input;
        $domain = $input->get('domain','','string');

        if (strpos($domain,'.cloudaccess.net') === false) {
            $domain .= '.cloudaccess.net';
        }

        $parse_domain = parse_url($domain);
        $return = HelperDemoApiApi::call(array(
            'method' => 'CheckDomainExistance',
            'p_domain' => $parse_domain['host'] ? $parse_domain['host'] : $parse_domain['path']
        ));
        echo json_encode($return);
        JFactory::getApplication()->close();
    }

    /**
     * Call datasets
     */
    public function listDatasets()
    {
        jimport( 'joomla.registry.registry' );

        $input = JFactory::getApplication()->input;
        $application = $input->get('application','','string');
        $family = $input->get('family','','string');
        $module_id = $input->get('mid',0,'int');
        $options = array();

        if (!empty($application) || empty($family) || $module_id) {

            $db = JFactory::getDbo();
            // get module and check access level permision
            $query = $db->getQuery(true);
            $groups = implode(',', JFactory::getUser()->getAuthorisedViewLevels());
            $query->select('params')->from('#__modules')->where('id='.$module_id)->where('access IN ('.$groups.')');
            $db->setQuery($query);

            $params = $db->loadResult();
            if (!empty($params)) {
                $moduleParams = new JRegistry($params);

                $cids = $moduleParams->get('cid');
                if (!empty($cids)) {
                    foreach ($cids as $cid) {
                        $parts = explode(';', $cid);
                        $value = $parts[0];
                        $app_family = end($parts);
                        if (strpos($app_family,$family) === false) continue;
                        $text = $parts[1];
                        $options[] = array(
                            'value' => $value,
                            'text' => $text
                        );
                    }
                }
            }
        }

        echo json_encode($options);
        JFactory::getApplication()->close();
    }

    public function checkEmail()
    {
        $input = JFactory::getApplication()->input;
        $email = $input->get('email','','string');

        $return = HelperDemoApiApi::call(array(
            'method' => 'CheckEmailExistance',
            'p_email' => $email
        ));
        echo json_encode($return);
        JFactory::getApplication()->close();
    }
}