<?php
namespace Scotch\Utilities;

use DateTime as DateTime;
use DateInterval as DateInterval;
use Scotch\Localization\Currencies as Currencies;

class Utilities 
{
	const BufferChunkSize = 0x100000; //1048576;		// 1 * (1024 * 1024)
	
	// List of patterns and date formats
	private static $datePatternFormats = array(
		array( "pattern" => "/^([1]*[0-2]|[0]*[0-9])[\/\.-]([3][0-1]|[0-2]*[0-9])[\/\.-][0-9][0-9]$/", "format" => "m#d#y"),
		array( "pattern" => "/^([1]*[0-2]|[0]*[0-9])[\/\.-]([3][0-1]|[0-2]*[0-9])[\/\.-][0-9][0-9][0-9]+$/", "format" => "m#d#Y"),
		array( "pattern" => "/^([3][0-1]|[0-2]*[0-9])[\/\.-]([1][0-2]|[0]*[0-9])[\/\.-][0-9][0-9]$/", "format" => "d#m#y"),
		array( "pattern" => "/^([3][0-1]|[0-2]*[0-9])[\/\.-]([1][0-2]|[0]*[0-9])[\/\.-][0-9][0-9][0-9]+$/", "format" => "d#m#Y"),
		array( "pattern" => "/^([0-9][0-9])[\/\.-]([1]*[0-2]|[0]*[0-9])[\/\.-]([3][0-1]|[0-2]*[0-9])$/", "format" => "y#m#d"),
		array( "pattern" => "/^([0-9][0-9][0-9]+)[\/\.-]([1]*[0-2]|[0]*[0-9])[\/\.-]([3][0-1]|[0-2]*[0-9])$/", "format" => "Y#m#d"),
		array( "pattern" => "/^(january|jan|february|feb|march|mar|april|apr|may|june|jun|july|jul|august|aug|september|sept|sep|october|oct|november|nov|december|dec) ([3][0-1]|[0-2]*[0-9])([,][ ]*[0-9][0-9])$/", "format" => "F d, y"),
		array( "pattern" => "/^(january|jan|february|feb|march|mar|april|apr|may|june|jun|july|jul|august|aug|september|sept|sep|october|oct|november|nov|december|dec) ([3][0-1]|[0-2]*[0-9])([,][ ]*[0-9][0-9][0-9]+)$/", "format" => "F d, Y"),
		array( "pattern" => "/^(january|jan|february|feb|march|mar|april|apr|may|june|jun|july|jul|august|aug|september|sept|sep|october|oct|november|nov|december|dec) ([3][0-1]|[0-2]*[0-9])([ ][0-9][0-9])$/", "format" => "F d y"),
		array( "pattern" => "/^(january|jan|february|feb|march|mar|april|apr|may|june|jun|july|jul|august|aug|september|sept|sep|october|oct|november|nov|december|dec) ([3][0-1]|[0-2]*[0-9])([ ][0-9][0-9][0-9]+)$/", "format" => "F d Y"),
		array( "pattern" => "/^(january|jan|february|feb|march|mar|april|apr|may|june|jun|july|jul|august|aug|september|sept|sep|october|oct|november|nov|december|dec) ([3][0-1]|[0-2]*[0-9])(st|nd|rd|th)([,][ ]*[0-9][0-9])$/", "format" => "F dS, y"),
		array( "pattern" => "/^(january|jan|february|feb|march|mar|april|apr|may|june|jun|july|jul|august|aug|september|sept|sep|october|oct|november|nov|december|dec) ([3][0-1]|[0-2]*[0-9])(st|nd|rd|th)([,][ ]*[0-9][0-9][0-9]+)$/", "format" => "F dS, Y"),
		array( "pattern" => "/^(january|jan|february|feb|march|mar|april|apr|may|june|jun|july|jul|august|aug|september|sept|sep|october|oct|november|nov|december|dec) ([3][0-1]|[0-2]*[0-9])(st|nd|rd|th)([ ]*)([0-9][0-9])$/", "format" => "F dS y"),
		array( "pattern" => "/^(january|jan|february|feb|march|mar|april|apr|may|june|jun|july|jul|august|aug|september|sept|sep|october|oct|november|nov|december|dec) ([3][0-1]|[0-2]*[0-9])(st|nd|rd|th)([ ]*)([0-9][0-9][0-9]+)$/", "format" => "F dS Y"),
	);
	
