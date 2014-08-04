<?php
namespace Scotch\Data;

class SqlRecordSet
{
	private $currentResult;
	private $resultIndex = 0;
	private $currentRow;
	private $rowIndex = 0;
	public $statement;
	public $results;
	
	function __construct($sqlStatement)
	{
		$this->statement = $sqlStatement;
		$this->results = null;
	}
	/**
	* Check if there is another result in the record set and increments the result if there
	* is another result.
	*
	*@return boolean returns true if there is another record set and false otherwise
	*/
	public function hasNextResult()
	{
		if(isset($this->currentResult))
		{
			if(++$this->resultIndex < count($this->results))
			{
				$this->currentResult = $this->results[$this->resultIndex];
			}
			else
			{
				$this->currentResult = null;
			}
		}
		else
		{
			$this->currentResult = null;
			if($this->resultIndex < count($this->results))
			{
				$this->currentResult = $this->results[0];
			}
		}
		$this->rowIndex = 0;
		$hasResult = false;
		if(isset($this->currentResult))
		{
			$hasResult = true;
		}
		return $hasResult;
	}
	
	/**
	* Retrieves the next row in the current result.
	*
	* @return array returns the array of values for the current row of the result.
	*/
	public function getNextRow()
	{
		$this->checkForResult();
		
		if(isset($this->currentRow))
		{
			if(++$this->rowIndex < count($this->currentResult))
			{
				$this->currentRow = $this->currentResult[$this->rowIndex];
			}
			else
			{
				$this->currentRow = null;
			}
		}
		else
		{
			$this->currentRow = null;
			if($this->rowIndex < count($this->currentResult))
			{
				$this->currentRow = $this->currentResult[0];
			}
		}
		return $this->currentRow;
	}
	
	/**
	* Retrieves the row at the specified index.
	*
	* @return array returns the array of values for the specified row in the result.
	*/
	public function fetchRow($rowIndex)
	{
		$this->checkForResult();
		return $this->currentResult[$rowIndex];
	}
	
	/**
	* Retrieves the row at the specified index.
	*
	* @return int returns the number of rows in the result.
	*/
	public function getRowCount()
	{
		$this->checkForResult();
		return count($this->currentResult);
	}
	
	public function isEmpty()
	{
		return ($this->getRowCount()==0);
	}
	
	public function filterResult($filterFunction)
	{
		$this->checkForResult();
		
		$r =  array_filter($this->currentResult, $filterFunction);
		
		return $r;
	}
	
	/* loop through list of results (multiple select statements returned from single query)  */
	public function getResults()
	{
		if(!isset($this->results))
		{
			$this->results = array();
			$this->addResult();
			while($i = sqlsrv_next_result($this->statement))
			{
				$this->addResult();
			}
			
			sqlsrv_free_stmt($this->statement);
		}
	}
	
	protected function checkForResult()
	{
		if(!isset($this->currentResult))
		{
			$this->hasNextResult();
		}
	}
	
	/* gets all records from one result into an array */
	protected function addResult()
	{
		$result = array();
		while($row = sqlsrv_fetch_array($this->statement, SQLSRV_FETCH_ASSOC))
		{
			array_push($result, $row);
		}
		array_push($this->results,$result);
	}
	
}
?>