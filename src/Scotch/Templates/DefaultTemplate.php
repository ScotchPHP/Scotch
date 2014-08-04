<?php
namespace Scotch\Templates;

use Scotch\Templates\Template as Template;

class DefaultTemplate extends Template
{
	
	function __construct(&$view)
	{
		parent::__construct($view);
	}

	function render()
	{
		$data = $this->view->data;
?>
<html>
	<body>
		<? include $this->view->file ?>
	</body>
</html>
<?
	}
}
?>