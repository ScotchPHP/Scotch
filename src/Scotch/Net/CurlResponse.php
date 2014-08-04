<?php
namespace Scotch\Net;

class CurlResponse
{
	public $header;
	public $httpCode;
	public $responseText;
	public $lastUrl;
	
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