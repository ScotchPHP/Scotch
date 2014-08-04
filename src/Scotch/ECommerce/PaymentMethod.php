<?php
namespace Scotch\ECommerce;

use Scotch\Models\BaseModel as BaseModel;

abstract class PaymentMethod extends BaseModel
{
	public $paymentType;
	
	abstract public function validate(&$errors = array());
	
	abstract public function getAccountName();
	
	abstract public function getAccountType();
	
	abstract public function getAccountNumber();
	
	abstract public function processCharge($paymentProvider, $paymentTransaction);
	
	abstract public function processAuthorization($paymentProvider, $paymentTransaction);
	
	abstract public function processVoid($paymentProvider, $paymentTransaction);
	
	abstract public function processCredit($paymentProvider, $paymentTransaction);
}
?>