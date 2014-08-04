<?php
namespace Scotch\Data;

class SqlParameter
{
	public $name;
	public $direction;
	public $type;
	public $size;
	public $precision;
	public $scale;
	public $value;
	
	function __construct($array = array())
	{
		if (count($array) > 0)
		{
			foreach($array as $key => $val)
			{
				$this->$key = $val;
			}		
		}
	}
	
	public function createAsArray()
	{
		$array = array();		
		array_push($array, $this->value);
		
		
		if(isset($this->direction))
		{
			array_push($array, $this->direction);
		}
		
		$val = $this->value;
		$typeInfo = $this->getSqlType();
		
		$type = $typeInfo[0];
		$phpType = $typeInfo[1];
		
		if(isset($this->type))
		{
			if ($this->type == SqlParameterTypes::SqlNVarChar)
			{
				//array_push($array, SQLSRV_PHPTYPE_STRING("UTF-8"));
				if (!isset($val))
				{
					$val = null;
				}
			}
			
			array_push($array, $phpType);
			array_push($array, $type);
		}
		
		return $array;
	}
	
	protected function getSqlType()
	{
		$sqlType = null;
		$phpType = null;
		switch($this->type)
		{
			case SqlParameterTypes::SqlInt:
				$sqlType = SQLSRV_SQLTYPE_INT;
				$phpType = SQLSRV_PHPTYPE_INT;
				break;
			case SqlParameterTypes::SqlBigInt:
				$sqlType = SQLSRV_SQLTYPE_BIGINT;
				$phpType = SQLSRV_PHPTYPE_INT;
				break;
			case SqlParameterTypes::SqlBit:
				$sqlType = SQLSRV_SQLTYPE_BIT;
				$phpType = SQLSRV_PHPTYPE_INT; 
				break;
			case SqlParameterTypes::SqlFloat:
				$sqlType = SQLSRV_SQLTYPE_FLOAT;
				$phpType = SQLSRV_PHPTYPE_FLOAT;
				break;
			case SqlParameterTypes::SqlDecimal:
				$sqlType = SQLSRV_SQLTYPE_DECIMAL($this->precision, $this->scale);
				$phpType = SQLSRV_PHPTYPE_FLOAT;
				break;
			case SqlParameterTypes::SqlNVarChar:
				$sqlType = SQLSRV_SQLTYPE_NVARCHAR($this->size);
				$phpType = SQLSRV_PHPTYPE_STRING("UTF-8");
				break;
			case SqlParameterTypes::SqlNVarCharMax:
				$sqlType = SQLSRV_SQLTYPE_NVARCHAR('max');
				$phpType = SQLSRV_PHPTYPE_STRING("UTF-8");
				break;
			case SqlParameterTypes::SqlXml:
				$sqlType = SQLSRV_SQLTYPE_XML;
				$phpType = SQLSRV_PHPTYPE_STREAM(SQLSRV_ENC_BINARY);
				break;
			case SqlParameterTypes::SqlUniqueIdentifier:
				$sqlType = SQLSRV_SQLTYPE_UNIQUEIDENTIFIER;
				$phpType = SQLSRV_PHPTYPE_STRING("UTF-8");
				break;
			case SqlParameterTypes::SqlTinyInt:
				$sqlType = SQLSRV_SQLTYPE_TINYINT;
				$phpType = SQLSRV_PHPTYPE_INT;
				break;
			case SqlParameterTypes::SqlDate:
				$sqlType = SQLSRV_SQLTYPE_DATE;
				$phpType = SQLSRV_PHPTYPE_DATETIME;
				break;
			case SqlParameterTypes::SqlDateTime:
				$sqlType = SQLSRV_SQLTYPE_DATETIME;
				$phpType = SQLSRV_PHPTYPE_DATETIME;
				break;
			case SqlParameterTypes::SqlTime:
				$sqlType = SQLSRV_SQLTYPE_TIME;
				$phpType = SQLSRV_PHPTYPE_DATETIME;
				break;
			case SqlParameterTypes::SqlTimestamp:
				$sqlType = SQLSRV_SQLTYPE_TIMESTAMP;
				$phpType = SQLSRV_PHPTYPE_DATETIME;
				break;
		}
		return array($sqlType,$phpType);
	}
}
?>