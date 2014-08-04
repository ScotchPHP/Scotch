<?php
namespace Scotch\Utilities;

use Scotch\Utilities\WebUtilities as WebUtilities;
use Scotch\Utilities\WebConversionTypes as WebConversionTypes;
use Scotch\Collections\PagedCollection as PagedCollection;
use Scotch\Web\ControlTypes as ControlTypes;
use Scotch\Exceptions\InvalidTypeException as InvalidTypeException;

/**
* HTML Utilities class provides basica HTML rendering functions.
*/
class HtmlUtilities extends WebUtilities
{
	private static $noValueAttributes = array(
		"checked" => "*",
		"selected" => "*",
		"readonly" => "*",
		"disabled" => "*",
	);
	
	private static $singletonInstance;
	
	/**
	* Private constructor for singleton instance
	*/
	private function __construct()
	{
	}
	
	/**
	* Static getInstance method for singleton implementation.
	*
	* @returns Kohva\Scotch\Utilities\HtmlUtilities the singleton object 
	*/
	public static function getInstance()
	{
		if(!self::$singletonInstance)
		{
			self::$singletonInstance = new HtmlUtilities();
		}
		
		return self::$singletonInstance;
	}
	
	/**
	* Create pagination.
	*
	*/
	public function pagination($parameters = array())
	{
		if(is_object($parameters) && method_exists($parameters, "toArray"))
		{
			$parameters = $parameters->toArray();
		}
		$page = $this->getValue($parameters, "page", $this->request("pg", WebConversionTypes::WebInt, 1));
		$pageSize = $this->getValue($parameters, "pageSize", $this->request("pg", WebConversionTypes::WebInt, 20));
		$maxPages = $this->getValue($parameters, "maxPages", 10); 
		$rows = $this->getValue($parameters, "maxRows");
		$includeQueryString = $this->getValue($parameters, "includeQueryString", true);
		$includeFormVariables = $this->getValue($parameters, "includeFormVariables", false);
		
		if($pageSize <= 0)
		{
			return null;
		}
		$maxRows = $maxPages * $pageSize;
		$pages = ceil($rows / $pageSize);
		if ( $pages <= 1 )
		{
			return null;
		}
		
		//build url & querystring
		$url = $this->getUrl();
		$url .= $this->buildQueryString(
			array(
				"includeQueryString" => $includeQueryString,
				"includeFormVariables" => $includeFormVariables,
			),
			array("pg")
		);
		
		$html = "<div";
		$attributes = array(
			"id" => $this->getValue($parameters, "id"),
			"data-page" => $this->getValue($parameters, "page", 1),
			"data-page-size" => $this->getValue($parameters, "pageSize"),
			"class" => "pagination " . $this->getValue($parameters, "class", ""),
		);
		$html .= $this->createAttributes($attributes);
		
		//previous
		$html .= "><ul>";
		$html .= "<li";
		$html .= ($page == 1) ? " class=\"disabled\"" : "";
		$html .= ">";
		$html .= "<a href=\"";
		if( $page == 1 )
		{
			$html .= "";
		}
		else
		{
			$html .= ($page - 1) == 1 ? $url : $this->appendQueryStringParameter($url, "pg", ($page - 1));
		}
		$html .= "\" data-page=\"" . ($page - 1) . "\">&lsaquo;</a></li>";
		
		//pages
		$active = "active";
		for($i = 1; $i <= $pages; $i++){
			$active = "";
			if($i == $page){
				$active = " class=\"active\" ";
			}
			$html .= "<li$active><a href=\"";
			$html .= $i == 1 ? $url : $this->appendQueryStringParameter($url, "pg", $i);
			$html .= "\" data-page=\"$i\">$i</a></li>";
		}
		
		//next
		$html .= "<li";
		$html .= $page == $pages ? " class=\"disabled\"" : "";
		$html .= "><a href=\"";
		if ( $page == $pages )
		{
			$html .= "";
		}
		else
		{
			$html .= $this->appendQueryStringParameter($url, "pg", ($page + 1));
		}
		$html .= "\" data-page=\"" . ($page + 1) . "\">&rsaquo;</a></li>";
		$html .= "</ul></div>";
		
		return $html;
	}
	
	/**
	* Create an Html text box by using <input type="text"/>
	*
	* @param $parameters associative array of parameters to create the html element 
	* @returns string the html to render and input text
	*/
	public function textbox($parameters = array())
	{
		$parameters["type"] = "text";
		return $this->createInput($parameters);
	}
	
