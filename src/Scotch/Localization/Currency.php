<?php
namespace Scotch\Localization;

use Scotch\Models\BaseModel as BaseModel;

class Currency extends BaseModel
{
	public $currencyID;
	public $currencyHtml = "&#36;";
	public $decimalPoint = ".";
	public $separator = ",";
	public $symbolPosition = "prepend";
}
?>