<?php

/**
 * @name Demo Trial API PHP SDK
 * @version 1.0
 * @author Cloudaccess.net
 *
 */
  
class DemoApiException extends Exception
{
}

class DemoApi
{
	protected $_host = null;
	protected $_client_id = null;
	protected $_client_secret = null;

	protected $_verify_ssl = true;
	
	protected $_code = null;
	protected $_access_token = null;
	protected $_refresh_token = null;

	public function __construct($config)
	{
		$this->_host = 'https://api.cloudaccess.net';
		if ( ! isset($config['client_id']))
		{
			throw new DemoApiException('Client ID not specified in the config', 1);
		}
		if ( ! isset($config['client_secret']))
		{
			throw new DemoApiException('Client Secret not specified in the config', 1);
		}

		$this->_client_id = $config['client_id'];
		$this->_client_secret = $config['client_secret'];

		if (isset($config['access_token']))
		{
			$this->_access_token = $config['access_token'];
		}
		if (isset($config['refresh_token']))
		{
			$this->_refresh_token = $config['refresh_token'];
		}
		if (isset($config['verify_ssl']))
		{
			$this->_verify_ssl = $config['verify_ssl'];
		}
	}
	
	public function get_code($redirect_uri='')
	{
		$url = $this->_build_url('/oauth/authorization',array(
			'client_id' => $this->_client_id,
			'response_type' => 'code',
			'redirect_uri' => $redirect_uri
		));
		
		$r = $this->_request($url);
		$purl = parse_url($r['headers']['location']);
		$pquery = array();
		parse_str($purl['query'], $pquery);
		$this->_code = $pquery['code'];
		return $this->_code;
	}
	
	public function get_token($redirect_uri='')
	{
		$url = $this->_build_url('/oauth/token');
		
		$r = $this->_request($url, array(
			'client_id' => $this->_client_id,
			'client_secret' => $this->_client_secret,
			'code'	=> $this->_code,
			'scope' => 'global',
			'grant_type' => 'authorization_code',
			'redirect_uri' => $redirect_uri
		));
		
		$this->_access_token = $r['body']['access_token'];
		$this->_refresh_token = $r['body']['refresh_token'];
		
		return $r['body'];
	}

	public function login($redirect_uri='')
	{
		$this->get_code();
		return $this->get_token($redirect_uri);
	}
	
	public function refresh_token($redirect_uri='')
	{
		$url = $this->_build_url('/oauth/token');
		$r = $this->_request($url, array(
			'client_id' => $this->_client_id,
			'client_secret' => $this->_client_secret,
			'refresh_token'	=> $this->_refresh_token,
			'scope' => 'global',
			'grant_type' => 'refresh_token',
			'redirect_uri' => $redirect_uri
		));
		
		$this->_access_token = $r['body']['access_token'];
		$this->_refresh_token = $r['body']['refresh_token'];
		
		return $r['body'];
	}
	
	public function api($method, $args=array())
	{
		$url = $this->_build_url('/api');
		
		$r = $this->_request($url, array_merge($args, array(
			'client_id' => $this->_client_id,
			'access_token' => $this->_access_token,
			'method' => $method,
			'token_type' => 'Bearer',
			'scope' => 'global',
		)));
		return $r['body'];
	}
	
	protected function _build_url($endpoint, $args=null)
	{
		return $args
				? $this->_host.$endpoint.'?'.http_build_query($args)
				: $this->_host.$endpoint;
	}
	
	protected function _curl_header($response)
	{
		$headers = array();

		$header_text = substr($response, 0, strpos($response, "\r\n\r\n"));

		foreach (explode("\r\n", $header_text) as $i => $line)
			if ($i === 0)
				$headers['http_code'] = $line;
			else
			{
				list ($key, $value) = explode(': ', $line);

				$headers[strtolower($key)] = $value;
			}

		return $headers;
	}
	
	protected function _request($url, $args=null)
	{
		$r = $this->_curl($url, $args);
		$r['body'] = json_decode($r['body'], true);
		if ( ! in_array($r['info']['http_code'], array(200, 302, 301)))
		{
			throw new DemoApiException($r['body']['error'], $r['info']['http_code']);
		}
		return $r;
	}

	protected function _curl($url, $args=null)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		if ($args)
		{
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($args));
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if ($this->_verify_ssl)
		{
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($ch, CURLOPT_SSLVERSION, 2);
			curl_setopt($ch, CURLOPT_CAINFO, __DIR__.'/cloudaccess.net.pem');
		}
		else
		{
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		}

		$out = curl_exec($ch);
		$info = curl_getinfo($ch);
		if ($info['http_code'] == 0)
		{
			echo curl_error($ch);
			curl_close($ch);
			die();
		}
		$header = substr($out, 0, $info['header_size']);
		$body = substr($out, $info['header_size']);	

		curl_close($ch);
		return array('info' => $info, 'headers' => $this->_curl_header($header), 'body' => $body);
	}
}