	/**
	* Create an Html password box by using <input type="password"/>
	*
	* @param $parameters associative array of parameters to create the html element
	* @returns string the html to render and input
	*/
	public function password($parameters = array())
	{
		$parameters["type"] = "password";
		return $this->createInput($parameters);
	}
	
	/**
	* Create an Html hidden input by using <input type="hidden"/>
	*
	* @param $parameters associative array of parameters to create the html element
	* @returns string the html to render and input
	*/
	public function hidden($parameters = array())
	{
		$parameters["type"] = "hidden";
		return $this->createInput($parameters);
	}
	
	/* 
	* Create a block of html code
	*
	* @param $parameters associate array of parameters 
	* @returns string the html to render
	*/
	public function block($parameters = array())
	{
		return $this->getValue($parameters,"html");
	}
	
	/**
	* Create an Html password box by using the <textarea></textarea> element
	*
	* @param $parameters associative array of parameters to create the html element
	* @returns string the html to render and text area
	*/
	public function textarea($parameters = array())
	{
		$name = $this->getValue($parameters, "name", "");
		$data = $this->getValue($parameters, "data");
		
		$attributes = array(
			"name" => $name,
			"id" => $this->getValue($parameters, "id"),
			"cols" => $this->getValue($parameters, "cols", "40"),
			"rows" => $this->getValue($parameters, "rows", "3"),
			"class" => $this->getValue($parameters, "class"),
			"style" => $this->getValue($parameters, "style"),
			"placeHolder" => $this->getValue($parameters, "placeholder"),
			"tooltip" => $this->getValue($parameters, "tooltip", "text"),
			"disabled" => $this->getValue($parameters, "disabled"),
			"readonly" => $this->getValue($parameters, "readonly"),
			"maxlength" => $this->getValue($parameters, "maxlength"),
			"wrap" => $this->getValue($parameters, "wrap"),
		);
		if(is_array($data))
		{
			foreach($data as $dataKey => $dataValue)
			{
				$attributes["data-".$dataKey] = $dataValue;
			}
		}
		
		$output = "<textarea";
		$output .= $this->createAttributes($attributes);
		$output .= ">" . $this->getValue($parameters, "value") . "</textarea>";
		
		return $output;
	}
	
	/**
	* Create an Html checkbox by using <input type="checkbox"/>
	*
	* @param $parameters associative array of parameters to create the html element
	* @returns string the html to render and input
	*/
	public function checkbox($parameters = array())
	{
		$parameters["type"] = "checkbox";
		$parameters["checked"] = $this->setChecked($this->getValue($parameters, "value"), $this->getValue($parameters, "inputValue"));
		$name = $this->getValue($parameters, "name");
		$label = $this->getValue($parameters, "label");
		$id = $this->getValue($parameters, "id");
		
		$output = $this->createInput($parameters);
		if($label)
		{
			$output .= "<label class=\"checkbox\" for=\"$id\"><span></span>$label</label>";
		}
		
		return $output;
	}
	
	/**
	* Create an Html radio button by using <input type="radio"/>
	*
	* @param $parameters associative array of parameters to create the html element
	* @returns string the html to render and input
	*/
	public function radio($parameters = array())
	{
		$parameters["type"] = "radio";
		$parameters["checked"] = $this->setChecked($this->getValue($parameters, "value"), $this->getValue($parameters, "inputValue"));
		$name = $this->getValue($parameters, "name");
		$label = $this->getValue($parameters, "label");
		$id = $this->getValue($parameters, "id");
		
		$output = $this->createInput($parameters);
		if($label)
		{
			$output .= "<label class=\"radio\" for=\"$id\"><span></span>$label</label>";
		}
		return $output;
	}
	
