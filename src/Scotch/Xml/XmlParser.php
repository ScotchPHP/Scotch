<?php
namespace Scotch\Xml;

class XmlParser
{
	private $parser;
	
	private $encoding = "UTF-8";
	
	
	function __contruct($parameters = array())
	{
		$this->parser = xml_create_parser();
	}
	
	function __decontruct($parameters = array())
	{
		if(isset($this->parser))
		{
			xml_parser_free($this->parser);
		}
	}
}
?>