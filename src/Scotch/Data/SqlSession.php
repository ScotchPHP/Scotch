<?php 
namespace Scotch\Data;

use Scotch\Data\SqlParameter as SqlParameter;
use Scotch\Data\SqlRecordSet as SqlRecordSet;
use Scotch\Exceptions\SqlException as SqlException;

/**
* Create a Sql Session (connection the the database)
* 
* <code>
*	$sqlSession = new SqlSession(Config::$PocSqlServerName,Config::$PocSqlSessionInfo);
*	$rs = $sqlSession->executeStoredProcedure("dbo.getUsers", 
*		array(
*			new SqlParameter(array("name"=>"@userID","direction"=>SqlParameterDirections::In,"type"=>SqlParameterTypes::SqlInt,"value"=>null)),
*			new SqlParameter(array("direction"=>SqlParameterDirections::Out,"type"=>SqlParameterTypes::SqlNVarChar,"size"=>100,"value"=>$userID_Error)),
*			new SqlParameter(array("direction"=>SqlParameterDirections::Out,"value"=>$masterError))
*		)
*	);
* </code>
*/
class SqlSession
{
	public $connectionInfo;
	public $serverName;
	public $connection;
	public $statement;
	
	function __construct($serverName, $connectionInfo = array()) 
	{	
		$this->serverName = $serverName;	
		$this->connection = sqlsrv_connect($serverName, $connectionInfo);
	}
	
	/**
	* Execute a SQL Stored Procedure
	*
	* @param string $procedureName Name of the procedure to execute
	* @param array $parameters An array of SqlParameter objects
	* @return SqlRecordSet
	*
	* <code>
	*	$rs = $sqlSession->executeStoredProcedure("dbo.getUsers", 
	*		array(
	*			new SqlParameter(array("name"=>"@userID","direction"=>SqlParameterDirections::In,"type"=>SqlParameterTypes::SqlInt,"value"=>null)),
	*			new SqlParameter(array("direction"=>SqlParameterDirections::Out,"type"=>SqlParameterTypes::SqlNVarChar,"size"=>100,"value"=>$userID_Error)),
	*			new SqlParameter(array("direction"=>SqlParameterDirections::Out,"value"=>$masterError))
	*		)
	*	);
	* </code>
	*/
	public function executeStoredProcedure($procedureName, $parameters)
	{
		$procedureParameters = array();
		$tsql = "{call $procedureName(";
		if (count($parameters) > 0) 
		{
			foreach ($parameters as $parameter)
			{
				$tsql .= "?,";
				array_push($procedureParameters, $parameter->createAsArray());
			}
			$tsql = substr($tsql, 0, strlen($tsql) - 1);
		}
		$tsql .= ")}";
		$statement = sqlsrv_query($this->connection, $tsql, $procedureParameters);
		
		$recordSet = null;
		if($statement)
		{
			$recordSet = new SqlRecordSet($statement);
			$recordSet->getResults();
		}else
		{
			throw new SqlException("Procedure '$procedureName' failed with Errors: " . print_r(sqlsrv_errors(), true));
		}
		return $recordSet;
	}
	
	function __destruct() 
	{
		// Dispose or close connection if needed
		sqlsrv_close($this->connection);
	}
}
?>