<?php
namespace Scotch\Data;

use Scotch\Data\SqlSession as SqlSession;

class SqlSessionPool 
{
	private $sessions = array();
	
	private static $singletonInstance;
	
	private function __construct()
	{
	}
	
	public static function getInstance()
	{
		if(!self::$singletonInstance)
		{
			self::$singletonInstance = new SqlSessionPool();
		}
		
		return self::$singletonInstance;
	}
	
	public function getSqlSession($server,$info)
	{ 
		$key = $server . "__" . $info["Database"] . "__" . $info["UID"];
		$session = null;
		if(isset($this->sessions[$key]))
		{
			$session = $this->sessions[$key];
		}
		else
		{
			$session = new SqlSession($server,$info);
			$this->sessions[$key] = $session;
		}
		
		return $session;
	}
}
?>