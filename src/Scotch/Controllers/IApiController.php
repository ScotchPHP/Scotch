<?
namespace Scotch\Controllers;

/**
* Api Controller Interface.
*/
interface IApiController
{
	// read
	function get();
	
	//create
	function post();
	
	//update
	function put();
	
	//delete
	function delete();
}
?>