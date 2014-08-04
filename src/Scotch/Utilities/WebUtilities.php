<?php
namespace Scotch\Utilities;

use Scotch\Utilities\Utilities as Utilities;
//use Scotch\Utilities\WebConversionTypes as WebConversionTypes;
use Scotch\Localization\LocalizationService as LocalizationService;
use Scotch\Exceptions\MissingLocalizationException as MissingLocalizationException;
use Scotch\DataTypes as DataTypes;
use Scotch\System as System;

class WebUtilities extends Utilities
{
	private static $singletonInstance;
	private $urlArray;
	
	/*
	// Types of web types
	private $webTypeMap = 
		array(
			"string" => WebConversionTypes::WebString,
			"int" => WebConversionTypes::WebInt,
			"float" => WebConversionTypes::WebFloat,
			"bit" => WebConversionTypes::WebBit,
			"guid" => WebConversionTypes::WebGuid,
			"wysiwyg" => WebConversionTypes::WebWysiwyg,
			"date" => WebConversionTypes::WebDate,
			"dateTime" => WebConversionTypes::WebDateTime,
		);
	*/
	
	private function __construct()
	{
	}
	
	public static function getInstance()
	{
		if(!self::$singletonInstance)
		{
			self::$singletonInstance = new WebUtilities();
		}
		
		return self::$singletonInstance;
	}
	
	public function get($value, $type = 0)
	{
		$outValue = null;
		
		if(isset($_GET[$value]))
		{
			$outValue = $_GET[$value];
			$outValue = $this->convertToType($outValue, $type);
		}
		
		return $outValue;
	}
	
	public function post($value, $type = 0)
	{
		$outValue = null;
		if(isset($_POST[$value]))
		{
			$outValue = $_POST[$value];
			$outValue = $this->convertToType($outValue, $type);
		}
		
		return $outValue;
	}
	
	public function request($value, $type = DataTypes::String, $defaultValue = null, &$ref = null)
	{
		if(!isset($ref))
		{
			$ref = $_REQUEST;
		}
		$outValue = $defaultValue;
		if( $this->hasValue($this->getValue($ref, $value)) )
		{
			$outValue = $ref[$value];
			$outValue = $this->convertToType($outValue, $type);
		}
		return $outValue;
	}
	
	public function convertToType($value, $type = DataTypes::String)
	{
		$outValue = null;
		switch($type)
		{
			case DataTypes::String:
				$outValue = $this->setNull($value);
				break;
			case DataTypes::Int:
				$outValue = $this->setInt($value);
				break;
			case DataTypes::Float:
				$outValue = $this->setFloat($value);
				break;
			case DataTypes::Bit:
				$outValue = $this->setBit($value);
				break;
			case DataTypes::Guid:
				$outValue = $this->setGuid($value);
				break;
			case DataTypes::Wysiwyg:
				$outValue = $this->setNull($value);
				break;
			case DataTypes::Date:
				$outValue = $this->setDate($value);
				break;
			case  DataTypes::DateTime:
				$outValue = $this->setDateTime($value);
				break;
		}
		
		return $outValue;
	}
	
	public function permanentRedirect($url)
	{
		header("HTTP/1.1 301 Moved Permanently"); 
		$this->redirect($url);
	}
	
	public function temporaryRedirect($url)
	{
		$this->redirect($url);
	}
	
	public function redirect($url, $code = null)
	{
		header("Location: " . $url ); 
		die();
	}
	
	private function explodeUrl()
	{
		return explode("?",$_SERVER["REQUEST_URI"]);
	}
	
	public function getUrl()
	{
		$urlParts = $this->explodeUrl();
		return $this->getValue($urlParts, 0);
	}
	
	public function getQueryString()
	{
		$urlParts = $this->explodeUrl();
		$queryString = "?" . $this->getValue($urlParts, 1);
		return ($queryString == "?") ? null : $queryString;
	}
	
