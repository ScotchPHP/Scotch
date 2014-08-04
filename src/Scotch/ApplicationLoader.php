<?
namespace Scotch;

use Scotch\Exceptions\MethodNotFoundException as MethodNotFoundException;
use Scotch\Exceptions\ClassNotFoundException as ClassNotFoundException;
use Scotch\Exceptions\MissingConfigurationException as MissingConfigurationException;

class ApplicationLoader
{
	const MAIN_METHOD = "main";
	
	public $application;
	public $libraries;
	
	private static $singletonInstance;
	
	private function __construct()
	{
	}
	
	public static function getInstance()
	{
		if(!self::$singletonInstance)
		{
			self::$singletonInstance = new ApplicationLoader();
		}
		
		return self::$singletonInstance;
	}
	
	function autoload($class)
	{
		$library = null;
		$libraryPath = null;
		
		$parts = explode("\\", $class);
		
		if(count($parts) > 0){
			$library = $parts[0];
		}
		
		$libraryPath = $this->libraries[$library];
		
		$path = $libraryPath."\\".$class.".php";
		
		if(file_exists($path))
		{
			include($path);
		}
	}
	
	public function run($applicationClassName = null, $configuration = array())
	{
		if(isset($configuration["libraries"]))
		{
			$this->libraries = $configuration["libraries"];		
		}
		else
		{
			throw new MissingConfigurationException("Missing libraries configuration");
		}
		
		spl_autoload_register(array($this,"autoload"));	
		
		if(!isset($applicationClassName))
		{
			throw new ClassNotFoundException("Application not found.");
		}
		else
		{
			$this->autoload($applicationClassName);
			
			if(class_exists($applicationClassName))
			{
				
				$this->application = new $applicationClassName($configuration);
				
				$m = ApplicationLoader::MAIN_METHOD;
			
				if(method_exists($this->application, $m) == true)
				{
					$output = $this->application->$m();
				}
				else
				{
					throw new MethodNotFoundException("Class '" . $applicationClassName . "' does not contain method '$m.'");
				}
			}
			else
			{
				throw new ClassNotFoundException("Class '" . $applicationClassName . "' could not be found.");
			}
		}
	}
	
	
}
?>