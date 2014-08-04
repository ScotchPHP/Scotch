<?php
namespace Scotch;

use Scotch\Net\TcpClient as TcpClient;
use Scotch\Exceptions\InvalidArgumentException as InvalidArgumentException;

abstract class ServiceApplication
{
	private $tcpClient;
	private $serviceName;
	
	function __construct($serviceName,$serviceHost,$servicePort,$serviceTimeout = 30)
	{
		$this->serviceName = $serviceName;
		$this->tcpClient = new TcpClient($serviceHost,$servicePort,$serviceTimeout);
	}
	
	protected function send($data,$operation = null)
	{
		if(!($data instanceof ServiceApplicationRequest))
		{
			throw new InvalidArgumentException("Invalid Argument 'data' must be of type Kohva\Scotch\ServiceApplicationRequest");
		}
		else
		{
			$data->serviceName = $this->serviceName;
			$data->operation = $operation;
		}
		
		return $this->tcpClient->send($data->serialize());
	}
	
	function __destruct()
	{
		$this->tcpClient->close();
	}
}

?>