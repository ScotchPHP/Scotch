<?php
namespace Scotch\Encryption;

use Scotch\Encryption\IEncryptionProvider;
use Scotch\Encryption\CryptographicException;

class AesEncryptionProvider implements IEncryptionProvider
{
	public $iv;
	
	/**
	* Warning this is a very insecure key generator do not use for high security operations
	*/
	function generateKey($password)
	{
		return hash("SHA256", $password, true);
	}
	
	/**
	* Generates a random IV for the encryption. 
	*/
	function generateIV()
	{
		// for good entropy (for MCRYPT_RAND)
		srand((double) microtime() * 1000000);
		
		// generate random iv
		$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_RAND);
		
		return base64_encode($iv);
	}
	
	function generateAndSetIV()
	{
		$this->iv = $this->generateIV();
		
		return $this->iv;
	}

	function encrypt($value, $key)
	{
		if(!isset($this->iv))
		{
			throw new CryptographicException("Missing IV for encryption.  Did you forget to set the IV property?");
		}
		
		if(!isset($key))
		{
			throw new CryptographicException("Missing key for encryption.  Did you forget to pass in the key?");
		}
		
		$key = base64_decode($key);
		$parts = explode(",", $key);
		$key = $parts[1];
		$secret = base64_decode($parts[1]);
		$ivDecoded = base64_decode($this->iv);
		$encryptedValue = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $secret, $value, MCRYPT_MODE_CBC, $ivDecoded);
		
		return rtrim(base64_encode($encryptedValue), "\0\3");
	}
	
	function decrypt($value, $key)
	{
		if(!isset($this->iv))
		{
			throw new CryptographicException("Missing IV for decryption.  Did you forget to set the IV property?");
		}
		
		if(!isset($key))
		{
			throw new CryptographicException("Missing key for decryption.  Did you forget to pass in the key?");
		}
		$key = base64_decode($key);
		$value = base64_decode($value);
		$parts = explode(",", $key);
		$key = $parts[1];
		$secret = base64_decode($parts[1]);
		$ivDecoded = base64_decode($this->iv);
		$decryptedValue = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $secret, $value, MCRYPT_MODE_CBC,$ivDecoded); 
	
		return rtrim($decryptedValue, "\0\3");
	}
}
?>