	/**
	* Create an Html drop down by using <select/>
	*
	* @param $parameters associative array of parameters to create the html element
	* @param $options array of option values
	* @returns string the html to render and input
	*/
	public function dropDown($parameters = array(), $options = array())
	{
		$name = $this->getValue($parameters, "name", "");
		$data = $this->getValue($parameters, "data");
		$placeholder = $this->getValue($parameters, "placeholder");
		$doLocalization = $this->getValue($parameters, "localize", false);
		$class = $this->getValue($parameters, "class");
		$value = $this->getValue($parameters,"value");
		if (!$this->hasValue($this->getValue($parameters,"value")))
		{
			$class = $this->appendAttributeValue($class, "empty");
		}
		$output = "<select";
		$attributes = array(
			"name" => $this->getValue($parameters, "name", ""),
			"id" => $this->getValue($parameters, "id"),
			"class" => $class,
			"size" => $this->getValue($parameters, "size"),
			"style" => $this->getValue($parameters, "style"),
			"tooltip" => $this->getValue($parameters, "tooltip", "text"),
			"disabled" => $this->getValue($parameters, "disabled"),
			"readonly" => $this->getValue($parameters, "readonly"),
		);
		
		if(is_array($data))
		{
			foreach($data as $dataKey => $dataValue)
			{
				$attributes["data-".$dataKey] = $dataValue;
			}
		}
		
		$output .= $this->createAttributes($attributes);
		$output .= ">";
		
		if ( empty($options) )
		{
			$options = $this->getValue($parameters, "options");
		}
		
		if ( $this->hasValue($placeholder) )
		{
			$output .= "<option value=\"\"" . ((!$this->hasValue($value)) ? " selected" : "") . ">".$placeholder."</option>";
			//$output .= "<optgroup label=\"-------------------------------------\"></optgroup>";
			$output .= "<option value=\"\">-------------------------------------</option>";
		}
		
		if ( $this->hasValue($options) )
		{
			foreach ($options as $key => $value)
			{
				if ( is_array($value) )
				{
					$optionData = $this->getValue($value, "data");
					$optionText = $this->getValue($value, "text");
					$optionText = ($doLocalization) ? $this->getString($optionText) : $optionText;
					
					$output .= "<option value=\"".$this->getValue($value, "value")."\"";
					//set selected if value matches option
					if($this->getValue($parameters,"value") === $this->getValue($value, "value"))
					{
						$output .= " selected";
					}
					//set data tags on current option
					if( is_array($optionData) )
					{
						foreach($optionData as $dataKey => $dataValue)
						{
							$output .= " data-".$dataKey."=\"".$dataValue."\"";
						}
					}
					$output .= ">" . $optionText . "</option>";
				}
				else
				{
					$value = ($doLocalization) ? $this->getString($value) : $value;
					
					$output .= "<option value=\"".$key."\"";
					if($this->getValue($parameters,"value") === $key)
					{
						$output .= " selected";
					}
					$output .= ">".$value."</option>";
				}
			}
		}
		
		$output .= "</select>";
		
		return $output;
	}
	
	/**
	* Create an Html Search textbox by using <input type="text"/>
	*
	* @param $parameters associative array of parameters to create the html element 
	* @returns string the html to render and input text
	*/
	public function searchbox($parameters = array())
	{
		$parameters["type"] = "text";
		$parameters["name"] = $this->getValue($parameters, "name", "search");
		$parameters["value"] = $this->getValue($parameters, "value", $this->request($parameters["name"]));
		$parameters["class"] = trim(trim(str_replace("search", "", $this->getValue($parameters, "class")))." search");
		$parameters["placeholder"] = $this->getValue($parameters, "placeholder", "Search");
		return $this->createInput($parameters);
	}
	
	public function calendar($parameters = array())
	{
		$parameters["type"] = "text";
		$parameters["class"] = trim(trim(str_replace("datepicker", "", $this->getValue($parameters, "class")))." datepicker");
		$output = $this->createInput($parameters);
		//$output .= "&nbsp;<img src=\"/images/cal.png\" class=\"cal\" />";
		return $output;
	}
	
