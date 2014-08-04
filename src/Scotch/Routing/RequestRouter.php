<?
namespace Scotch\Routing;

use Scotch\Routing\IRouter as IRouter;
use Scotch\ServerVariables as ServerVariables;
use Scotch\Protocols as Protocols;
use Scotch\Utilities\WebUtilities as WebUtilities;

/**
* Abstract Request Router class.  This class provides the basic break down for url routing.  The class provides a basic
* url breakdown and provides them as properties.
*/
abstract class RequestRouter implements IRouter
{
	public $protocol;
	public $hostName;
	public $url;
	public $pageName;
	public $queryString;
	public $requestMethod;
	public $referer;
	public $controller;
	
	protected $webUtil;
	
	/**
	* On construction the url is parsed into pieces.
	*/
	function __construct()
	{
		$util = WebUtilities::getInstance();
		
		$this->protocol = strpos(strtolower($util->getValue($_SERVER,ServerVariables::PROTOCOL)),Protocols::HTTPS) === FALSE ? Protocols::HTTP : Protocols::HTTPS;
		$this->hostName = $util->getValue($_SERVER, ServerVariables::HTTP_HOST);
		$this->pageName = $util->getValue($_SERVER, ServerVariables::SCRIPT_NAME);
		$this->queryString = $util->getValue($_SERVER, ServerVariables::QUERY_STRING);
		$this->url = $util->getValue($_SERVER, ServerVariables::REQUEST_URI);
		$this->requestMethod = $util->getValue($_SERVER, ServerVariables::REQUEST_METHOD);
		$this->referer = $util->getValue($_SERVER, ServerVariables::HTTP_REFERER);
		$this->webUtil = WebUtilities::getInstance();
	}
	
	/**
	* Route the request
	*/
	abstract function route();
}
?>