<?
namespace Scotch\Serialize;

class XmlSerializer
{
	public static function serialize($data, $wrapNodeName = "nodes", $defaultNodeName = "node")
	{
		$xml = null;
		if ( is_array($data) )
		{
			$xml = self::generateFromArray($data, $wrapNodeName, $defaultNodeName);
		}
		else
		{
			$xml = self::generateFromObject($data, self::getClassName($data, $wrapNodeName));
		}
		return $xml;
	}
	
    public static function generateFromObject($object, $wrapNodeName = "nodes")
	{
		$objectArray = get_object_vars($object);
        return self::generateFromArray($objectArray, $wrapNodeName);
    }
	
    public static function generateFromArray($array, $wrapNodeName = "nodes", $defaultNodeName = "node")
	{
		$xml = "<" . $wrapNodeName . ">";
		$xml .= self::arrayToXml($array, $defaultNodeName);
		$xml .= "</" . $wrapNodeName . ">";
        return $xml;
    }
	
	public static function wrapInNode($xml, $wrapNodeName = "nodes")
	{
		return "<" . $wrapNodeName . ">" . $xml . "</" . $wrapNodeName . ">";
	}
	
	public static function finalizeDocument($xml, $rootNodeName = null)
	{
		$returnXml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
		if ( isset($rootNodeName) )
		{
			$xml = self::wrapInNode($xml, $rootNodeName);
		}
		return $returnXml . $xml;
	}
	
    private static function arrayToXml($array, $nodeName)
	{
        $xml = '';
        if ( is_array($array) || is_object($array) )
		{
			foreach ($array as $key => $value) {
				if ( is_object($value) )
				{
					$nodeName = self::getClassName($value);
				}
				if ( is_numeric($key) )
				{
					$key = $nodeName;
				}
				if ( !isset($value) )
				{
					$xml .= '<' . $key . '/>';
				}
				else
				{
					$xml .= '<' . $key . '>' . self::arrayToXml($value, $nodeName) . '</' . $key . '>';
				}
			}
        }
		else
		{
			if ( is_numeric($array) )
			{
				$xml = $array;
			}
			else
			{
				$xml = "<![CDATA[" . $array . "]]>";
			}
		}
		return $xml;
    }
	
	private static function getClassName($object, $defaultClassName = "node")
	{
		$className = $defaultClassName;
		if ( is_object($object) )
		{
			$className = get_class($object);
			$pos = strrpos($className, "\\");
			if ( $pos )
			{
				$className = substr($className, $pos + 1);
			}
			$className = lcfirst($className);
		}
		return $className;
	}
}
?>