<?php
namespace Scotch\Services;

use Scotch\System as System;
use Scotch\Services\Service as Service;
use Scotch\Exceptions\InvalidArgumentException as InvalidArgumentException;

class CacheableService extends Service 
{
	function __construct()
	{
		parent::__construct();
	}
	
	protected function retrieveCachedData($cacheKey, $getDataFunction, $source, $getDataParamertes = array(), $parameters = array(), $timeToLiveInCache = 0)
	{
		$value = null;
		if(!$this->retrieveFromCache($cacheKey, $value))
		{
			if(is_callable($getDataFunction))
			{
				$value = $getDataFunction($source, $getDataParamertes, $parameters);
				$this->putInCache($cacheKey, $value); 
			}
			else
			{
				throw new InvalidArgumentException("Parameter 'getDataFunction' must be a callable function.");
			}
		}
		
		return $value;
	}

/* CACHE METHODS */
	protected function retrieveFromCache($key, &$value)
	{
		$wasFound = false;
		
		$value = null;
		$cacheProvider = null;		
		if($this->hasCacheProvider($cacheProvider))
		{
			if($cacheProvider->exists($key))
			{
				$wasFound = true;
				$value = $cacheProvider->get($key);
			}
		}
		
		return $wasFound;
	}
	
	protected function putInCache($key, $value, $timeToLiveInCache = 0)
	{
		$cacheProvider = null;		
		if($this->hasCacheProvider($cacheProvider))
		{
			$value = $cacheProvider->set($key, $value, $timeToLiveInCache);
		}		
	}
	
	protected function removeFromCache($key)
	{
		$cacheProvider = null;		
		if($this->hasCacheProvider($cacheProvider))
		{
			$value = $cacheProvider->delete($key);
		}
	}
	
	protected function clearCache()
	{
		$cacheProvider = null;		
		if($this->hasCacheProvider($cacheProvider))
		{
			$cacheProvider->clear();
		}
	}
	
	private function hasCacheProvider(&$cacheProvider)
	{
		$hasCacheProvider = false;
		
		$cacheProvider = System::$application->cache;
		if(isset($cacheProvider))
		{
			$hasCacheProvider = true;
		}
		
		return $hasCacheProvider;
	}
	
}
?>