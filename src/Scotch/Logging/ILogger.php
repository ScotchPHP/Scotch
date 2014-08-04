<?php
namespace Scotch;

interface ILogger
{
	public function logDebug($msg);
	
	public function logInfo($msg);
	
	public function logWarn($msg);
	
	public function logError($msg);
	
	public function logFatal($msg);
}
?>