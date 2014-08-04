<?php
namespace Scotch\Encryption;

/**
* Provide PBKDF2 salting and hashing algorithm.  
*
* <info>
*   This implementation of PBKDF2 was originally created by https://defuse.ca
*   With improvements by http://www.variations-of-shadow.com 
*	And turned into an Object Oriented implementation by Jim Dubreville
* </info>
*/
class Pbkdf2
{	
	// These constants may be changed without breaking existing hashes.
	const HASH_ALGORITHM = "sha256";
	const ITERATIONS = 1000;
	const SALT_BYTES = 24;
	const HASH_BYTES = 24;

	const HASH_SECTIONS = 4;
	const HASH_ALGORITHM_INDEX = 0;
	const HASH_ITERATION_INDEX = 1;
	const HASH_SALT_INDEX = 2;
	const HASH_INDEX = 3;

	/**
	* Create a Salted and Hashed version of the given password.  This function
	* produces a random salt on its own.
	*
	* @param string $password string to salt and hash
	* @return string The return string is formatted as algorithm:iterations:salt:hash.  Use explode to get the parts of the string.
	* 
	* <code>
	*	$hash = Pbkdf2::createHash($password);
	*	$hashParts = explode(":",$hash);	
	*	$salt = $hashParts[Pbkdf2::HASH_SALT_INDEX];
	*   $hashedPassword = $hashParts[Pbkdf2::HASH_INDEX];
	* </code>
	*/
	public static function createHash($password)
	{
		// format: algorithm:iterations:salt:hash
		$salt = base64_encode(mcrypt_create_iv(self::SALT_BYTES, MCRYPT_DEV_URANDOM));
		return self::HASH_ALGORITHM . ":" . self::ITERATIONS . ":" .  $salt . ":" . 
			base64_encode(self::pbkdf2(
				self::HASH_ALGORITHM,
				$password,
				$salt,
				self::ITERATIONS,
				self::HASH_BYTES,
				true
			));
	}
	
	/**
	* Hashes a specified password with the specified salt
	*
	* @param string $salt string to use as a salt for the hash
	* @param string $password string to salt and hash
	* @return string The return string is formatted as algorithm:iterations:salt:hash.  Use explode to get the parts of the string.
	* 
	* <code>
	*	$hash = Pbkdf2::doHash($salt, $password);
	*	$hashParts = explode(":",$hash);	
	*	$salt = $hashParts[Pbkdf2::HASH_SALT_INDEX];
	*   $hashedPassword = $hashParts[Pbkdf2::HASH_INDEX];
	* </code>
	*/
	public static function doHash($salt, $password)
	{
		// format: algorithm:iterations:salt:hash
		return self::HASH_ALGORITHM . ":" . self::ITERATIONS . ":" .  $salt . ":" . 
			base64_encode(self::pbkdf2(
				self::HASH_ALGORITHM,
				$password,
				$salt,
				self::ITERATIONS,
				self::HASH_BYTES,
				true
			));
	}
	
	/**
	* Validate a password against the specified salted and hashed password.
	*
	* @param string $password string to salt and hash
	* @param string $goodHash previously salted and hashed string in the format algorithm:iterations:salt:hash
	* @return boolean true if the two passwords match and false otherwise
	* 
	* <code>
	*	$goodHash = Pbkdf2::createHash($password);
	*	$isValid = Pbkdf2::validatePassword($password, $goodHash);
	* </code>
	*/
	public static function validatePassword($password, $goodHash)
	{
		$params = explode(":", $goodHash);
		if(count($params) < self::HASH_SECTIONS)
		   return false; 
		$pbkdf2 = base64_decode($params[self::HASH_INDEX]);
		return slowEquals(
			$pbkdf2,
			self::pbkdf2(
				$params[self::HASH_ALGORITHM_INDEX],
				$password,
				$params[self::HASH_SALT_INDEX],
				(int)$params[self::HASH_ITERATION_INDEX],
				strlen($pbkdf2),
				true
			)
		);
	}

	/**
	* Compares two strings in length-constant time
	*
	* @param string $a first string to compare
	* @param string $b second string to compare
	* @return boolean true if the strings are equal and false otherwise
	* 
	* <code>
	*	$equal = Pbkdf2::slowEquals($a, $b);
	* </code>
	*/
	public static function slowEquals($a, $b)
	{
		$diff = strlen($a) ^ strlen($b);
		for($i = 0; $i < strlen($a) && $i < strlen($b); $i++)
		{
			$diff |= ord($a[$i]) ^ ord($b[$i]);
		}
		return $diff === 0; 
	}

	/**
	 * PBKDF2 key derivation function as defined by RSA's PKCS #5: https://www.ietf.org/rfc/rfc2898.txt
	 *
	 * @param stirng $algorithm the hash algorithm to use. Recommended: SHA256
	 * @param string $password the password.
	 * @param string $salt a salt that is unique to the password.
	 * @param int $count iteration count. Higher is better, but slower. Recommended: At least 1000.
	 * @param int $key_length the length of the derived key in bytes.
	 * @param boolean $raw_output if true, the key is returned in raw binary format. Hex encoded otherwise.
	 * @return string a salted and hashed version of the specified password
	 *
	 * <info>
	 *    Test vectors can be found here: https://www.ietf.org/rfc/rfc6070.txt
	 *
	 *    This implementation of PBKDF2 was originally created by https://defuse.ca
	 *    With improvements by http://www.variations-of-shadow.com
	 * </info>
	 * <code>
	 *   $hash = Pbkdf2::pbkdf2($algorithm, $password, $salt, $count, $keyLength, $rawOutput);
	 * </code>
	 */
	public static function pbkdf2($algorithm, $password, $salt, $count, $key_length, $raw_output = false)
	{
		$algorithm = strtolower($algorithm);
		if(!in_array($algorithm, hash_algos(), true))
			die('PBKDF2 ERROR: Invalid hash algorithm.');
		if($count <= 0 || $key_length <= 0)
			die('PBKDF2 ERROR: Invalid parameters.');

		$hash_length = strlen(hash($algorithm, "", true));
		$block_count = ceil($key_length / $hash_length);

		$output = "";
		for($i = 1; $i <= $block_count; $i++) {
			// $i encoded as 4 bytes, big endian.
			$last = $salt . pack("N", $i);
			// first iteration
			$last = $xorsum = hash_hmac($algorithm, $last, $password, true);
			// perform the other $count - 1 iterations
			for ($j = 1; $j < $count; $j++) {
				$xorsum ^= ($last = hash_hmac($algorithm, $last, $password, true));
			}
			$output .= $xorsum;
		}

		if($raw_output)
		{
			return substr($output, 0, $key_length);
		}
		else
		{
			return bin2hex(substr($output, 0, $key_length));
		}
	}
}
?>
