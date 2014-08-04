<?php
namespace Scotch\Models;

use Scotch\Utilities\WebUtilities as WebUtilities;
use Scotch\Collections\Collection as Collection;

/**
* Base object for data models.  Provides a constructor that will parse an array into properties.
*/
abstract class BaseModel
{
	public function __construct($properties = array())
	{
		$this->setData($properties);
	}
	
	public function setData($properties = array())
	{	
		if(count($properties)>0)
		{
			//$typeMap = $this->getTypeMap();
			foreach($properties as $key => $value)
			{
				if(property_exists($this, $key))
				{
					$this->$key = $this->mapToType($key, $value);
				}
			}
		}
	}
	
	public function toArray($properties = null)
	{
		return get_object_vars($this);
	}
	
	protected function getTypeMap()
	{
		return array();
	}
	
	protected function getTypeFromKey($key)
	{
		$typeMap = $this->getTypeMap();
		return WebUtilities::getInstance()->getValue($typeMap, $key);
	}
	
	protected function mapToType($key, $value)
	{
		$type = $this->getTypeFromKey($key);
		$outValue = $value;
		if(isset($type))
		{
			$value = WebUtilities::getInstance()->convertToType($value, $type);
		}
		return $value;
	}
	
}
?>