	// List of patterns and time formats
	private static $timePatternFormats = array(
		array( "pattern" => "/^([1][0-2]|[0]*[0-9])[:]([0-5][0-9])( (am|pm|AM|PM))$/", "format" => "g:i a"),
		array( "pattern" => "/^([1][0-2]|[0]*[0-9])[:]([0-5][0-9])([am|pm|AM|PM])$/", "format" => "h:ia"),
		array( "pattern" => "/^([1][0-2]|[0]*[0-9])[:]([0-5][0-9])[:]([0-5][0-9])([ ]am|pm|AM|PM)$/", "format" => "h:i:s a"),
		array( "pattern" => "/^([1][0-2]|[0]*[0-9])[:]([0-5][0-9])[:]([0-5][0-9])(am|pm|AM|PM)$/", "format" => "h:i:sa"),
		array( "pattern" => "/^([1][0-2]|[0]*[0-9])[:]([0-5][0-9])[:]([0-5][0-9])[.]([0-9]+)( (am|pm|AM|PM))$/", "format" => "h:i:s.u a"),
		array( "pattern" => "/^([1][0-2]|[0]*[0-9])[:]([0-5][0-9])[:]([0-5][0-9])[.]([0-9])+(am|pm|AM|PM)$/", "format" => "h:i:s.ua"),
		array( "pattern" => "/^([2][0-3]|[0-1]*[0-9])[:]([0-5][0-9])$/", "format" => "H:i"),
		array( "pattern" => "/^([2][0-3]|[0-1]*[0-9])[:]([0-5][0-9])[:][0-5][0-9]$/", "format" => "H:i:s"),
		array( "pattern" => "/^([2][0-3]|[0-1]*[0-9])[:]([0-5][0-9])[:][0-5][0-9][.][0-9]+$/", "format" => "H:i:s.u"),
		
	);
	
	private static $singletonInstance;
	
	private function __construct()
	{
	}
	
	public static function getInstance()
	{
		if(!self::$singletonInstance)
		{
			self::$singletonInstance = new Utilities();
		}
		
		return self::$singletonInstance;
	}
	
	public function hasValue($value)
	{
		$hasValue = true;
		if (is_null($value))
		{
			$hasValue = false;
		}
		elseif ( is_string($value) )
		{
			if( strlen(trim($value)) == 0 )
			{
				$hasValue = false;
			}
		}
		elseif ( is_array($value) )
		{
			$hasValue = $this->arrayHasValue($value);
		}
		elseif ( is_object($value) )
		{
			$hasValue = $this->objectHasValue($value);
		}
		
		return $hasValue;		
	}
	
	public function isEmpty($value)
	{
		$isEmpty = true;
		
		if ( is_object($value) )
		{
			$isEmpty = $this->objectHasValue($value) ? false : true;
		}
		elseif ( is_array($value) )
		{
			$isEmpty = $this->arrayHasValue($value) ? false : true;
		}
		elseif ( $this->hasValue($value) )
		{
			$isEmpty = false;
		}
		
		return $isEmpty;
	}
	
	public function isEmail($email)
	{
		$isValid = true;
		if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$isValid = false;
		}
		
