<?php 
namespace Scotch;

use Scotch\Data\SqlSessionPool as SqlSessionPool;
use Scotch\IConfiguration as IConfiguration;

class Configuration implements IConfiguration
{	
	const SERVER_CONNECTION_PART = "server";
	const CONNECTION_INFO_CONNECTION_PART = "connectionInfo";
	
	public $connectionInfo;
	public $logger;
	public $mode;
	public $libraryRoot;
	public $routingTable;
	public $httpsEnabled = true;
	public $settings;
	public $languages;
	public $cacheProvider;
	public $services;
	
	function __construct($configuration)
	{
		
		$this->readConfiguration($configuration);
	}
	
	function readConfiguration($configuration)
	{
		if(count($configuration)>0)
		{
		
			foreach($configuration as $key => $value)
			{
				$this->$key = $value;
			}
		}
	}
	
	public function getConnectionInfo($connectionName)
	{
		return $this->connectionInfo[$connectionName];
	}
	
	public function getSqlSession($connectionName){
		$connectionInformation = $this->getConnectionInfo($connectionName);
		$sqlSession = SqlSessionPool::getInstance()->getSqlSession($connectionInformation[self::SERVER_CONNECTION_PART],$connectionInformation[self::CONNECTION_INFO_CONNECTION_PART]);
		return $sqlSession;
	}
	
	
}
?>