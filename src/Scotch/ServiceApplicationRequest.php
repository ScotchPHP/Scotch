<?php
namespace Scotch;

use Scotch\Serialize\JsonSerializable as JsonSerializable;

class ServiceApplicationRequest extends JsonSerializable
{
	public $serviceName;
	public $operation;
	
	function __construct($properties = array())
	{
		if(count($properties)>0)
		{
			foreach($properties as $key => $value)
			{
				if(property_exists($this, $key))
				{					
					$this->$key = $value;
				}
			}
		}
	}
	
}
?>
