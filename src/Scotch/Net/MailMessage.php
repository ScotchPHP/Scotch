<?php
namespace Scotch\Net;

use Scotch\Models\BaseModel as BaseModel;

class MailMessage extends BaseModel
{
	protected $to = array();
	protected $cc = array();
	protected $bcc = array();
	protected $attachments = array();
	
	public $from;
	public $subject;
	public $message;
	
	function __construct($parameters = array())
	{
		parent::__construct($parameters);
	}
	
	public function addTo($emailAddress)
	{
		$this->addEmailAddress($this->to,$emailAddress);
	}
	
	public function addCC($emailAddress)
	{
	$this->addEmailAddress($this->cc,$emailAddress);
	}
	
	public function addBcc($emailAddress)
	{
		$this->addEmailAddress($this->bcc,$emailAddress);
	}
	
	
	private function addEmailAddress($list, $emailAddress)
	{
		if(is_array($emailAddress))
		{
			array_merge($list, $emailAddress);
		}
		else
		{
			array_push($list, $emailAddress);
		}
	}
	
	private function generateEmailStringList($list,$glue = ",")
	{
		return implode($glue, $list);
	}
	
	public function send()
	{
		$toString = $this->generateEmailStringList($this->to);
		$ccString = $this->generateEmailStringList($this->cc);
		$bccString = $this->generateEmailStringList($this->bcc);
		$headers = "From: " . $from . "\r\n"; 
		
		
		return mail($toString,$subject,$message,$headers);
	}
}
?>