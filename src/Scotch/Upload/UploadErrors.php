<?php
namespace Scotch\Upload;

class UploadErrors
{
	const UPLOAD_ERR_OK = 0;
	const UPLOAD_ERR_INI_SIZE = 1;
	const UPLOAD_ERR_FORM_SIZE = 2;
	const UPLOAD_ERR_PARTIAL = 3;
	const UPLOAD_ERR_NO_FILE = 4;
	//const = 5;
	const UPLOAD_ERR_NO_TMP_DIR = 6;
	const UPLOAD_ERR_CANT_WRITE = 7;
	const UPLOAD_ERR_EXTENSION = 8;
	const UPLOAD_ERR_FILE_TYPE = 9;
	const UPLOAD_ERR_FILE_EMPTY = 10;
	
	public static $errorMessages = array(
		self::UPLOAD_ERR_OK => "errorUploadNoError",
		self::UPLOAD_ERR_INI_SIZE => "errorUploadFileSize",
		self::UPLOAD_ERR_FORM_SIZE => "errorUploadFileSize",
		self::UPLOAD_ERR_PARTIAL => "errorUploadPartialFile",
		self::UPLOAD_ERR_NO_FILE => "errorUploadNoFile",
		self::UPLOAD_ERR_NO_TMP_DIR => "errorUploadMissingTempDir",
		self::UPLOAD_ERR_CANT_WRITE => "errorUploadFailedWrite",
		self::UPLOAD_ERR_EXTENSION => "errorUploadPhpExtension",
		self::UPLOAD_ERR_FILE_TYPE => "errorUploadInvalidFileExt",
		self::UPLOAD_ERR_FILE_EMPTY => "errorUploadFileEmpty",
	);
	
}
?>