<?php
namespace Scotch\Routing;

use Scotch\System as System;
use Scotch\Routing\RequestRouter as RequestRouter;
use Scotch\Controllers\ApiController as ApiController;
use Scotch\Views\View as View;
use Scotch\Views\ApiView as ApiView;
use Scotch\Exceptions\MethodNotFoundException as MethodNotFoundException;
use Scotch\Exceptions\ClassNotFoundException as ClassNotFoundException; 
use Scotch\Exceptions\InvalidViewException as InvalidViewException;
use Scotch\Serialize\XmlSerializer as XmlSerializer;
use Scotch\Utilities\Utilities as Utilities;

class Router extends RequestRouter
{
	const PATH_PARAMETER = "c";
	const CONTROLLER_PARAMETER = "c";
	const METHOD_PARAMETER = "m";
	const RETURN_TYPE_PARAMETER = "r";
	
	const RETURN_TYPE_JSON = "json";
	const RETURN_TYPE_XML = "xml";
	
	const DEFAULT_ACTION = "index";
	const DEFAULT_API_ACTION = "get";
	
	const REDIRECT_REGEX = "/index.php/";
	const API_REGEX = "/^\/api\//";
	const END_REGEX = "/.*\/$/";
	
	public $routingTable;
	
	public $controllerClass;
	public $controllerMethod;
	
	public function __construct()
	{
		parent::__construct();
		$this->routingTable = System::$application->getRoutingTable();
	}
	
	public function route($path = null)
	{	
		try
		{
			$c = (($path == null) ? Utilities::getInstance()->getValue($_REQUEST, self::PATH_PARAMETER) : $path);
			
			if(substr($c,-1) == "/")
			{
				$c = substr_replace($c,"",-1);
			}
			
			if(preg_match(self::API_REGEX, $this->url))
			{
				$controllerMethod = Utilities::getInstance()->getValue($_SERVER, "REQUEST_METHOD");
				if( !isset($controllerMethod) || strlen($controllerMethod) == 0 )
				{
					$controllerMethod = self::DEFAULT_API_ACTION;
				}
				else
				{
					switch($controllerMethod)
					{
						case "PUT" : 
							global $_PUT;
							$_PUT = $this->createInputGlobals();
							break;
						case "DELETE":
							global $_DELETE;
							$_DELETE = $this->createInputGlobals();
							break;
					}
				}
				$this->controllerMethod = $controllerMethod;
				
				$output = $this->executeApiController($c);
				
				if ($output instanceof ApiView)
				{
					$output->render(Utilities::getInstance()->getValue($_REQUEST, self::RETURN_TYPE_PARAMETER));
				}
				else
				{
					throw new InvalidViewException("Invalid Api View.  An api view must be of type Kohva\Scotch\Views\ApiView.");
				}
			}
			//not api controller
			else
			{
				if(strlen($this->controllerMethod) == 0)
				{
					$this->controllerMethod = self::DEFAULT_ACTION;
				}
				
				$output = $this->executeController($c);
				
				if($output instanceof View)
				{
					$output->render();
				}
				else
				{
					throw new InvalidViewException("Invalid View.  A view must be of type Kohva\Scotch\Views\View.");
				}
			}
		} 
		catch(Exception $e)
		{
			header('HTTP/1.1 404 Not Found');
		}
		
	}
	
	protected function executeController($controllerKey)
	{
		$output = null;
		$this->controllerClass = Utilities::getInstance()->getValue($this->routingTable, $controllerKey);
		
		if(!isset($this->controllerClass))
		{
			$offset = ( strrpos($controllerKey,"/") ) ? 1 : 0;
			$this->controllerMethod = substr($controllerKey, strrpos($controllerKey,"/")+$offset);
			
			$controllerKey = substr($controllerKey, 0, strrpos($controllerKey,"/"));
			
			$this->controllerClass = Utilities::getInstance()->getValue($this->routingTable, $controllerKey);
		}

		if(!isset($this->controllerClass))
		{
			throw new ClassNotFoundException("Controller Route not found.");
		}
		else
		{
			if(class_exists($this->controllerClass))
			{
				$this->controller = new $this->controllerClass($this);
				
				$m = $this->controllerMethod;
				
				if(method_exists($this->controller, $m) == true)
				{
					$output = $this->controller->$m();
				}
				else
				{
					throw new MethodNotFoundException("Class '" . $this->controllerClass . "' does not contain method '$m.'");
				}
			}
			else
			{
				throw new ClassNotFoundException("Class '" . $this->controllerClass . "' could not be found.");
			}
		}
		
		return $output;
	}
	
	protected function executeApiController($controllerKey)
	{
		$output = null;
		$this->controllerClass = Utilities::getInstance()->getValue($this->routingTable, $controllerKey);
		
		if(!isset($this->controllerClass))
		{
			throw new ClassNotFoundException("API Controller Route not found.");
		}
		else
		{
			if(class_exists($this->controllerClass))
			{
				$this->controller = new $this->controllerClass($this);
				
				$m = $this->controllerMethod;
				
				if(method_exists($this->controller, $m) == true)
				{
					$output = $this->controller->$m();
				}
				else
				{
					throw new MethodNotFoundException("Class '" . $this->controllerClass . "' does not contain method '$m.'");
				}
			}
			else
			{
				throw new ClassNotFoundException("Class '" . $this->controllerClass . "' could not be found.");
			}
		}
		
		return $output;
	}
	
	private function createInputGlobals()
	{	
		$inputs = null;
		
		parse_str(file_get_contents("php://input"),$inputs);
		
		return $inputs;
	}
	
}
?>