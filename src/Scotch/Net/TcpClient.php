<?php
namespace Scotch\Net;

class TcpClient 
{
	protected $serverAddress;
	protected $port;
	protected $timeout = 30;
	
	protected $socket;
	
	function __construct($serverAddress, $port, $timeout = 30)
	{	
		$this->open($serverAddress, $port, $timeout);
	}
	
	function open($serverAddress, $port, $timeout = 30)
	{
		$this->serverAddress = $serverAddress;
		$this->port = $port;
		$this->timeout = $timeout;
		$errNo = null;
		$errStr = null;
		$this->socket = fsockopen($serverAddress,$port,$errNo,$errStr,$timeout);
	}
	
	function send($message)
	{
		fwrite($this->socket, $message);
		$response =  stream_get_contents($this->socket,-1);
		return $response;
	}
	
	
	function close()
	{
		fclose($this->socket);
		$this->socket = null;
	}
	
}
?>