<?php
class HelperCloudaccessApiApi
{
    static public function getApiKey()
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->select('access_token')
              ->from('#__demoapi_authentication')
              ->setLimit(1);
        $db->setQuery($query);
        $token = $db->loadResult();

        if (is_null($token)) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->delete('#__demoapi_authentication');
            $db->setQuery($query);
            $db->execute();
        }

        return $token;
    }

    static public function call($data, $try = 1)
    {
        // Initinalise vars
        $options            = new JRegistry;
        $transport          = new JHttpTransportCurl($options);
        $http               = new JHttp($options, $transport);

        $api_call = sprintf('%s/api',JComponentHelper::getParams('com_cloudaccessapi')->get('api_host'));
        $postData = array(
            'client_id' => JComponentHelper::getParams('com_cloudaccessapi')->get('api_user'),
            'token_type' => 'Bearer',
            'scope' => 'global',
            'access_token' => self::getApiKey()
        );

        $postData = array_merge($postData, $data);

        try {
            $statusResponse = $http->post($api_call,$postData);
        } catch (Exception $e) {
            JFactory::getApplication()->enqueueMessage(sprintf('Error when try to connect with api host "%s", please verify url.',$api_call),'error');
            return false;
        }

        $json = json_decode($statusResponse->body, true);

        if ($statusResponse->code != 200) {
            if ($json['err_code'] == 1 && $try == 1) {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->delete('#__demoapi_authentication');
                $db->setQuery($query);
                $db->execute();
                if (self::createApiKey(array(
                    'params' => array(
                        'api_user' => JComponentHelper::getParams('com_cloudaccessapi')->get('api_user'),
                        'api_host' => JComponentHelper::getParams('com_cloudaccessapi')->get('api_host'),
                        'api_password' => JComponentHelper::getParams('com_cloudaccessapi')->get('api_password')
                    )
                ))) {
                    $try++;
                    return self::call($data, $try);
                }
            }
            JFactory::getApplication()->enqueueMessage($json['error'],'error');
            return $json;
        } else {
            if (empty($json)) {
                if ($try == 1) {
                    $db = JFactory::getDbo();
                    $query = $db->getQuery(true);
                    $query->delete('#__demoapi_authentication');
                    $db->setQuery($query);
                    $db->execute();
                    if (self::createApiKey(array(
                        'params' => array(
                            'api_user' => JComponentHelper::getParams('com_cloudaccessapi')->get('api_user'),
                            'api_host' => JComponentHelper::getParams('com_cloudaccessapi')->get('api_host'),
                            'api_password' => JComponentHelper::getParams('com_cloudaccessapi')->get('api_password')
                        )
                    ))) {
                        $try++;
                        return self::call($data, $try);
                    }
                } else {
                    JFactory::getApplication()->enqueueMessage('Please contact www.cloudaccess.net and copy this message: Empty response from api','error');
                }
            }

            if (isset($json['error'])) {
                JFactory::getApplication()->enqueueMessage($json['error'],'error');
                return $json;
            } else {
                return $json['result'];
            }
        }
    }

    public static function createApiKey($data)
    {
        $api_host = trim($data['params']['api_host']);
        $api_user = trim($data['params']['api_user']);
        $api_pass = trim($data['params']['api_password']);

        if (empty($api_host) || empty($api_user) || empty($api_pass)) {
            JFactory::getApplication()->enqueueMessage('Please fill api fields!','warning');
            return false;
        }

        $first_step = sprintf('%s/oauth/authorization?client_id=%s&response_type=code',$api_host,$api_user);

        // Initinalise vars
        $options            = new JRegistry;
        $transport          = new JHttpTransportCurl($options);
        $http               = new JHttp($options, $transport);

        try {
            $response = $http->get($first_step);
        } catch (Exception $e) {
            JFactory::getApplication()->enqueueMessage(sprintf('Error when try to connect with api host "%s", please verify url.',$api_host),'error');
            return false;
        }

        $arguments = array();

        if ( !in_array($response->code, array(200, 302, 301))) {
            $json = json_decode($response->body, true);
            JFactory::getApplication()->enqueueMessage(sprintf('%s Error: %s',$response->code,$json['error']),'error');
            return false;
        } else if ($response->code == 302 || $response->code == 301) {
            $locationUri = parse_url($response->headers['Location']);
            parse_str($locationUri['query'], $arguments);
        } else if ($response->code == 200) {
            $arguments = json_decode($response->body, true);
        }

        if (!empty($arguments)) {
            if (!isset($arguments['scope'])) {
                $arguments['scope'] = 'code';
            }

            $post_data = array(
                'code' => $arguments['code'],
                'client_id' => $api_user,
                'client_secret' => $api_pass,
                'scope' => $arguments['scope'],
                'grant_type' => 'authorization_code'
            );

            $second_step = sprintf('%s/oauth/token',$api_host);

            $oauth = $http->post($second_step, $post_data);

            if ($oauth->code == 200) {
                $json = json_decode($oauth->body, true);
                $values = array_values($json);

                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->insert('#__demoapi_authentication');
                $query->values('0,"'.$values[3].'","'.$values[0].'","'.$values[2].'","'.$values[1].'",NOW()');
                $db->setQuery($query);
                $db->execute();
                return true;
            }
            else
            {
                $json = json_decode($oauth->body, true);
                $values = array_values($json);

                JFactory::getApplication()->enqueueMessage($values[0],'error');
                return false;
            }
        }


        return false;
    }

    public static function updateEmailTemplates($data)
    {
        $j_template_id = $data['params']['joomla_welcome_template_id'];
        $joomlaTemplateData = [
            'method' => 'SetEmailTemplate',
            'p_fromname' => $data['params']['fromname'],
            'p_fromemail' => $data['params']['from'],
            'p_name' => '[API module] Free Joomla welcome email',
            'p_subject' => $data['params']['joomla_welcome_subject'],
            'p_message' => $data['params']['joomla_welcome_body_content'],
            'p_product' => 'joomla',
        ];
        if ($j_template_id) {
            $joomlaTemplateData['p_id'] = $j_template_id;
        }
        $result = self::call($joomlaTemplateData);
        if (!is_array($result) && !$j_template_id) {
            $j_template_id = $result;
        }

        $w_template_id = $data['params']['joomla_welcome_template_id'];
        $wordpressTemplateData = [
            'method' => 'SetEmailTemplate',
            'p_fromname' => $data['params']['fromname'],
            'p_fromemail' => $data['params']['from'],
            'p_name' => '[API module] Free Wordpress welcome email',
            'p_subject' => $data['params']['wordpress_welcome_subject'],
            'p_message' => $data['params']['wordpress_welcome_body_content'],
            'p_product' => 'wordpress'
        ];
        if ($w_template_id) {
            $wordpressTemplateData['p_id'] = $w_template_id;
        }
        $result = self::call($wordpressTemplateData);
        if (!is_array($result) && !$w_template_id) {
            $w_template_id = $result;
        }

        return [$j_template_id, $w_template_id];
    }
}
