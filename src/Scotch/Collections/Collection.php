<?
namespace Scotch\Collections;

use Scotch\Collections\ICollection as ICollection;
use Scotch\Exceptions\InvalidArgumentException as InvalidArgumentException;

abstract class Collection implements ICollection
{
	public $count = 0;
	public $items = array();
	
	protected $itemIndex = array();
	abstract protected function idProperty();
	
	public function __construct()
    {
        $this->count = 0;
    }
	
	protected function valueProperty()
	{
		return null;
	}
	
	protected function dataTagProperties()
	{
		$properties = array();
		foreach( get_object_vars($this->items[0]) as $property => $value)
		{
			if ( $property != $idProperty && $property != $valueProperty )
			{
				$properties[$property] = $property;
			}
		}
		return $properties;
	}
	
	public function addItem($data)
	{
		array_push($this->items, $data);
		
		$idProperty = $this->idProperty();
		if ( isset($data->$idProperty) )
		{
			$this->itemIndex[$data->$idProperty] = $this->count;
		}
		
		$this->count++;
	}
	
	public function clear()
	{
		unset($this->items);
		unset($this->itemIndex);
		$this->items = array();
		$this->itemIndex = array();
		$this->count = 0;
	}
	
	public function removeItem($index)
	{
		unset( $this->items[$index] );
		unset( $this->itemIndex[$this->getIdFromIndex($index)] );
		$this->count--;
	}
	
	public function removeItemById($itemID)
	{
		$index = $this->getIndexFromId($itemID);
		unset( $this->items[$index] );
		unset( $this->itemIndex[$itemID] );
		$this->count--;
	}
	
	public function getItem($index)
	{
		return $this->items[$index];
	}
	
	public function getItemById($itemID)
	{
		$item = null;
		if ( array_key_exists($itemID, $this->itemIndex) )
		{
			$item = $this->getItem($this->itemIndex[$itemID]);
		}
		return $item;
	}
	
	public function isEmpty()
	{
		$isEmpty = ( $this->count == 0 ) ? true : false;
		return $isEmpty;
	}
	
	public function toKeyValuePairs($key = null, $value = null)
	{
		$return = array();
		
		$idProperty = $key == null ? $this->idProperty() : $key;
		$valueProperty = $value == null ? $this->valueProperty() : $value;
		
		
		if ($valueProperty == null)
		{
			throw new InvalidArgumentException("Value Property not defined.");
		}
		
		foreach( $this->items as $item )
		{
			if ( is_callable($valueProperty) )
			{
				$return[$item->$idProperty] = $valueProperty($item);
			}
			else
			{
				$return[$item->$idProperty] = $item->$valueProperty;
				
			}
		}
		return $return;
	}
	
	public function toDropDown($key = null, $value = null)
	{
		$return = array();
		$idProperty = $key == null ? $this->idProperty() : $key;
		$valueProperty = $value == null ? $this->valueProperty() : $value;
		
		if ($valueProperty == null)
		{
			throw new InvalidArgumentException("Value Property not defined.");
		}
		
		$properties = $this->dataTagProperties();
		
		foreach( $this->items as $item )
		{
			$value = $item->$idProperty;
			$text = ( is_callable($valueProperty) ) ? $valueProperty($item) : $item->$valueProperty;
			$dataArray = array();
			foreach($properties as $property => $tag)
			{
				$dataArray[$tag] = $item->$property;
			}
			array_push($return, array(
				"value" => $value,
				"text" => $text,
				"data" => $dataArray,
			));
		}
		
		return $return;
	}
	
	public function fill($array = array())
	{
		foreach($array as $item)
		{
			$this->addItem($item);
		}
	}
	
	public function filter($filterProperty, $filterMatchValue)
	{
		$className = get_class($this);
		$filteredCollection = new $className();
		$filterArray = array();
		if ($this->count > 0)
		{
			$filterArray = array_filter($this->items, function($item) use ($filterProperty, $filterMatchValue){
				$filterMatch = false;
				if( isset($item) )
				{
					if ($item->$filterProperty == $filterMatchValue)
					{
						$filterMatch = true;
					}
				}
				return $filterMatch;
			});
			
			$filteredCollection->fill($filterArray);
		}
		
		return $filteredCollection;
	}
	
	private function getIndexFromId($itemID)
	{
		return $this->itemIndex[$itemID];
	}
	
	private function getIdFromIndex($index)
	{
		return array_search($index, $this->itemIndex);
	}
}
?>