<?php
namespace Scotch\ECommerce;

use Scotch\Models\BaseModel as BaseModel;
use Scotch\Utilities\WebUtilities as WebUtilities;

class PaymentTransaction extends BaseModel
{
/* payment properties */
	public $paymentMethod;
	public $invoiceNumber;
	public $description;
	public $amount;
	public $currencyCode;
	public $transactionID;

/* payee properties */
	public $customerID;
	public $firstName;
	public $lastName;
	public $email;
	public $company;
	public $phone;
	public $fax;
	
/* shipping properties */
	public $shiptToCountry;
	public $shiptToFirstName;
	public $shiptToLastName;
	public $shiptToCompany;
	public $shiptToAddress;
	public $shiptToCity;
	public $shiptToState;
	public $shiptToZip;

/* constructor */
	function __construct($paymentMethod, $properties = array())
	{
		parent::__construct($properties);
		$this->paymentMethod = $paymentMethod;
	}

/* methods */
	public function validate(&$errors = array())
	{
		return $this->paymentMethod->validate($errors);
	}
	
	function processCharge($paymentProvider)
	{
		return $this->paymentMethod->processCharge($paymentProvider, $this);
	}
	
	public function processAuthorization($paymentProvider)
	{
		return $this->paymentMethod->processAuthorization($paymentProvider, $this);
	}
	
	public function processVoid($paymentProvider)
	{
		return $this->paymentMethod->processVoid($paymentProvider, $this);
	}
	
	public function processCredit($paymentProvider)
	{
		return $this->paymentMethod->processCredit($paymentProvider, $this);
	}
	
}
?>