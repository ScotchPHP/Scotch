<?php
namespace Scotch\Caching;

use Scotch\Caching\ICache as ICache;

/**
* Memory Cache Module.  This class provides function and methods to store, retrieve, and delete 
* items in a memory cache.
*
*/
class MemoryCache implements ICache
{
	// Start ICache Methods
	
	function exists($key)
	{
		return wincache_ucache_exists($key);
	}
	
	function get($key, $foundInCache = false)
	{
		$data = null;
		$data = wincache_ucache_get($key, $foundInCache);
		
		return $data;
	}
	
	function set($key, $value, $timeToLive)
	{
		 wincache_ucache_set($key, $value, $timeToLive);
	}
	
	function delete($key)
	{
		wincache_ucache_delete($key);
	}
	
	function clear()
	{
		wincache_ucache_clear();
	}
	
	// End ICache Methods
}
?>