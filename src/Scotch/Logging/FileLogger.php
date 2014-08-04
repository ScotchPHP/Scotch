<?php
namespace Scotch\Logging;

use Scotch\Logging\Logger as Logger;

class FileLogger extends Logger
{
	protected $filePath;
	protected $fileHandle;
	protected $logFailed;
	
	public function __construct($filePath, $state)
	{
		$this->fileName = $fileName;
		$this->state = $state;
		$this->logFailed = false;
	}
	
	protected function isFileOpen()
	{
		$isFileOpen = false;
		if(!$this->logFailed)
		{
			if(!isset($this->fileHandle))
			{
				try
				{
					$this->fileHandle = fopen($this->filePath, 'w');
					$isFileOpen = true;
				}
				catch(Exception $e)
				{
					unset($this->fileHandle);
					$this->logFailed = true;
				}
			}
			else
			{
				$isFileOpen = true;
			}
		}
		
		return $isFileOpen;
	}

	protected function log($msg)
	{
		if($this->isFileOpen())
		{
			fwrite($this->fileHandle, $msg);
		}
	}
	
	function __destruct(
	{
		if(isset($this->fileHandle))
		{
			fclose($this->fileHandle);
		}
	}
	
}
?>