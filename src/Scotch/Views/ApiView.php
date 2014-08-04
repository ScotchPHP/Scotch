<?php
namespace Scotch\Views;

use Scotch\Serialize\XmlSerializer as XmlSerializer;

class ApiView
{
	const RETURN_TYPE_JSON = "json";
	const RETURN_TYPE_XML = "xml";
	
	public $data;
	
	function __construct($data = array())
	{
		$this->data = $data;
	}
	
	function render($r = self::RETURN_TYPE_JSON)
	{
		if($r == self::RETURN_TYPE_XML)
		{
			header('Content-Type: application/xml');
			echo XmlSerializer::serialize($this->data);
		}
		else
		{
			header('Content-Type: application/json');
			echo json_encode($this->data);
		}
	}
}
?>