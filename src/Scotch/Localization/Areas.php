<?php
namespace Scotch\Localization;

use Scotch\Localization\Area as Area;

class Areas
{
	const SquareFeet = "F";
	const Meters = "M";
	
	private static $areas = array(
		self::SquareFeet => array(
			"areaID" => "F",
			"areaCode" => "areaSquareFeet",
			"conversionRate" => 0.09290304,
			"isBase" => false,
		),
		self::Meters => array(
			"areaID" => "M",
			"areaCode" => "areaMeters",
			"conversionRate" => 1.00000000,
			"isBase" => true,
		),
	);
	
	public static function getArea($areaID = self::Meters)
	{
		$area = null;
		if ( isset(self::$areas[$areaID]) )
		{
			$area = new Area(self::$areas[$areaID]);
		}
		else
		{
			$area = new Area(self::$areas[self::Meters]);
		}
		return $area;
	}
	
}
?>