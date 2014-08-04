<?php
namespace Scotch\Encryption;

interface IEncryptionProvider
{
	function encrypt($value, $key);
	
	function decrypt($value, $key);
}
?>