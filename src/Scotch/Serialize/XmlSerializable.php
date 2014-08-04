<?php
namespace Scotch\Serialize;

use Scotch\Serialize\ISerializable as ISerializable;
use Scotch\Serialize\XmlSerializer as XmlSerializer;

abstract class XmlSerializable implements ISerializable
{
	function serialize()
	{
		return XmlSerializer::serialize($this);
	}
}
?>