<?php
namespace Scotch\Logging;

use Scotch\Logging\ILogger as ILogger;

abstract class Logger implements ILogger
{
	const DEBUG = 1; // Most Verbode
	const INFO = 2;
	const WARN = 3;
	const ERROR = 4;
	const FATAL = 5; // Least Verbose
	
	protected $state;

	public function logDebug($msg)
	{
		$this->log($msg);
	}
	
	public function logInfo($msg)
	{
		if($this->state >= self::INFO)
		{
			$this->log($msg);
		}
	}
	
	public function logWarn($msg)
	{
		if($this->state >= self::WARN)
		{
			$this->log($msg);
		}
	}
	
	public function logError($msg)
	{
		if($this->state >= self::ERROR)
		{
			$this->log($msg);
		}
	}
	
	public function logFatal($msg)
	{
		if($this->state >= self::FATAL)
		{
			$this->log($msg);
		}
	}
	
	abstract protected function log($msg);

}
?>