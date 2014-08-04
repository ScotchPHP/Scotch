<?php
namespace Scotch\Localization;

use Scotch\System as System;
use Scotch\Utilities\Utilities as Utilities;

class LocalizationService 
{
	const DEFAULT_LANGUAGE = "en-US";
	
	public $currentLanguage = self::DEFAULT_LANGUAGE;

	private $languages = array();
	
	private static $singletonInstance;
	
	private $util;
	
	private function __construct()
	{
		$this->util = Utilities::getInstance();
	}
	
	public static function getInstance()
	{
		if(!self::$singletonInstance)
		{
			self::$singletonInstance = new LocalizationService();
		}
		
		return self::$singletonInstance;
	}
	
	private function lookupString($key)
	{
		$val = null;
		
		$languageSource = $this->util->getValue($this->languages, $this->currentLanguage);
		if(!isset($languageSource))
		{
			$languageSourceClasses = System::$application->configuration->languages;
			$languageSourceClass = Utilities::getInstance()->getValue($languageSourceClasses, $this->currentLanguage);
			
			if(isset($languageSourceClass))
			{
				$languageSource = new $languageSourceClass();
				$languageSource = $languageSource->getMap();
				array_push($this->languages, $languageSource);
			}
			
			$val = $this->util->getValue($languageSource, $key);
		}
		else
		{
			$val = $this->util->getValue($languageSource, $key);
		}
		
		return $val;
	}
	
	public function getString($key = null)
	{
		$val = null;
		if(!$this->util->hasValue($this->currentLanguage))
		{
			$language = self::DEFAULT_LANGUAGE;
		}
		if(isset($key))
		{
			// Lookup 
			$val = $this->lookupString($key);
		}
		return $val;
	}
	
	
}
?>