<?php
namespace Scotch\Serialize;

use Scotch\Serialize\ISerializable as ISerializable;

abstract class JsonSerializable implements ISerializable
{
	function serialize()
	{
		return json_encode($this);
	}
}
?>