	public function buildQueryString($parameters = array(), $excludeParameters = array())
	{
		$includeQueryString = $this->getValue($parameters, "includeQueryString", true);
		$includeFormVariables = $this->getValue($parameters, "includeFormVariables", false);
		
		$queryString = "";
		$formString = "";
		if($includeQueryString)
		{
			$queryString .= $this->getQueryString();
		}
		if($includeFormVariables)
		{
			foreach($_POST as $parameter => $value) {
				if( !$this->isParameterInQueryString($parameter, $queryString) )
				{
					if(is_array($value))
					{
						foreach($value as $i=>$arrayValue)
						{
							$formString = $this->appendQueryStringParameter($formString, $parameter."[".$i."]", $arrayValue);
						}
					}
					else
					{
						$formString = $this->appendQueryStringParameter($formString, $parameter, $value);
					}
				}
			}
			if($this->hasValue($formString))
			{
				$queryString .= (($this->hasValue($queryString)) ? "&" : "?") . ltrim($formString, "?");
			}
		}
		if(isset($excludeParameters))
		{
			foreach($excludeParameters as $key=>$parameter)
			{
				$queryString = $this->removeQueryStringParameter($parameter, $queryString);
			}
			if($queryString == "?")
			{
				$queryString = null;
			}
		}
		
		return $this->setNull($queryString);
	}
	
	public function appendQueryStringParameter($url, $parameter, $value)
	{
		if($this->hasValue($parameter) && $this->hasValue($value))
		{
			$url .= (strpos($url, "?") !== false) ? "&" : "?";
			$url .= urlencode($parameter) . "=" . urlencode($value);
		}
		return $url;
	}
	
	public function removeQueryStringParameter($parameter, $queryString)
	{
		$queryString = ltrim($queryString, "?");
		
		if ( $this->hasValue($queryString) and $this->hasValue($parameter) )
		{
			$queryString = "&".$queryString;
			$position = strpos($queryString, "&" . $parameter . "=");
			
			if( $position !== false )
			{
				// Filter out query string
				$endPosition = strpos($queryString, "&", ($position+1));
				
				if( $endPosition !== false )
				{
					$queryString = substr_replace($queryString, "", $position, ($endPosition - $position));
				}
				else
				{
					$queryString = substr_replace($queryString, "", $position);
				}
				//$queryString = ltrim($queryString,'&');
			}
			if ( $this->hasValue($queryString) )
			{
				$queryString = "?" . ltrim($queryString, "&");
			}
		}
		
		return $queryString;
	}
	
	public function isParameterInQueryString($parameter, $queryString)
	{
		$position = strpos("&".$queryString, "&" . $parameter . "=");
		return ( $position !== false ) ? true : false;
	}
	
	public function urlForApplication($application, $controller, $action = null, $queryString = null)
	{
		$path = "/";
		if( $this->hasValue($application) )
		{
			$path .= $application."/";
		}
		$path .= $controller."/";
		if( $this->hasValue($action) )
		{
			$path .= $action."/";
		}
		
		if(is_array($queryString))
		{
			$qsString = "?";
			foreach($queryString as $key => $value)
			{
				$qsString .= $key . "=" . urlencode($value) . "&";
			}
			$qsString = rtrim($qsString,"&");
			$path .= $qsString;
			
		}
		else
		{
			if( $this->hasValue($queryString) )
			{
				$path .= "?".ltrim($queryString,"?");
			}
		}
		
		return $path;
	}
	
	public function urlFor($controller, $action = null, $queryString = null)
	{
		return $this->urlForApplication(null, $controller, $action, $queryString);
	}
	
	public function hasValueRedirect($value, $redirect)
	{
		if ( is_array($value) )
		{
			foreach($value as $item)
			{
				if ( !$this->hasValue($item) )
				{
					$this->redirect($redirect);
				}
			}
		}
		elseif ( !$this->hasValue($value) )
		{
			$this->redirect($redirect);
		}
	}
	
	public function isEmptyRedirect($value, $redirect)
	{
		if ( $this->isEmpty($value) && $this->hasValue($redirect) )
		{
			$this->redirect($redirect);
		}
	}
	
	public function getString($key)
	{
		$string = null;
		if ( isset($key) )
		{
			$string = LocalizationService::getInstance()->getString($key);
			if ( !$this->hasValue($string) )
			{
				throw new MissingLocalizationException("Missing Localization: ".$key);
			}
		}
		return $string;
	}
	
	public function formatString($string, $parameters = array())
	{
		foreach($parameters as $key => $value)
		{
			$string = str_replace("{".$key."}",$value,$string);
		}
		return $string;
	}
	
	public function getFileExtension($path)
	{
		$extension = null;
		
		if(isset($path))
		{
			$pathinfo = pathinfo($path);
			$extension = $pathinfo['extension'];
		}
		
		return $extension;
	}
	
	public function getConfigurationItem($item = null)
	{
		return System::$application->configuration->$item;
	}
	
	public function getApplicationSetting($setting = null)
	{
		$settings = $this->getConfigurationItem("settings");
		return $settings[$setting];
	}
	
}
?>