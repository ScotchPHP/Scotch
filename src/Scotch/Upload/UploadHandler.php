<?php
namespace Scotch\Upload;

use Scotch\IO\FileTypes as FileTypes;
use Scotch\Upload\UploadFile as UploadFile;
use Scotch\Upload\UploadResult as UploadResult;
use Scotch\Upload\UploadErrors as UploadErrors;
use Scotch\Utilities\WebUtilities as WebUtilities;

class UploadHandler
{
	private static $acceptedFileTypes = array(
		FileTypes::TXT,
		FileTypes::PDF,
		FileTypes::XLS,
		FileTypes::XLSX,
		FileTypes::DOC,
		FileTypes::DOCX,
	);
	
	private static $acceptedImageTypes = array(
		FileTypes::JPG,
		FileTypes::JPEG,
		FileTypes::PNG,
		FileTypes::GIF,
		FileTypes::BMP,
		FileTypes::TIFF,
	);
	
	private $util;
	private $uploadResult;
	
	function __construct($properties = null)
	{
		$this->util = WebUtilities::getInstance();
	}
	
	function __destruct() {
		if(isset($this->uploadResult) && $this->uploadResult instanceof UploadResult)
		{
			foreach($this->uploadResult->files as $file)
			{
				if (is_file($file->tempName))
				{
					unlink($file->tempName);
				}
			}
		}
	}
	
	public function getFiles($inputName, $acceptedTypes = null, $parameters = array())
	{
		$this->uploadResult = new UploadResult();
		
		if($this->util->hasValue($inputName))
		{
			$acceptedTypes = ( $this->util->hasValue($acceptedTypes) ) ? $acceptedTypes : array_merge(self::$acceptedFileTypes,self::$acceptedImageTypes);
			$files = $_FILES[$inputName];
			$hasError = false;
			
			if(is_array($files["name"]))
			{
				
				for ($i = 0; $i < count($files["name"]); $i++)
				{
					$name = $files["name"][$i];
					$type = $files["type"][$i];
					$extension = $this->util->getFileExtension($files["name"][$i]);
					$size = $files["size"][$i];
					$tempName = $files["tmp_name"][$i];
					$error = $files["error"][$i];
					
					if( $files["size"][$i] <= 0 )
					{
						$error = UploadErrors::UPLOAD_ERR_FILE_EMPTY;
					}
					elseif ( !in_array($type, $acceptedTypes) )
					{
						$error = UploadErrors::UPLOAD_ERR_FILE_TYPE;
					}
					
					if( $error != 0 )
					{
						$hasError = true;
					}
					
					$uploadFile = new UploadFile(array(
						"name" => $name,
						"type" => $type,
						"extension" => $extension,
						"size" => $size,
						"tempName" => $tempName,
						"error" => $error,
					));
					array_push($this->uploadResult->files,$uploadFile);
				}
			}
			else
			{
				$name = $files["name"];
				$type = $files["type"];
				$extension = $this->util->getFileExtension($files["name"]);
				$size = $files["size"];
				$tempName = $files["tmp_name"];
				$error = $files["error"];
				
					if( $files["size"] <= 0 )
					{
						$error = UploadErrors::UPLOAD_ERR_FILE_EMPTY;
					}
					elseif ( !in_array($type, $acceptedTypes) )
					{
						$error = UploadErrors::UPLOAD_ERR_FILE_TYPE;
					}
					
					if( $error != 0 )
					{
						$hasError = true;
					}
					
					$uploadFile = new UploadFile(array(
						"name" => $name,
						"type" => $type,
						"extension" => $extension,
						"size" => $size,
						"tempName" => $tempName,
						"error" => $error,
					));
					
					array_push($this->uploadResult->files,$uploadFile);
			}
			
			$this->uploadResult->hasError = $hasError;
		}
		
		return $this->uploadResult;
	}
	
	public function getImages($inputName, $acceptedTypes = null, $parameters = array())
	{
		$acceptedTypes = ( $this->util->hasValue($acceptedTypes) ) ? $acceptedTypes : self::$acceptedImageTypes;
		return $this->getFiles($inputName, $acceptedTypes, $parameters);
	}
	
}
?>