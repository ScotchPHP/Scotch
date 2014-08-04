<?
namespace Scotch\Collections;

interface ICollection
{
	function addItem($data);
	function getItem($index);
	function removeItem($index);
	function getItemById($index);
	function isEmpty();
	function toKeyValuePairs($key,$value);
}
?>