	/**
	* Create an Html control group
	*
	* @param $parameters associative array of parameters to create the html element
	* @param $options array of option values
	* @returns string the html to render and input
	*/
	public function controlGroup($parameters = array(), $controls = array())
	{
		$controlGroupActive = false;
		$label = $this->getValue($parameters, "label");
		$helper = $this->getValue($parameters, "helper");
		$error = $this->getValue($parameters, "error", "");
		$id = $this->getValue($parameters, "id");
		$class = $this->getValue($parameters, "class");
		if ( is_array($error) )
		{
			$error = implode(", ",array_filter($error));
		}
		$controlGroupClass = "ctrl-group";
		$controlGroupClass .= ($label === null) ? " ctrl-no-label" : "";
		$controlGroupClass .= $this->hasValue($error) ? " ctrl-error" : "";
		$controlGroupClass .= ($this->hasValue($class)) ? " ".$class : "";
		$controlGroupID = "";
		$controlGroupLabelID = "";
		$controlGroupHelperID = "";
		$controlGroupMessageID = "";
		if ( $this->hasValue($id) )
		{
			$controlGroupID = " id=\"ctrl-group-".$id."\"";
			$controlGroupLabelID = " id=\"ctrl-label-".$id."\"";
			$controlGroupHelperID = " id=\"ctrl-help-".$id."\"";
			$controlGroupMessageID = " id=\"ctrl-msg-".$id."\"";
		}
		
		$output =	"<div class=\"".$controlGroupClass."\"".$controlGroupID.">";
		if ( $this->hasValue($label) )
		{
			$output .=	"<label".$controlGroupLabelID.">".$this->getValue($parameters, "label")."</label>";
		}
		$output .=		"<div class=\"ctrls\">";
		if (is_array($controls))
		{
			if ( count($controls) == 1 )
			{
				$controlGroupActive = ( $this->getValue($controls[0], "active") === false ) ? false : true;
				if ( $controlGroupActive )
				{
					$output .=		$this->$controls[0]["type"]($controls[0]);
				}
			}
			else
			{
				foreach($controls as $control)
				{
					$controlActive = ( $this->getValue($control, "active") === false ) ? false : true;
					if ( $controlActive )
					{
						$controlGroupActive = true;
						$controlClassAdditional = $this->getValue($control, "controlClass");
						$controlClass = "ctrl";
						$controlClass .= ($this->hasValue($controlClassAdditional)) ? " ".$controlClassAdditional : "";
						$size = $this->getValue($control, "size");
						if ( !is_numeric($size) )
						{
							$controlClass .= " ".$size;
						}
						$output .=	"<div class=\"".$controlClass."\">";
						if ( !is_numeric($size) )
						{
							unset($control["size"]);
						}
						$output .=		$this->$control["type"]($control);
						
						if ( $this->hasValue($this->getValue($control, "label")) && $control["type"] != ControlTypes::CheckBox && $control["type"] != ControlTypes::RadioButton )
						{
							$output .=	"<label>".$control["label"]."</label>";
						}
						$output .=	"</div>";
					}
				}
			}
		}
		else
		{
			throw new InvalidTypeException('Invalid Type: controls must be an array');
		}
		
		if ( $controlGroupActive && $this->hasValue($helper) )
		{
			$output .=		"<span class=\"ctrl-help\"".$controlGroupHelperID.">".$helper."</span>";
		}
		
		if ( $controlGroupActive )
		{
			$output .=			"<span class=\"ctrl-msg\"".$controlGroupMessageID.">".$error."</span>";
			
			$output .=		"</div>" .
						"</div>";
		}
		else
		{
			$output = null;
		}
		
		return $output;
	}
	
	public function renderAddress($parameters = array(), $renderInline = false)
	{
		$address = $this->getValue($parameters, "address");
		$address2 = $this->getValue($parameters, "address2");
		$city = $this->getValue($parameters, "city");
		$state = $this->getValue($parameters, "state");
		$postalCode = $this->getValue($parameters, "postalCode");
		$country = $this->getValue($parameters, "country");
		
		$separator = ($renderInline) ? " " : "<br/>";
		
		$output = null;
		$output .= $this->appendString($address, $separator);
		$output .= $this->appendString($address2, $separator);
		$output .= $this->appendString($city, ", ", array($state,$postalCode));
		$output .= $this->appendString($state, " ", array($postalCode,$country));
		$output .= $this->appendString($postalCode, $separator, $country);
		$output .= $country;
		
		return $output;
	}
	
	public function renderFullName($parameters = array())
	{
		$firstName = $this->getValue($parameters, "firstName");
		$middleName = $this->getValue($parameters, "middleName");
		$lastName = $this->getValue($parameters, "lastName");
		
		$output = $this->hasValue($firstName) ? $firstName : null;
		$output .= $this->prependString($middleName, " ");
		$output .= $this->prependString($lastName, " ");
		
		return $output;
	}
	
	public function renderPhone($parameters = array())
	{
		$phone = $this->getValue($parameters, "phone");
		$phoneExt = $this->getValue($parameters, "phoneExt");
		
		$output = $this->hasValue($phone) ? $phone : null;
		$output .= $this->prependString($phoneExt, " x");
		
		return $output;
	}
	
	public function appendAttributeValue($values, $newValue)
	{
		if( !$this->hasValue($values) )
		{
			$values = $newValue;
		}
		else
		{
			if( strpos($values, $newValue) === false )
			{
				$values .= " " . $newValue;
			}
		}
		return trim($values);
	}
	
