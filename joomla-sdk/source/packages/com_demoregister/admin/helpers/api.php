<?php
class HelperDemoRegisterApi
{
    static public function getApiKey()
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->select('access_token')
              ->from('#__demoregister_authentication')
              ->setLimit(1);
        $db->setQuery($query);
        $token = $db->loadResult();

        if (is_null($token)) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->delete('#__demoregister_authentication');
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

        $api_call = sprintf('%s/api',JComponentHelper::getParams('com_demoregister')->get('api_host'));
        $postData = array(
            'client_id' => JComponentHelper::getParams('com_demoregister')->get('api_user'),
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
                $query->delete('#__demoregister_authentication');
                $db->setQuery($query);
                $db->execute();
                if (self::createApiKey(array(
                    'params' => array(
                        'api_user' => JComponentHelper::getParams('com_demoregister')->get('api_user'),
                        'api_host' => JComponentHelper::getParams('com_demoregister')->get('api_host'),
                        'api_password' => JComponentHelper::getParams('com_demoregister')->get('api_password')
                    )
                ))) {
                    $try++;
                    return self::call($data, $try);
                }
            }
            JFactory::getApplication()->enqueueMessage('Error when try to authenticate, please try again.','warning');
            return $json;
        } else {
            if (empty($json)) {
                if ($try == 1) {
                    $db = JFactory::getDbo();
                    $query = $db->getQuery(true);
                    $query->delete('#__demoregister_authentication');
                    $db->setQuery($query);
                    $db->execute();
                    if (self::createApiKey(array(
                        'params' => array(
                            'api_user' => JComponentHelper::getParams('com_demoregister')->get('api_user'),
                            'api_host' => JComponentHelper::getParams('com_demoregister')->get('api_host'),
                            'api_password' => JComponentHelper::getParams('com_demoregister')->get('api_password')
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
                JFactory::getApplication()->enqueueMessage('Please contact www.cloudaccess.net and copy this message: '.$json['error'],'warning');
                return $json;
            } else {
                return $json['result'];
            }
        }
    }

    public static function createApiKey($data)
    {
        $api_host = trim($data['params']['api_host']);
        $api_user = $data['params']['api_user'];
        $api_pass = $data['params']['api_password'];

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
        

        if ($response->code == 200) {
            $json = json_decode($response->body);

            $post_data = array(
                'code' => $json->code,
                'client_id' => $api_user,
                'client_secret' => $api_pass,
                'scope' => 'global',
                'grant_type' => 'authorization_code'
            );

            $second_step = sprintf('%s/oauth/token',$api_host);

            $oauth = $http->post($second_step, $post_data);

            if ($oauth->code == 200) {
                $json = json_decode($oauth->body, true);
                $values = array_values($json);

                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->insert('#__demoregister_authentication');
                $query->values('0,"'.$values[3].'","'.$values[0].'","'.$values[2].'","'.$values[1].'",NOW()');
                $db->setQuery($query);
                $db->execute();
                return true;

            }
        }

        return false;
    }
}