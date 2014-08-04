<?php
namespace Scotch\Upload;

use Scotch\IO\File as File;

class UploadFile extends File
{
	public $error;
	public $tempName;
	
	public function tempFileName()
	{
		return basename($this->tempName);
	}
}
?>