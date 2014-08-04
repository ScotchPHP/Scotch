<?php
namespace Scotch\Net;

use Scotch\Net\CurlResponse as CurlResponse;
use Scotch\Net\CurlException as CurlException;

class CurlRequest
{
	const USER_AGENT_NAME = "ScotchPHP Curl/1.0";
	const HTTPS_PATTERN = "^https://";
	
	private $handle;
	
	public $url;
	public $host;
	public $header;
	public $method;
	public $referer;
	public $cookie;
	public $postFields;
	public $login;
	public $password;
	public $timeout = 0;
	
	protected $headers;

	function __construct($parameters = array())
	{
		$this->setData($parameters);
	}
	
	function addHeader($header,$value)
	{
		if(!isset($this->headers))
		{
			$this->headers = array();
		}
		$this->headers[$header] = $value;
	}
	
	function execute($parameters = array())
	{
		$this->setData($parameters);
		
		if(!isset($this->handle))
		{
			$this->handle = curl_init();
		}
		curl_setopt($this->handle, CURLOPT_VERBOSE, true);
		curl_setopt($this->handle, CURLOPT_CAINFO, dirname(__FILE__)."/Resources/cacert.pem");
		curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->handle, CURLOPT_USERAGENT, self::USER_AGENT_NAME);
		curl_setopt($this->handle, CURLINFO_HEADER_OUT, true);
		
		if(isset($this->headers))
		{
			$headers = array();
			foreach($this->headers as $key => $value)
			{
				array_push($headers,"$key: $value");
			}
			
			if(count($headers) > 0)
			{
				curl_setopt($this->handle, CURLOPT_HTTPHEADER, $headers);
			}
		}
		
		if(isset($this->url))
		{
			if(preg_match("/^https:/i",$this->url))
			{
				curl_setopt($this->handle, CURLOPT_SSL_VERIFYPEER, true);
				curl_setopt($this->handle, CURLOPT_SSL_VERIFYHOST, 2);
			}
			curl_setopt($this->handle, CURLOPT_URL, $this->url);
		}
		else
		{
			throw new CurlException("URL is required to make a CURL Request");
		}
		
		if(isset($this->referer))
		{
			curl_setopt($this->handle, CURLOPT_REFERER, $this->referer);
		}
		
		if(isset($this->cookie))
		{
			curl_setopt($this->handle, CURLOPT_COOKIE, $this->cookie);
		}
		
		if(isset($this->method))
		{
			if(strtoupper($this->method) == "POST")
			{
				curl_setopt($this->handle, CURLOPT_POST, true);
				curl_setopt($this->handle, CURLOPT_POSTFIELDS, $this->postFields);
			}
		}
		
		if(isset($this->login) && isset($this->password))
		{
			curl_setopt($this->handle, CURLOPT_USERPWD, $this->login . ":" . $this->password);
		}
		
		if($this->timeout > 0)
		{
			curl_setopt($this->handle, CURLOPT_TIMEOUT, $this->timeout);
		}
		
		$response = curl_exec($this->handle);
		$error = curl_error($this->handle);
		
		if($error != "")
		{
			throw new CurlException($error);
		}
		
		$headerSize = curl_getinfo($this->handle, CURLINFO_HEADER_SIZE);
		$contentType = curl_getinfo($this->handle, CURLINFO_CONTENT_TYPE);	
		$responseText = $response;
		$code = curl_getinfo($this->handle, CURLINFO_HTTP_CODE);
		$lastUrl = curl_getinfo($this->handle, CURLINFO_EFFECTIVE_URL);
		
		$this->dispose();
		
		$result = new CurlResponse(array(
			"headerSize" => $headerSize,
			"contentType" => $contentType,
			"responseText" => $responseText,
			"code" => $code,
			"lastUrl" => $lastUrl
		));
		
		return $result;
	}
	
	private function setData($parameters = array())
	{
		if(count($parameters)>0)
		{
			foreach($parameters as $key => $value)
			{
				if(property_exists($this, $key))
				{					
					$this->$key = $value;
				}
			}
		}
	}
	
	protected function dispose()
	{
		if(isset($this->handle))
		{
			curl_close($this->handle); 
			$this->handle = null;
		}
	}
	
	function __destruct()
	{
		$this->dispose();
	}
}
?>