	/**
	* Create an Html input element by using <input/>
	*
	* @param $parameters associative array of parameters to create the html element
	* @returns string the html to render and input
	*/
	private function createInput($parameters = array(), $isBitControl = false)
	{
		$output = "";
		$name = $this->getValue($parameters, "name", "");
		$value = $this->getValue($parameters, "value");
		$data = $this->getValue($parameters, "data");
		$active = $this->getValue($parameters, "active", true);
		$prepend = $this->getValue($parameters, "prepend");
		$append = $this->getValue($parameters, "append");
		$checkedValue = null;
		
		if ($isBitControl)
		{
			$value = $this->getValue($parameters, "checkValue");
			$checkedValue = $this->getValue($parameters, "value");
		}
		
		if ( isset($prepend) || isset($append) )
		{
			if ( isset($prepend) && isset($append) )
			{
				$appendClass = "append-both";
			}
			else if ( isset($prepend) )
			{
				$appendClass = "append-lt";
			}
			else
			{
				$appendClass = "append-rt";
			}
			$output .= "<div class=\"append ".$appendClass."\">";
		}
		
		if ( isset($prepend) )
		{
			$output .= $this->createInputAppend($prepend, "prepend");
		}
		
		$output .= "<input";
		$attributes = array(
			"type" => $this->getValue($parameters, "type", "text"),
			"name" => $name,
			"id" => $this->getValue($parameters, "id"),
			"value" => $value,
			"size" => $this->getValue($parameters, "size"),
			"maxlength" => $this->getValue($parameters, "maxlength"),
			"checked" => $this->getValue($parameters, "checked"),
			"class" => $this->getValue($parameters, "class"),
			"style" => $this->getValue($parameters, "style"),
			"placeholder" => $this->getValue($parameters, "placeholder"),
			"tooltip" => $this->getValue($parameters, "tooltip"),
			"disabled" => $this->getValue($parameters, "disabled"),
			"readonly" => $this->getValue($parameters, "readonly"),
			"autocomplete" => $this->getValue($parameters, "autocomplete"),
		);
		
		if(is_array($data))
		{
			foreach($data as $dataKey => $dataValue)
			{
				$attributes["data-".$dataKey] = $dataValue;
			}
		}
		
		if ( !is_numeric($attributes["size"]) )
		{
			$attributes["class"] = trim($attributes["class"]." ".$attributes["size"]);
			unset($attributes["size"]);
		}
		$output .= $this->createAttributes($attributes);
		if( $this->hasValue($checkedValue) && $checkedValue == $value )
		{
			$output .= " checked";
		}
		$output .= " />";
		
		if ( isset($append) )
		{
			$output .= $this->createInputAppend($append, "append");
		}
		
		if ( isset($prepend) || isset($append) )
		{
			$output .= "</div>";
		}
		
		return $output;
	}
	
	/**
	* Create an html element attribute in the form {attribute}="{value}"
	* 
	* @param string $attribute the name of the attribute 
	* @param string $value the value of the attribute
	* @returns string the string that is an html attribute {attribute}="{value}"
	*/
	private function createAttribute($attribute, $value)
	{
		$output = "";
		if( $this->hasValue($value) )
		{
			if ( $this->hasValue($this->getValue(self::$noValueAttributes, $attribute)) )
			{
				if ( $value == true )
				{
					$output = " $attribute";
				}
			}
			else
			{
				$output = " $attribute=\"$value\"";
			}
		}
		return $output;
	}
	
	/**
	* Create a list of html element attributes in the form {attribute}="{value}"
	* 
	* @param array $attributes and associate array of attribute in the form {attributeName} => {attributeValue}
	* @returns string the string that is the list of html attributes in the form {attribute}="{value}"
	*/
	private function createAttributes($attributes = array())
	{
		$output = "";
		foreach($attributes as $key => $value)
		{
			$output .= $this->createAttribute($key,$value);
		}
		return $output;
	}
	
	/**
	* Evaluate the "value" and "inputValue" parameters against each other and return a "checked" value for checkboxes & radiobuttons
	* 
	* @value string
	* @inputValue string
	* @returns string the checked string
	*/
	private function setChecked($value, $inputValue)
	{
		if (is_bool($value))
		{
			$value = ($value) ? "true" : "false";
		}
		if (is_bool($inputValue))
		{
			$inputValue = ($inputValue) ? "true" : "false";
		}
		$checked = false;
		if ( $value == $inputValue )
		{
			$checked = true;
		}
		return $checked;
	}
	
	private function createInputAppend($append = array(), $direction = "append")
	{
		$value = $this->getValue($append, "value", "");
		$id = $this->getValue($append, "id");
		$class = $this->getValue($append, "class");
		$style = $this->getValue($append, "style");
		
		$appendClass = ($direction == "append") ? "add-rt" : "add-lt";
		$appendClass = $this->appendAttributeValue($appendClass, $class);
		
		$output = "<span";
		if( isset($id) )
		{
			$output .= " id=\"".$id."\"";
		}
		if ( isset($style) )
		{
			$output .= " style=\"".$style."\"";
		}
		$output .= " class=\"".$appendClass."\">".$value."</span>";
		
		return $output;
	}
}
?>