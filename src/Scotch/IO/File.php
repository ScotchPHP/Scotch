<?php
namespace Scotch\IO;

use Scotch\Models\BaseModel as BaseModel;

class File extends BaseModel
{
	public $name;
	public $type;			//	image/jpeg
	public $extension;		//	jpg
	public $path;
	public $size;
}
?>