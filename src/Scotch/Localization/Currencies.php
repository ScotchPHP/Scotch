<?php
namespace Scotch\Localization;

use Scotch\Localization\Currency as Currency;

class Currencies
{
	private static $currencies = array(
		"AED" => array(
			"currencyID" => "AED",
			"currencyHtml" => "&#1583;&#46;&#1573;",
		),
		"AFN" => array(
			"currencyID" => "AFN",
			"currencyHtml" => "&#1547;",
		),
		"ALL" => array(
			"currencyID" => "ALL",
			"currencyHtml" => "Lek",
		),
		"AMD" => array(
			"currencyID" => "AMD",
			"currencyHtml" => "&#1423;",
		),
		"AOA" => array(
			"currencyID" => "AOA",
			"currencyHtml" => "Kz",
		),
		"AUD" => array(
			"currencyID" => "AUD",
		),
		"AZN" => array(
			"currencyID" => "AZN",
			"currencyHtml" => "&#1084;&#1072;&#1085;",
		),
		"BAM" => array(
			"currencyID" => "BAM",
			"currencyHtml" => "KM",
		),
		"BBD" => array(
			"currencyID" => "BBD",
		),
		"BDT" => array(
			"currencyID" => "BDT",
			"currencyHtml" => "Tk",
		),
		"BGN" => array(
			"currencyID" => "BGN",
			"currencyHtml" => "&#1083;&#1074;",
		),
		"BHD" => array(
			"currencyID" => "BHD",
			"currencyHtml" => "&#46;&#1583;&#46;&#1576;",
		),
		"BIF" => array(
			"currencyID" => "BIF",
			"currencyHtml" => "FBu",
		),
		"BND" => array(
			"currencyID" => "BND",
		),
		"BOB" => array(
			"currencyID" => "BOB",
			"currencyHtml" => "&#36;b",
		),
		"BRL" => array(
			"currencyID" => "BRL",
			"currencyHtml" => "R&#36;",
		),
		"BSD" => array(
			"currencyID" => "BSD",
			"currencyHtml" => "B&#36;",
		),
		"BWP" => array(
			"currencyID" => "BWP",
			"currencyHtml" => "P",
		),
		"BYR" => array(
			"currencyID" => "BYR",
			"currencyHtml" => "p.",
		),
		"BZD" => array(
			"currencyID" => "BZD",
			"currencyHtml" => "BZ&#36;",
		),
		"CAD" => array(
			"currencyID" => "CAD",
		),
		"CDF" => array(
			"currencyID" => "CDF",
			"currencyHtml" => "F",
		),
		"CHF" => array(
			"currencyID" => "CHF",
			"currencyHtml" => "Fr",
		),
		"CLP" => array(
			"currencyID" => "CLP",
		),
		"CNY" => array(
			"currencyID" => "CNY",
			"currencyHtml" => "&#165;",
		),
		"COP" => array(
			"currencyID" => "COP",
		),
		"CRC" => array(
			"currencyID" => "CRC",
			"currencyHtml" => "&#8353;",
		),
		"CUP" => array(
			"currencyID" => "CUP",
			"currencyHtml" => "&#8369;",
		),
		"CZK" => array(
			"currencyID" => "CZK",
			"currencyHtml" => "K&#269;",
		),
		"DKK" => array(
			"currencyID" => "DKK",
			"currencyHtml" => "kr",
		),
		"DOP" => array(
			"currencyID" => "DOP",
			"currencyHtml" => "RD&#36;",
		),
		"EGP" => array(
			"currencyID" => "EGP",
			"currencyHtml" => "&#163;",
		),
		"EUR" => array(
			"currencyID" => "EUR",
			"currencyHtml" => "&#8364;",
		),
		"FJD" => array(
			"currencyID" => "FJD",
		),
		"FKP" => array(
			"currencyID" => "FKP",
			"currencyHtml" => "&#163;",
		),
		"GBP" => array(
			"currencyID" => "GBP",
			"currencyHtml" => "&#163;",
		),
		"GTQ" => array(
			"currencyID" => "GTQ",
			"currencyHtml" => "Q",
		),
		"GYD" => array(
			"currencyID" => "GYD",
		),
		"HKD" => array(
			"currencyID" => "HKD",
		),
		"HNL" => array(
			"currencyID" => "HNL",
			"currencyHtml" => "L",
		),
		"HRK" => array(
			"currencyID" => "HRK",
			"currencyHtml" => "kn",
		),
		"HUF" => array(
			"currencyID" => "HUF",
			"currencyHtml" => "Ft",
		),
		"IDR" => array(
			"currencyID" => "IDR",
			"currencyHtml" => "Rp",
		),
		"ILS" => array(
			"currencyID" => "ILS",
			"currencyHtml" => "&#8362;",
		),
		"INR" => array(
			"currencyID" => "INR",
			"currencyHtml" => "",
		),
		"IRR" => array(
			"currencyID" => "IRR",
			"currencyHtml" => "&#65020;",
		),
		"ISK" => array(
			"currencyID" => "ISK",
			"currencyHtml" => "kr",
		),
		"JMD" => array(
			"currencyID" => "JMD",
			"currencyHtml" => "J&#36;",
		),
		"JPY" => array(
			"currencyID" => "JPY",
			"currencyHtml" => "&#165;",
		),
		"KGS" => array(
			"currencyID" => "KGS",
			"currencyHtml" => "&#1083;&#1074;",
		),
		"KHR" => array(
			"currencyID" => "KHR",
			"currencyHtml" => "&#6107;",
		),
		"KPW" => array(
			"currencyID" => "KPW",
			"currencyHtml" => "&#8361;",
		),
		"KRW" => array(
			"currencyID" => "KRW",
			"currencyHtml" => "&#8361;",
		),
		"KZT" => array(
			"currencyID" => "KZT",
			"currencyHtml" => "&#8376;",
		),
		"LAK" => array(
			"currencyID" => "LAK",
			"currencyHtml" => "&#8365;",
		),
		"LBP" => array(
			"currencyID" => "LBP",
			"currencyHtml" => "&#163;",
		),
		"LKR" => array(
			"currencyID" => "LKR",
			"currencyHtml" => "&#8360;",
		),
		"LRD" => array(
			"currencyID" => "LRD",
		),
		"LTL" => array(
			"currencyID" => "LTL",
			"currencyHtml" => "Lt",
		),
		"LVL" => array(
			"currencyID" => "LVL",
			"currencyHtml" => "Ls",
		),
		"MKD" => array(
			"currencyID" => "MKD",
			"currencyHtml" => "&#1076;&#1077;&#1085;",
		),
		"MMK" => array(
			"currencyID" => "MMK",
			"currencyHtml" => "K",
		),
		"MNT" => array(
			"currencyID" => "MNT",
			"currencyHtml" => "&#8366;",
		),
		"MUR" => array(
			"currencyID" => "MUR",
			"currencyHtml" => "&#8360;",
		),
		"MXN" => array(
			"currencyID" => "MXN",
		),
		"MYR" => array(
			"currencyID" => "MYR",
			"currencyHtml" => "RM",
		),
		"MZN" => array(
			"currencyID" => "MZN",
			"currencyHtml" => "MT",
		),
		"NAD" => array(
			"currencyID" => "NAD",
		),
		"NGN" => array(
			"currencyID" => "NGN",
			"currencyHtml" => "&#8358;",
		),
		"NIO" => array(
			"currencyID" => "NIO",
			"currencyHtml" => "C&#36;",
		),
		"NOK" => array(
			"currencyID" => "NOK",
			"currencyHtml" => "kr",
		),
		"NPR" => array(
			"currencyID" => "NPR",
			"currencyHtml" => "&#8360;",
		),
		"NZD" => array(
			"currencyID" => "NZD",
			"currencyHtml" => "NZ&#36;",
		),
		"OMR" => array(
			"currencyID" => "OMR",
			"currencyHtml" => "&#65020;",
		),
		"PAB" => array(
			"currencyID" => "PAB",
			"currencyHtml" => "B/.",
		),
		"PEN" => array(
			"currencyID" => "PEN",
			"currencyHtml" => "S/.",
		),
		"PHP" => array(
			"currencyID" => "PHP",
			"currencyHtml" => "Php",
		),
		"PKR" => array(
			"currencyID" => "PKR",
			"currencyHtml" => "Rs",
		),
		"PLN" => array(
			"currencyID" => "PLN",
			"currencyHtml" => "z&#322;",
			"decimalPoint" => ",",
			"separator" => ".",
			"symbolPosition" => "append",
		),
		"PYG" => array(
			"currencyID" => "PYG",
			"currencyHtml" => "Gs",
		),
		"QAR" => array(
			"currencyID" => "QAR",
			"currencyHtml" => "&#65020;",
		),
		"RON" => array(
			"currencyID" => "RON",
			"currencyHtml" => "lei",
		),
		"RSD" => array(
			"currencyID" => "RSD",
			"currencyHtml" => "&#044;&#1080;&#1085;.",
		),
		"RUB" => array(
			"currencyID" => "RUB",
			"currencyHtml" => "&#1088;&#1091;&#1073;",
		),
		"SAR" => array(
			"currencyID" => "SAR",
			"currencyHtml" => "&#65020;",
		),
		"SBD" => array(
			"currencyID" => "SBD",
		),
		"SCR" => array(
			"currencyID" => "SCR",
			"currencyHtml" => "&#8360;",
		),
		"SEK" => array(
			"currencyID" => "SEK",
			"currencyHtml" => "kr",
		),
		"SGD" => array(
			"currencyID" => "SGD",
			"currencyHtml" => "S&#36;",
		),
		"SHP" => array(
			"currencyID" => "SHP",
			"currencyHtml" => "&#163;",
		),
		"SOS" => array(
			"currencyID" => "SOS",
			"currencyHtml" => "S",
		),
		"SRD" => array(
			"currencyID" => "SRD",
		),
		"SYP" => array(
			"currencyID" => "SYP",
			"currencyHtml" => "&#163;",
		),
		"THB" => array(
			"currencyID" => "THB",
			"currencyHtml" => "&#3647;",
		),
		"TRY" => array(
			"currencyID" => "TRY",
			"currencyHtml" => "TL",
		),
		"TTD" => array(
			"currencyID" => "TTD",
			"currencyHtml" => "TT&#36;",
		),
		"TWD" => array(
			"currencyID" => "TWD",
			"currencyHtml" => "NT&#36;",
		),
		"TZS" => array(
			"currencyID" => "TZS",
			"currencyHtml" => "x/y",
		),
		"UAH" => array(
			"currencyID" => "UAH",
			"currencyHtml" => "&#8372;",
		),
		"USD" => array(
			"currencyID" => "USD",
		),
		"UYU" => array(
			"currencyID" => "UYU",
			"currencyHtml" => "&#36;U",
		),
		"UZS" => array(
			"currencyID" => "UZS",
			"currencyHtml" => "&#1083;&#1074;",
		),
		"VEF" => array(
			"currencyID" => "VEF",
			"currencyHtml" => "Bs",
		),
		"VND" => array(
			"currencyID" => "VND",
			"currencyHtml" => "&#8363;",
		),
		"XCD" => array(
			"currencyID" => "XCD",
		),
		"YER" => array(
			"currencyID" => "YER",
			"currencyHtml" => "&#65020;",
		),
		"ZAR" => array(
			"currencyID" => "ZAR",
			"currencyHtml" => "R",
		),
	);
	
	public static function getCurrency($currencyID = "USD")
	{
		$currency = null;
		if ( isset(self::$currencies[$currencyID]) )
		{
			$currency = new Currency(self::$currencies[$currencyID]);
		}
		else
		{
			$currency = new Currency(self::$currencies["USD"]);
		}
		return $currency;
	}
	
}
?>