		return $isValid;
	}
	
	private function arrayHasValue($array = array())
	{
		$hasValue = false;
		
		foreach($array as $item)
		{
			if ( $this->hasValue($item) )
			{
				$hasValue = true;
				break;
			}
		}
		
		return $hasValue;
	}
	
	private function objectHasValue($object)
	{
		$hasValue = false;
		
		if ( method_exists($object,"isEmpty") )
		{
			if ( !$object->isEmpty() )
			{
				$hasValue = true;
			}
		}
		elseif ( isset($object) )
		{
			foreach ($object as $key => $value)
			{
				if ( $this->hasValue($value) )
				{
					$hasValue = true;
					break;
				}
			}
		}
		
		return $hasValue;
	}
	
	public function setNull($value)
	{
		$outValue = $value;
		if (!$this->hasValue($value)) 
		{
			$outValue = null;
		}
		
		return $outValue;
	}
	
	public function setEmtpy($value)
	{
		$outValue = $value;
		if (!$this->hasValue($value)) 
		{
			$outValue = "";
		}
		
		return $outValue;
	}
	
	public function setBit($value)
	{
		$outValue = false;
		if (!$this->hasValue($value))
		{
			$outValue = null;
		}
		elseif ( preg_match('/^true|1|yes$/',strtolower($value)) || $value === true )
		{
			$outValue = true;
		}
		return $outValue;
	}
	
	public function setInt($value)
	{
		$outValue = null;
		if($this->hasValue($value))
		{
			if(is_numeric($value))
			{
				$outValue = intval($value);
			}
		}
		
		return $outValue;
	}
	
	public function setFloat($value)
	{
		$outValue = null;
		$value = $this->cleanseNumeric($value);
		if($this->hasValue($value))
		{
			if(is_numeric($value))
			{
				$outValue = floatval($value);
			}
		}
		
		return $outValue;
	}
	
	private function cleanseNumeric($value)
	{
		return preg_replace('/,|\s|\$/i','',$value);
	}
	
	public function setDate($value, $format = null)
	{
		$outValue = null;
		if($this->hasValue($value))
		{
			if($value instanceof DateTime)
			{
				$outValue = $value;
			}
			else
			{	
				$value = trim($value);
				if(!$this->hasValue($format))
				{
					// No format given therefore we must try to find one :)
					foreach(Utilities::$datePatternFormats as $patternFormat)
					{
						$pattern = $this->getValue($patternFormat, "pattern");
						
						if(preg_match($pattern, $value) == 1)
						{
							$format = $this->getValue($patternFormat, "format");
							break; // Exit for loop
						}
					}
				}
				
				if($this->hasValue($format))
				{
					$possibleValue = DateTime::createFromFormat($format, $value);
					
					if ($possibleValue != false)
					{
						$outValue = $possibleValue;
						$outValue->setTime(0,0,0);
					}
				}
			}
		}
		
		return $outValue;
	}
	
	public function setTime($value, $format = null)
	{
		$outValue = null;
		if($this->hasValue($value))
		{
			if($value instanceof DateTime)
			{
				$outValue = $value;
			}
			else
			{	
				$value = trim($value);
				if(!$this->hasValue($format))
				{
					// No format given therefore we must try to find one :)
					foreach(Utilities::$timePatternFormats as $patternFormat)
					{
						$pattern = $this->getValue($patternFormat, "pattern");
						
						if(preg_match($pattern, $value) == 1)
						{
							$format = $this->getValue($patternFormat, "format");
							break; // Exit for loop
						}
					}
				}
				
				if($this->hasValue($format))
				{
					$possibleValue = DateTime::createFromFormat($format, $value);
					if($possibleValue != false)
					{
						$outValue = $possibleValue;
					}
				}
			}
		}
		
		return $outValue;
	}
	
	public function setDateTime($value, $format = null)
	{
	
		$outValue = null;
		if($this->hasValue($value))
		{
			if($value instanceof DateTime)
			{
				$outValue = $value;
			}
			else
			{	
				if($this->hasValue($format))
				{
					//Format was given attempt to parse the DateTime from the given format
					$outValue = DateTime::createFromFormat($value, $format);
				}
				else
				{
					// No format given there we will parse the date and time seperately and then combine them
					$datePart = null;
					$timePart = null;
					
					$splitPosition = strrpos($value, "T");
					if($splitPosition != false)
					{
						// Standard DateTime Format (UTC) 1994-11-05T08:15:30-05:00 corresponds to November 5, 1994, 8:15:30 am, US Eastern Standard Time.
						// The time can also be in a format similar too 1994-11-05T13:15:30Z  both stamps are equivilant
						// See http://www.w3.org/TR/NOTE-datetime for more info
						$datePart = trim(substr($value, 0, $splitPosition));
						$timePart = trim(trim(trim(substr($value, $splitPosition),"Z"),"T"));
						
						$splitPosition = strrpos($timePart, "-");
						if($splitPosition != false)
						{
							$timePart = substr($timePart, 0, $splitPosition);
						}
					}
					else
					{
						// Assume datetime is in a format similar to 1994-11-05 08:15:30 or simply (date) (time)
						// With this assumption we can split the string by the first space and assume the first part is the date and the last part
						// is the time.
						$splitPosition = strpos($value, " ");
						if ($splitPosition != false)
						{
							$datePart = trim(substr($value, 0, $splitPosition));
							$timePart = trim(substr($value, $splitPosition));
						}
						
					}					
					
					$datePart = $this->setDate($datePart);
					$timePart = $this->setTime($timePart);

					$dateHasValue = $this->hasValue($datePart);
					$timeHasValue = $this->hasValue($timePart);
					
					if(($dateHasValue) && ($timeHasValue))
					{
						// Combine the two parts
						$finalDateStr = $datePart->format("n/j/Y") . " " . $timePart->format("g:i:s.u a");
						$outValue = DateTime::createFromFormat("n/j/Y g:i:s.u a", $finalDateStr);							
					}
					else
					{
						// Take the item that has a value;
						$outValue = ($dateHasValue) ? $datePart : $timePart;
					}
				}
			}
		}
		
		return $outValue;
	}

	public function setGuid($value)
	{
		$outValue = null;
		if($this->hasValue($value))
		{
			$value = str_replace("_", "-", str_replace(array("{", "}"), "", $value));
			
			if (preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-[0-9A-F]{4}-[0-9A-F]{4}-[0-9A-F]{12}$/', $value))
			{
				$outValue = $value;
			}
		}
		
		return $outValue;
	}
	
	public function bitToString($value)
	{
		return ( $this->setBit($value) == true ) ? "true" : "false";
	}
	
	// Get Value Comment
	public function getValue(&$data, $key, $defaultValue = null)
	{
		$value = $defaultValue;
		if ( is_array($data) && isset($data[$key]) )
		{
			$value = $data[$key];
		}
		elseif ( is_object($data) && property_exists($data, $key) )
		{
			$value = $data->$key;
		}
		return $value;
	}
	
	/* left string function */
	public function left($value, $length)
	{
		return substr($value, 0, $length);
	}
	
	/* right string function */
	public function right($value, $length)
	{
		return substr($value, -$length);
	}
	
	public function coalesce($value1, $value2 = null)
	{
		if ( !is_array($value1) )
		{
			$value = $value1;
			if ( !$this->hasValue($value) )
			{
				$value = $value2;
			}
		}
		else
		{
			foreach($value1 as $value)
			{
				if ( $this->hasValue($value) )
				{
					break;
				}
			}
		}
		return $value;
	}
	
	public function writeLine($value)
	{
		echo $value . "<br/>";
	}
	
	public function elapsedTime($startTime, $endTime){
		$secs = $endTime - $startTime;
		$bit = array(
			"y" => $secs / 31556926 % 12,
			"w" => $secs / 604800 % 52,
			"d" => $secs / 86400 % 7,
			"h" => $secs / 3600 % 24,
			"m" => $secs / 60 % 60,
			"s" => $secs % 60
		);
		
		return $bit;
    }
	
	private function formatDatesAndTimes($dateTime = null, $defaultValue = null, $format = "n/j/Y g:i A")
	{
		$outValue = $defaultValue;
		if(($this->hasValue($dateTime)) && ($dateTime instanceof DateTime))
		{
			$outValue = $dateTime->format($format);
		}
		return $outValue;
	}
	
	public function formatDate($dateTime = null, $defaultValue = null, $format = "n/j/Y")
	{
		return $this->formatDatesAndTimes($dateTime, $defaultValue, $format);
	}
	
	public function formatTime($dateTime = null, $defaultValue = null, $format = "g:i A")
	{
		return $this->formatDatesAndTimes($dateTime, $defaultValue, $format);
	}
	
	public function formatDateTime($dateTime = null, $defaultValue = null, $format = "n/j/Y g:i A")
	{
		return $this->formatDatesAndTimes($dateTime, $defaultValue, $format);
	}
	
	public function formatNumber($value, $decimals = 2, $defaultValue = null, $decimalPoint = ".", $separator = ",")
	{
		$return = null;
		if ( $this->hasValue($value) && is_numeric($value) )
		{
			$return = number_format($value, $decimals, $decimalPoint, $separator);
		}
		elseif ( $this->hasValue($defaultValue) )
		{
			$return = number_format($defaultValue, $decimals, $decimalPoint, $separator);
		}
		return $return;
	}
	
	public function formatMoney($value, $currencyID = null, $decimals = 2, $defaultValue = null)
	{
		$currency = Currencies::getCurrency($currencyID);
		$value = $this->formatNumber($value, $decimals, $defaultValue, $currency->decimalPoint, $currency->separator);
		if( $this->hasValue($value) )
		{
			$value = ($currency->symbolPosition == "append") ? $value." ".$currency->currencyHtml : $currency->currencyHtml.$value ;
		}
		return $value;
	}
	
	public function greater($value1, $value2)
	{
		return $value1 > $value2 ? $value1 : $value2;
	}
	
	public function lesser($value1, $value2)
	{
		return $value1 < $value2 ? $value1 : $value2;
	}
	
	
	public function appendString($string, $append, $evalString = null)
	{
		$output = null;
		if ( $this->hasValue($string) )
		{
			$evalString = $this->hasValue($evalString) ? $evalString : $string;
			$output = $this->hasValue($evalString) ? $string . $append : null;
		}
		return $output;
	}
	
	public function prependString($string, $prepend, $evalString = null)
	{
		$output = null;
		if ( $this->hasValue($string) )
		{
			$evalString = $this->hasValue($evalString) ? $evalString : $string;
			$output = $this->hasValue($evalString) ? $prepend . $string : null;
		}
		return $output;
	}
	
	public function createMonthList($hasLeadingZero = true)
	{
		$list = array();
		$monthString = "";
		for($i = 1; $i <= 12; $i++)
		{
			$monthString = "" . $i;
			if($hasLeadingZero)
			{
				$monthString = substr("0" . $monthString, -2);
			}
			$list[$monthString] = $monthString;
		}
		return $list;
	}
	
	public function createDaysOfMonthList($maxDays = 31, $hasLeadingZero = true)
	{
		$list = array();
		$dayString = "";
		for($i = 1; $i <= $maxDays; $i++)
		{
			$dayString = "".$i;
			if($hasLeadingZero)
			{
				$dayString = substr("0" . $dayString, -2);
			}
			$list[$dayString] = $dayString;
		}
		return $list;
	}
	
	public function createYearList($length = 10, $valueLength = 4, $startYear = null)
	{
		$list = array();
		$yearValue = "";
		$yearString = "";
		$startYear = ($startYear == null) ? date('Y') : $startYear;
		for($i = 0; $i < $length; $i++)
		{
			$yearString = $startYear + $i;
			$yearValue = substr("".$yearString,(-1 * $valueLength));
			$list[$yearValue] = $yearString;
		}
		return $list;
	}
	
	function stringStartsWith($haystack, $needle)
	{
		return (($needle === "") || (strpos($haystack,$needle) === 0));
	}
	
	function stringEndsWith($haystack, $needle)
	{
		return (($needle === "") || (substr($haystack,-strlen($needle)) === $needle));
	}
	
	function getFirstDayOfMonth($datetime = null, $monthOffset = 0)
	{
		$date = ($datetime instanceof DateTime) ? $datetime : new DateTime($datetime);
		$date->modify("first day of this month");
		if ($monthOffset > 0)
		{
			$date->modify("+" . $monthOffset . " months");
		}
		return $date;
	}
	
	function getFirstDayOfNextMonth()
	{
		return $this->getFirstDayOfMonth(null, 1);
	}
	
	function getLastDayOfMonth($datetime = null, $monthOffset = 0)
	{
		$date = ($datetime instanceof DateTime) ? $datetime : new DateTime($datetime);
		if ($monthOffset > 0)
		{
			$date->modify("first day of this month");
			$date->modify("+" . $monthOffset . " months");
			$date->modify("last day of this month");
		}
		else
		{
			$date->modify("last day of this month");
		}
		return $date;
	}
	
	function getLastDayOfThisMonth()
	{
		return $this->getLastDayOfMonth(null, 0);
	}

	function sumBinaryIndexes($array = array())
	{
		$sum  = 0;
		if(isset($array))
		{
			if(!is_array($array))
			{
				$array = array($array);
			}
			foreach($array as $key => $value)
			{
				$sum += (1 << ($value - 1));
			}
		}
		return $sum;
	}
	
	function hasBit($value,$index = -1)
	{
		$hasBit = false;
		if($index >= 0)
		{
			$valueBit = (1 << ($index));
			
			if(($value & $valueBit) == $valueBit)
			{
				$hasBit = true;
			}
		}
		return $hasBit;
	}
	
	function uid()
	{
		return dechex(mt_rand()).uniqid().dechex(mt_rand());
	}
	
	function readfile($filename, $chunksize = self::BufferChunkSize)
	{
		$buffer = "";
		
		if ($chunksize == 0)
		{
			readfile($filename);
		}
		else
		{
			$handle = fopen($filename, "rb");
			if ($handle === false)
			{
				return false;
			}
			while (!feof($handle))
			{
				$buffer = fread($handle, $chunksize);
				echo $buffer;
				ob_flush();
				flush();
			}
			fclose($handle);
		}
	}
	
	function setProperty(&$object, $property, $value)
	{
		if ( is_object($object) && property_exists($object, $property) )
		{
			$object->$property = $value;
		}
		else if ( is_array($object) )
		{
			$object[$property] = $value;
		}
		return $object;
	}
}
?>