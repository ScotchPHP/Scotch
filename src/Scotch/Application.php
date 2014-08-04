<?php
namespace Scotch;

use Scotch\System as System;
use Scotch\Configuration as Configuration;
use Scotch\ServiceLocator as ServiceLocator;
use Scotch\IApplication as IApplication;
use Scotch\Routing\Router as Router;

abstract class Application implements IApplication
{
	public $test = "test";
	public $configuration;
	public $cache;
	public $router;
	
	private $serviceLocator;
	
	function __construct($configuration)
	{
		// Set Configuration and Application
		$this->configuration = new Configuration($configuration);
		$this->serviceLocator = new ServiceLocator();
		System::$application = $this;
		
		// Check if a cache provider exists
		if(!empty($this->configuration->cacheProvider))
		{
			$this->cache = new $this->configuration->cacheProvider();
		}
		
	}
	
	abstract function getRoutingTable();
	
	abstract function getServiceTable();
	
	function main()
	{
		$this->router = new Router();
		
		try
		{
			$this->router->route();
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	
	function getService($serviceName,$parameters = null)
	{
		return $this->serviceLocator->getService($serviceName,$parameters);
	}
}
?>