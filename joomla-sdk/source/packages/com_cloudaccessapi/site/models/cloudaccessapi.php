<?php
/**
 * Websockets class
 * @package     Cloud Panel Component for Joomla!
 * @author      CloudAccess.net LCC
 * @copyright   (C) 2010 - CloudAccess.net LCC
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_SITE.'/includes/activation.php';
require_once JPATH_COMPONENT_SITE.'/includes/validation.php';
require_once JPATH_COMPONENT_SITE.'/includes/recaptchalib.php';
require_once JPATH_COMPONENT_SITE.'/helpers/module.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/api.php';


/**
 * Demo Register Controller for JSON format
 *
 * @package     CloudaccessApi
 * @subpackage  models
 * @since       3.0
 */
class CloudaccessApiModelCloudaccessApi extends DRModel
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
        $this->setState('access_token', HelperCloudaccessApiApi::getApiKey());
    }

    /**
     * check Domain
     *
     * @return  1 if exists, 2 for Connection error else success
     *
     * @since   11.3
     */
    public function error($message)
    {
        $this->componentConfig = JComponentHelper::getParams('com_cloudaccessapi');
        $url = JFactory::getApplication()->input->get('return',base64_encode($this->componentConfig->get('error_redirect_page','index.php')));
        $url = base64_decode($url);

        if (!JFactory::getUri()->isInternal($url))
        {
            $message = 'invalid url';
        }
        JFactory::getApplication()->redirect($url,$message,'error');
    }

    /**
     * Save and create
     *
     * @return  1 if exists, 2 for Connection error else success
     *
     * @since   11.3
     */
    public function save()
    {
        // Grab session
        $session = JFactory::getSession();
        $msg = NULL;
        $level = 'message';
        $privatekey = "6LcvxgsAAAAAAHbPNy3zCZKegQQ3Gsmgp9Y0U4Q3";
        $post_array = array();
        $input = JFactory::getApplication()->input;

        $componentParams = JComponentHelper::getParams('com_cloudaccessapi');

        $post_array["error_msg"] = array();

        // moved to top
        $post_array["posted_email"] = trim($input->get('email','','string'));

        //validate email
        if($post_array["posted_email"] == "") {
            $post_array["error_msg"]["email"] = "Wrong e-mail.";
        } elseif(!preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i", $post_array["posted_email"])) {
            //validate correct email id
            $post_array["error_msg"]["email"] = "Wrong e-mail.";
        }

        // check if user are already registered in system
        $userRegistred = false;
        // if not errors check
        if (!isset($post_array["error_msg"]["email"])) {
            $userRegistred = $this->checkEmail($post_array["posted_email"]);
        }

        // site name
        $post_array["posted_sname"] = strtolower(trim($input->get('sitename')));

        //validate sitename
        if ($post_array["posted_sname"] == "sitename"){
            $post_array["error_msg"]["sitename"] = "Please choose your domain name.";
        }
        else if($post_array["posted_sname"] == "")
        {
            $post_array["error_msg"]["sitename"] = "Please delete spaces in you domain name.";
        }
        else
        {
            //check for special characters
            if(!preg_match("/^[a-z0-9][a-z0-9\-]*[a-z0-9]$/i", $post_array["posted_sname"] ))
            {
                $post_array["error_msg"]["sitename"] = "Please delete spaces in you domain name.";
            }

            if (strpos($post_array["posted_sname"],'.') !== false && strpos($post_array["posted_sname"],'.cloudaccess.net') === false) {
                $post_array["error_msg"]["sitename"] = "Please delete spaces in you domain name.";
            }
        }

        $fullname = $input->get('fullname','','string');
        if ($fullname == 'Full Name') {
            $post_array["error_msg"]["fullname"] = "Please type a Full Name";
            $fullname = '';
        }

        $lastname = '';
        $nameParts = explode(' ',$fullname);
        if (count($nameParts) == 1 && empty($nameParts[1]))
        {
            $lastname = '';
        }
        else
        {
            $firstname_len = strlen($nameParts[0]) + 1;
            $lastname = substr($fullname, $firstname_len);
        }

        $post_array["posted_prodid"] = $input->get('productid',102,'int');
        $post_array["posted_fname"] = trim($input->get('firstname',$nameParts[0],'string'));
        $post_array["posted_lname"] = trim($input->get('lastname',$lastname,'string'));
        $post_array["posted_phnum"] = trim($input->get('phonenumber','','string'));
        $post_array["posted_zip"] = trim($input->get('postcode','','string'));
        $post_array["posted_cntry"] = trim($input->get('country','','string'));
        $post_array["posted_state"] = trim($input->get('state','','string'));
        $post_array["posted_city"] = trim($input->get('city','','string'));
        $post_array["posted_postcode"] = trim($input->get('postcode','','string'));
        $post_array["posted_cname"] = trim($input->get('cname','','string'));
        $post_array["posted_csize"] = trim($input->get('companysize','','string'));
        $post_array["posted_tos"] = trim($input->get('tos',0,'int'));
        $post_array["dataset"] = trim($input->get('dataset','','string'));
        $post_array["application"] = trim($input->get('application','joomla','string'));

        //replace default values
        if ($post_array["posted_city"] == "City(Optional)") {
            $post_array["posted_city"] = "";
        }
        if ($post_array["posted_state"] == "State(Optional)") {
            $post_array["posted_state"] = "";
        }
        if ($post_array["posted_postcode"] == "Post code") {
            $post_array["posted_postcode"] = "";
        }

        //validate firstname
        if($post_array["posted_fname"] == "")
        {
            $post_array["error_msg"]["fname"] = "2 chars minimum, 50 maximum, only letters.";
        } else {
            //check for special characters
            if(!preg_match("/^[^\'\"\^0-9]{2,50}$/i", $post_array["posted_fname"] )) {
                $post_array["error_msg"]["fname"] = "2 chars minimum, 50 maximum, only letters.";
            }
        }

        if ($post_array["posted_phnum"] == 'PhoneNumber(Optional)')
        {
            $post_array["posted_phnum"] = "";
        }
        // validate phonenumber if passed
        if($post_array["posted_phnum"]  !=  "")
        {
            $tmphoneholder = $post_array["posted_phnum"];
            //replace string vals from phone number if submitted
            $tmphoneholder = str_replace("(", "", $tmphoneholder);
            $tmphoneholder = str_replace(")", "", $tmphoneholder);
            $tmphoneholder = str_replace("-", "", $tmphoneholder);
            $tmphoneholder = str_replace(" ", "", $tmphoneholder);
            $tmphoneholder = str_replace("+", "", $tmphoneholder);

            if(!preg_match("/^[0-9]{6,25}$/i", $tmphoneholder )) {
                $post_array["error_msg"]["phnum"] = "6 numbers minimum, 25 maximum.";
            }

        }

        //validate zip code
        $post_array["posted_zip"] = '00 000000';

        //validate country
        if($post_array["posted_cntry"] == "empty") {
            $post_array["error_msg"]["cntry"] = "Please choose option from list.";
        }

        // if user registered reset errors from fields exception if get error from sitename
        if ($userRegistred && !isset($post_array["error_msg"]["sitename"])) {
            $post_array["error_msg"] = array();
        }

        //validate captchs
        jimport('joomla.application.component.helper');
        $config = JComponentHelper::getParams('com_cloudaccessapi');
        if($config->get('captcha_enbaled') == 1) {
            if ($_POST["recaptcha_response_field"]) {

                $resp = recaptcha_check_answer($config->get('captcha_privatekey'),
                    $_SERVER["REMOTE_ADDR"],
                    $_POST["recaptcha_challenge_field"],
                    $_POST["recaptcha_response_field"]);
                if (!$resp->is_valid) {
                    # set the error code so that we can display it
                    $post_array["error_msg"]["captcha"] = "Captcha is Incorrect.";
                }
            } else {
                $post_array["error_msg"]["captcha"] = "Captcha is Incorrect.";
            }
        }

        //validate tos
        if($post_array["posted_tos"] == 0) {
            $post_array["error_msg"]["tos"] = "You have to agree with our terms of service.";
        }

        //save data in session for form in case of redirect due to error
        $session->set('cloudaccessapi',  $post_array);

        //check if any validation error exists or not
        if( !sizeof($post_array["error_msg"]) ) {
            $session->clear('cloudaccessapi');
            /* Extract parameters needed by cloudaccessapi */

            $params = cloudaccessapi_getparams();
            /* Get URL, store $params */

            $activation_url = Activation::get($params);

            $f = fopen(JPATH_ROOT . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'generatedcode.log', 'a');
            $email = $post_array["posted_email"];
            fwrite($f, "$activation_url\t{$params['email']}\n");
            fclose($f);
            /* Load email template */
            switch ($componentParams->get('template_engine',0))
            {
                case 1:
                    $aid = $componentParams->get('body_aid',0);
                    if (intval($aid) == 0) {
                            //get text area if no content selected
                        $body = $componentParams->get('body_content');
                    } else {
                        $db = JFactory::getDbo();
                        $query = $db->getQuery(true);
                        $query->select('CONCAT(a.introtext,"",a.fulltext) AS text')->from('#__content AS a')->where('a.id='.$db->quote($aid));
                        $db->setQuery($query);
                        $body = $db->loadResult();
                    }

                    if (empty($body)) {
                        $body = $componentParams->get('body_content');
                    }

                    break;
                case 0:
                default:
                    $body = $componentParams->get('body_content');
                    break;
            }

            if (empty($post_array["posted_fname"])) {
                $emailParts = explode('@',$email);
                $post_array["posted_fname"] = $emailParts[0];
            }

            $tpl_params = array(
                '%FIRSTNAME%' => htmlspecialchars($post_array["posted_fname"]),
                '%LINK%' => '<a href="' . htmlspecialchars($activation_url) . '">'.$activation_url.'</a>',
            );
            $body = str_replace(array_keys($tpl_params), array_values($tpl_params), nl2br($body));

            //replace /r
            $body = str_replace('\r','',$body);
            //replace /n
            $body = str_replace('\n','<br />',$body);


            $from = $componentParams->get('from');
            $fromname = $componentParams->get('fromname');
            $recipient = array($email);
            $subject = $componentParams->get('subject');
            return JFactory::getMailer()->sendMail($from, $fromname, $recipient, $subject, $body, 1);
        } else {
            $this->error(implode('<br />',array_values($post_array["error_msg"])));
        }
    }

    /**
     * Check if email already exists
     */
    private function checkEmail($email)
    {
        $return = HelperCloudaccessApiApi::call(array(
            'method' => 'CheckEmailExistance',
            'p_email' => $email
        ));

        return $return;
    }

    /**
     * check if a domain already exists
     */
    public function checkDomain($domain)
    {
        $parse_domain = parse_url($domain);
        $return = HelperCloudaccessApiApi::call(array(
            'method' => 'CheckDomainExistance',
            'p_domain' => $parse_domain['host'] ? $parse_domain['host'] : $parse_domain['path']
        ));

        return $return;
    }

    /**
     * Activate domain
     *
     * @return  Boolean
     *
     * @since   3.0
     */
    public function activate()
    {
        // Grab session
        $session = JFactory::getSession();
        $post_array = $session->get('cloudaccessapi');
        $code = !empty($_REQUEST['code']) ? $_REQUEST['code'] : false ;
        $componentParams = JComponentHelper::getParams('com_cloudaccessapi');
        JLoader::import('helpers.api', JPATH_ADMINISTRATOR.'/components/com_cloudaccessapi');

        if ($code) {
            $params = Activation::use_code($code);

            if ($params) {
                $params['phonenumber'] = str_replace("+", "", $params['phonenumber']);

                $client_details = array(
                    'p_firstname' => $params["firstname"],
                    'p_lastname' => $params["lastname"],
                    // 'companyname' => (string)$params["companyname"],
                    'p_email' => $params["email"],
                    'p_address1' => $params["address"],
                    // 'address2' => $params["address2"],
                    'p_city' => empty($params["city"]) ? 'Default City' : $params["city"] ,
                    'p_state' => empty($params["state"]) ? 'default state' : $params["state"] ,
                    'p_postcode' => empty($params["postcode"]) ? '00112233' : (string)$params["postcode"],
                    'p_country' => $params["country"],
                    'p_phonenumber' => empty($params["phonenumber"]) ? '00 98762342' : $params["phonenumber"],
                    'p_password' => $params["password2"]
                );
                $demo_details = array(
                    'p_domain' => $params["sitename"],
                    'p_application' => empty($params["application"]) ? 'joomla' : $params["application"],
                    'p_datasetid' => $params["dataset"],
                    'p_pid' => NULL
                );

                //collect product id as per application and value settted in API settings in CCP
                $create_application = strtolower(trim($demo_details['p_application']));
                $listdatasetsResp = HelperCloudaccessApiApi::call(array('method' => 'ListDatasets', 'p_application' => $create_application));

                if(is_array($listdatasetsResp['products'][$create_application]))
                {
                    $demo_details['p_pid'] = $listdatasetsResp['products'][$create_application][0];
                }

                $api_details = array(
                    'client_id' => JComponentHelper::getParams('com_cloudaccessapi')->get('api_user'),
                    'token_type' => 'Bearer',
                    'method' => 'CreateService',
                    'scope' => 'global',
                    'grant_type' => 'authorization_code',
                    'access_token' => $this->getState('access_token')
                );

                //automaticaly add subdomain
                $subdomain = $params['subdomain'];
                if (strpos($demo_details['p_domain'],$subdomain) === false) {
                    $demo_details['p_domain'] .= $subdomain;
                }

                // remove current code from database
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->delete('#__demoapi_activation_codes')->where('code='.$db->quote($code));
                $db->setQuery($query);
                $db->execute();

                // check if domain exists
                if ($this->checkDomain($demo_details["p_domain"])) {
                    JFactory::getApplication()->enqueueMessage(sprintf('The URL %s that you had choosen during signup has now been taken. Please start the signup process over and choose a new URL.',$demo_details['p_domain']),'error');
                    return fasle;
                }

                $postData = array_merge($client_details, $demo_details, $api_details);

                $api_call = sprintf('%s/api',JComponentHelper::getParams('com_cloudaccessapi')->get('api_host'));
                $createServiceResponse = $this->http->post($api_call,$postData);

                if ($createServiceResponse->code != 200) {
                    $json = json_decode($createServiceResponse->body);
                    $session->set('cloudaccessapi',  $post_array);
                    JFactory::getApplication()->enqueueMessage($json->error,'error');
                    return false;
                } else {
                    $json = json_decode($createServiceResponse->body);

                    $postData = array(
                        'client_id' => JComponentHelper::getParams('com_cloudaccessapi')->get('api_user'),
                        'token_type' => 'Bearer',
                        'scope' => 'global',
                        'method' => 'GetAsyncOpStatus',
                        'p_opid' => $json->taskid,
                        'access_token' => $this->getState('access_token')
                    );

                    $statusResponse = $this->http->post($api_call,$postData);

                    if ($statusResponse->code != 200) {
                        $json = json_decode($statusResponse->body);
                        $session->set('cloudaccessapi',  $post_array);
                        JFactory::getApplication()->enqueueMessage($json->error,'error');
                        return false;
                    } else {
                        $json = json_decode($statusResponse->body, true);

                        if ($json['result']['status'] == 'failed') {
                            $session->set('cloudaccessapi',  $post_array);
                            JFactory::getApplication()->enqueueMessage(nl2br($json['result']['error']),'error');
                            return false;
                        } else {
                            // add to queue process
                            $process = $session->get('cloudaccessapi.process',array());
                            $process[$postData['p_opid']] = array('status' => $json['result']['status'],'site' => $demo_details['p_domain']);
                            $session->set('cloudaccessapi.process', $process);
                            // JFactory::getApplication()->enqueueMessage(sprintf('Your request to %s is %s',$demo_details['p_domain'],nl2br($json['result']['status'])),'info');
                            //unset all sessions posted to blank starts if no error exists
                            $session->clear('cloudaccessapi');
                            return true;
                        }
                    }
                }
            } else {
                JFactory::getApplication()->enqueueMessage('Activation code was already used/expired.','error');
                $f = fopen(JPATH_ROOT . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'badcode.log', 'a');
                fwrite($f, "$code\n");
                fclose($f);
                // throw new Exception('Bad Code');
                return false;
            }
        } else {
            JFactory::getApplication()->enqueueMessage('Missing activation code!','error');
            return false;
        }

        return false;
    }

    /**
     * check Domain
     *
     * @return  1 if exists, 2 for Connection error else success
     *
     * @since   11.3
     */
    public function cron()
    {
        Activation::delete_older_than('1 WEEK');
    }
}
