<?php
namespace Scotch\Services;

use Scotch\System as System;
use Scotch\Utilities\Utilities as Utilities;

class Service 
{
	protected $util;
	
	function __construct()
	{
		$this->util = Utilities::getInstance();
	}
	
	function getService($serviceName,$parameters = null)
	{
		return System::$application->getService($serviceName,$parameters);
	}
}
?>