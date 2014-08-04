<?
namespace Scotch\Collections;

use Scotch\Collections\Collection as Collection;

abstract class PagedCollection extends Collection
{
	public $page;			//current page
	public $pageSize;		//# of records per page
	public $maxPages = 10;	//max # of pages to display at one time
	public $maxRows;		//total # of records in un-paged results
	
	public function isMultiPage()
	{
		$isMultiPage = null;
		
		if( isset($this->pageSize) && isset($this->maxRows) && is_numeric($this->pageSize) && is_numeric($this->maxRows) )
		{
			$isMultiPage = ($this->maxRows > $this->pageSize) ? true : false;
		}
		
		return $isMultiPage;
	}
}
?>