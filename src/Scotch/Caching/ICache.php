<?php
namespace Scotch\Caching;

interface ICache
{
	/**
	* Checks if a key is in the cache
	* 
	* @param mixed $key the key to store to look for in the cache.
	* @return boolean returns true if the key is in the cache and false otherwise
	*/
	function exists($key);
	
	/**
	* Get a value from the cache if the key exists in the cache.
	*
	* @param mixed $key the key to lookup in the cache.
	* @param boolean $foundInCache this is an output parameter that will be true if the value is found in the cache and false otherwise.
	* @return mixed returns the value found at the given key.
	*/
	function get($key, $foundInCache = false);
	
	/**
	* Adds a value to the cache at the specified key if the key is not in the cache.  If the value is found it is updated to the new value.
	*
	* @param mixed $key the key to lookup in the cache.
	* @param boolean $foundInCache this is an output parameter that will be true if the value is found in the cache and false otherwise.
	* @return mixed returns the value found at the given key.
	*/
	function set($key, $value, $timeToLive);
	
	/**
	* Deletes the value in the cache at the specified key.
	*
	* @param mixed $key the key to delete from the cache
	*/
	function delete($key);
	
	/**
	* Clear the entire cache.
	*/
	function clear();
}
?>