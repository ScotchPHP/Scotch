<?php
namespace Scotch\Localization;

use Scotch\Models\BaseModel as BaseModel;

class Area extends BaseModel
{
	public $areaID;
	public $areaCode;
	public $conversionRate;
	public $isBase;
	
	protected function getTypeMap()
	{
		return array(
			"conversionRate" => "float",
			"isBase" => "bit",
		);
	}
}
?>