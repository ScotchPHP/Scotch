<?php 
namespace Scotch\Repositories;

use Scotch\Utilities\Utilities as Utilities;
use	Scotch\Data\SqlParameterDirections as SqlParameterDirections;

/**
* Base repository object.  Provides a few basic methods for repositories.
*
*/
abstract class SqlRepository
{
	protected $sqlSession;
	
	function __construct()
	{
		$this->sqlSession = $this->getSqlSession();
	}

	
	protected abstract function getSqlSession();
	
	/**
	* Parameter to extract a value from a specified array and key and provides
	* cleansing ability and the ability to grab missing keys and set those to null.
	*
	* @param array $parameters the array to look up the key in
	* @param string $key the key to lookup in the specified array
	* @return mixed the value stored at the specified key in the specified array.  If the key is not found then null is returned.
	*/
	public function getParameterValue($parameters, $key)
	{
		return Utilities::getInstance()->getValue($parameters, $key);
	}
	
	/**
	* Extracts the output parameters from a list of Kohva\Scotch\Data\SqlParameter objects into a list.
	*
	* @param array $dataArray the array to store the list of output parameters into
	* @param array $parameters the array of parameters to search for output parameters in.
	*/
	public function setOutputParameters(&$dataArray, $parameters)
	{
		foreach($parameters as $parameter)
		{
			if($parameter->direction != SqlParameterDirections::In)
			{
				$dataArray[$parameter->name] = $parameter->value;
			}
		}
	}
}
?>