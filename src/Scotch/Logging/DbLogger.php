<?php
namespace Scotch\Logging;

use Scotch\Logging\Logger as Logger;

abstract class DbLogger extends Logger
{
	protected $sqlSession;
	
	public function __construct($sqlSession)
	{
		$this->sqlSession = $sqlSession;
		$this->state = $state;
	}

	abstract protected function log($msg);
}
?>