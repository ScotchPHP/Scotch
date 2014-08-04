<?php
namespace Scotch\Controllers;

use Scotch\System as System;
use Scotch\Utilities\WebUtilities as WebUtilities;
use Scotch\Utilities\WebConversionTypes as WebConversionTypes;
use Scotch\Views\View as View;
use Scotch\Views\ApiView as ApiView; 
use Scotch\DataTypes as DataTypes;

/**
* Basic controller for php web pages.  Provides an MVC controller for web pages.
* All public methods should output a View object.
*/
abstract class Controller
{
	public $router;
	public $util;
	
	public $template;

	protected $inputs= array();
	protected $errors = array();
		
	/**
	* Controller constructor
	*
	* @param Kohva\Scotch\Routers\Router $router the router for the controller
	*/
	function __construct($router = null)
	{
		$this->util = WebUtilities::getInstance();
		$this->router = $router;
	}
	
	/**
	* Parse and cleanse input data.
	*
	* @param string $key the key in the input arrays to access
	* @param string $type the type that the key's value should be
	* @param array $ref the input array 
	* @param boolean $excludeFromInputs if true the request item is not added to the input array and is added otherwise
	* @return mixed the value stored at the key in the reference array as the specified type
	*/
	protected function request($key, $type = DataTypes::String, $defaultValue = null, &$ref = null, $excludeFromInputs = false)
	{
		$value = $this->util->request($key, $type, $defaultValue, $ref);
		if(!$excludeFromInputs)
		{
			$this->addInput($key, $value);
		}
		return $value;
	}
	
	protected function requestString($key, $defaultValue = null, &$ref = null, $excludeFromInputs = false)
	{
		return $this->request($key, DataTypes::String, $defaultValue, $ref, $excludeFromInputs);
	}
	
	protected function requestInt($key, $defaultValue = null, &$ref = null, $excludeFromInputs = false)
	{
		return $this->request($key, DataTypes::Int, $defaultValue, $ref, $excludeFromInputs);
	}
	
	protected function requestFloat($key, $defaultValue = null, &$ref = null, $excludeFromInputs = false)
	{
		return $this->request($key, DataTypes::Float, $defaultValue, $ref, $excludeFromInputs);
	}
	
	protected function requestBit($key, $defaultValue = null, &$ref = null, $excludeFromInputs = false)
	{
		return $this->request($key, DataTypes::Bit, $defaultValue, $ref, $excludeFromInputs);
	}
	
	protected function requestGuid($key, $defaultValue = null, &$ref = null, $excludeFromInputs = false)
	{
		return $this->request($key, DataTypes::Guid, $defaultValue, $ref, $excludeFromInputs);
	}
	
	protected function requestWysiwyg($key, $defaultValue = null, &$ref = null, $excludeFromInputs = false)
	{
		return $this->request($key, DataTypes::Wysiwyg, $defaultValue, $ref, $excludeFromInputs);
	}
	
	protected function requestDate($key, $defaultValue = null, &$ref = null, $excludeFromInputs = false)
	{
		return $this->request($key, DataTypes::Date, $defaultValue, $ref, $excludeFromInputs);
	}
	
	protected function requestDateTime($key, $defaultValue = null, &$ref = null, $excludeFromInputs = false)
	{
		return $this->request($key, DataTypes::DateTime, $defaultValue, $ref, $excludeFromInputs);
	}
	
	/**
	* Check if data is null or empty.
	*
	* @param string $value the value to be evaluated for null or empty
	* @return boolean true if data is not null or empty / false otherwise
	*/
	protected function hasValue($value)
	{
		return $this->util->hasValue($value);
	}
	
	/**
	* Generate a url for a given controller, action and query string.
	*
	* @param string $controller the controller to handle the request
	* @param string $action the action in the controller to handle the request
	* @param string $queryString the query string to pass to the request
	* @return string url for the request
	*/
	public function urlFor($controller, $action = null, $queryString = null)
	{
		return $this->util->urlFor($controller, $action, $queryString);
	}
	
	public function redirect($url, $code = null)
	{
		return $this->util->redirect($url, $code);
	}
	
	public function hasValueRedirect($value, $redirect)
	{
		return $this->util->hasValueRedirect($value, $redirect);
	}
	
	public function isEmptyRedirect($recordset, $redirect)
	{
		return $this->util->isEmptyRedirect($recordset, $redirect);
	}
	
	public function getString($key)
	{
		return $this->util->getString($key);
	}
	
	/**
	* Get an instance of a service from the service locator
	*
	* @param string $serviceName the name of the service to retrieve
	* 
	* @returns the instance of the service requested
	* @throws MissingServiceException
	*/
	public function getService($serviceName,$parameters = null)
	{
		return System::$application->getService($serviceName,$parameters);
	}
	
	/**
	* Add a key and value to the keys input array
	*
	* @param string $key the key to store the value as in the input associative array
	* @param mixed $value the value to store into the specified key
	*/
	private function addInput($key, $value)
	{
		$this->inputs[$key] = $value;
	}
	
	/**
	* Creates a Kohva\Scotch\Views\View object
	*
	* @returns Kohva\Scotch\Views\View
	*/
	protected function createView($properties = array())
	{
		$properties["controller"] = $this;
		
		return new View($properties);
	}
	
	/**
	* Creates a Kohva\Scotch\Views\ApiView object
	*
	* @returns Kohva\Scotch\Views\ApiView
	*/
	function createApiView($data)
	{
		return new ApiView($data);
	}
	
}
?>