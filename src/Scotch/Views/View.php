<?php
namespace Scotch\Views;

use Scotch\Templates\ITemplate as ITemplate; 
use Scotch\Exceptions\ClassNotFoundException as ClassNotFoundException;

class View implements ITemplate
{
	public $controller;
	public $data;
	public $template;
	public $file;
	public $title;
	public $metaTitle;
    public $metaDescription;
    public $metaKeywords;
	public $css;
	public $scripts;
    public $canonical;
    public $relPrev;
    public $relNext;
	public $router;
	public $settings;
	
	function __construct($properties = array())
	{
		if(count($properties)>0)
		{
			foreach($properties as $key => $value)
			{
				$this->$key = $value;
			}
		}
	}
	
	function render()
	{
		$templateClass = null;
	
		if(isset($this->template))
		{
			$templateClass = $this->template;
		}
		else if(isset($this->controller))
		{
			$templateClass = $this->controller->template;
		}
		
		if(isset($templateClass))
		{
			if(class_exists($templateClass))
			{
				$template = new $templateClass($this);
				$template->render();
			}
			else
			{
				throw new ClassNotFoundException("Template '" . $this->template . "' not found.");
			}
		}
		else
		{
			if(isset($this->file))
			{
				if(file_exists($this->file))
				{
					include $this->file;
				}
			}
		}
	}
}
?>