<?php
namespace Scotch;

use SoapClient;
use Scotch\Exceptions\InvalidArgumentException as InvalidArgumentException;

abstract class WcfServiceApplication
{
	private $soapClient;
	
	function __construct($endPointUrl,$timeout = 30)
	{
		$this->soapClient = new SoapClient($endPointUrl);
	}
	
	protected function send($method,$parameters)
	{	
		return $this->soapClient->$method($parameters);		
	}
	
	function __destruct()
	{
		
	}
}

?>