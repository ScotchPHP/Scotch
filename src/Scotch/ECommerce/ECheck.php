<?php
namespace Scotch\ECommerce;

use Scotch\ECommerce\PaymentTypes as PaymentTypes;
use Scotch\Utilities\WebUtilities as WebUtilities;
use Scotch\ECommerce\PaymentMethod as PaymentMethod;
use Scotch\Exceptions\NotImplementedException as NotImplementedException;

class ECheck extends PaymentMethod
{
/* properties */
	public $nameOnAccount;
	public $bankName;
	public $bankAccountType;
	public $routingNumber;
	public $accountNumber;
	public $accountNumberConfirm;

	protected $firstName;
	protected $lastName;
	
/* constructor */
	function __construct($properties = null)
	{
		parent::__construct($properties);
		
		$this->paymentType = PaymentTypes::ECheck;
		$this->accountNumber = $this->cleanseAccountNumber($this->accountNumber);
		$this->accountNumberConfirm = $this->cleanseAccountNumber($this->accountNumberConfirm);
		$this->routingNumber = $this->cleanseRoutingNumber($this->routingNumber);
	}

/* public methods */
	public function validate(&$errors = array())
	{
		$util = WebUtilities::getInstance();
		
		//nameOnAccount
		if (!$util->hasValue($this->nameOnAccount)) 
		{
			$errors["nameOnAccount_Error"] = $util->getString("errorNameOnAccountRequired");
		}
		
		//bankName
		if (!$util->hasValue($this->bankName))
		{
			$errors["bankName_Error"] = $util->getString("errorBankNameRequired");
		}
		
		//bankAccountType
		if (!$util->hasValue($this->bankAccountType))
		{
			$errors["bankAccountTypeID_Error"] = $util->getString("errorBankAccountTypeRequired");
		}
		
		//routingNumber
		$routingNumberError = $this->validateRoutingNumber($this->routingNumber, false);
		if ($util->hasValue($routingNumberError))
		{
			$errors["routingNumber_Error"] = $routingNumberError;
		}
		
		//accountNumber
		$accountNumberError = $this->validateAccountNumber($this->accountNumber, false);
		if ($util->hasValue($accountNumberError))
		{
			$errors["accountNumber_Error"] = $accountNumberError;
		}
		
		//accountNumberConfirm
		$accountNumberConfirmError = $this->validateAccountNumber($this->accountNumberConfirm, true);
		if ($util->hasValue($accountNumberConfirmError))
		{
			$errors["accountNumberConfirm_Error"] = $accountNumberConfirmError;
		}
		
		//accountNumber & accountNumberConfirm match
		if($accountNumberError == null and $accountNumberConfirmError == null and $this->accountNumber != $this->accountNumberConfirm)
		{
			$errors["accountNumberConfirm_Error"] = $util->getString("errorAccountNumbersDoNotMatch");
		}
		
		return (count($errors) == 0);
	}
	
	public function getAccountName()
	{
		return $this->nameOnAccount;
	}
	
	public function getAccountType()
	{
		return $this->bankAccountType;
	}
	
	public function getAccountNumber()
	{
		return $this->accountNumber;
	}
	
	public function processCharge($paymentProvider, $paymentTransaction)
	{
		return $paymentProvider->chargeACH($paymentTransaction);
	}
	
	public function processAuthorization($paymentProvider, $paymentTransaction)
	{
		throw new NotImplementedException("Method processAuthorization not provided for ACH payment methods.");
	}
	
	public function processVoid($paymentProvider, $paymentTransaction)
	{
		throw new NotImplementedException("Method processVoid not provided for ACH payment methods.");
	}
	
	public function processCredit($paymentProvider, $paymentTransaction)
	{
		throw new NotImplementedException("Method processCredit not provided for ACH payment methods.");
	}
		
	public function cleanseAccountNumber($accountNumber)
	{
		return str_replace("-","",str_replace(" ","",$accountNumber));
	}
	public function cleanseRoutingNumber($routingNumber)
	{
		return str_replace("-","",str_replace(" ","",$routingNumber));
	}
	
	public function isValidAccountNumber($accountNumber)
	{
		return ($this->validateAccountNumber($accountNumber) == null) ? true : false;
	}
	
	public function isValidRoutingNumber($routingNumber)
	{
		return ($this->validateRoutingNumber($routingNumber) == null) ? true : false;
	}

/* private methods */
	private function validateAccountNumber($accountNumber, $isConfirm = false)
	{
		$util = WebUtilities::getInstance();
		$error = null;
		
		if ( !$util->hasValue($accountNumber) )
		{
			$error = (!$isConfirm) ? $util->getString("errorAccountNumberRequired") : $util->getString("errorAccountNumberConfirmRequired");
		}
		else if( !preg_match("/^[a-z,A-Z,0-9]{1,34}$/", $accountNumber) )
		{
			$error = (!$isConfirm) ? $util->getString("errorAccountNumberInvalid") : $util->getString("errorAccountNumberConfirmInvalid");
		}
		
		return $error;
	}
	
	private function validateRoutingNumber($routingNumber)
	{
		$util = WebUtilities::getInstance();
		$error = null;
		
		if ( !$util->hasValue($routingNumber) )
		{
			$error = $util->getString("errorRoutingNumberRequired");
		}
		else if( !preg_match("/^[0-9]{9}$/", $routingNumber) )
		{
			$error = $util->getString("errorRoutingNumberInvalid");
		}
		
		return $error;
	}
	
	public function getFirstName()
	{
		if(!isset($this->firstName))
		{
			$this->parseNameOnAccount();
		}
		return $this->firstName;
	}
	
	public function getLastName()
	{
		if(!isset($this->lastName))
		{
			$this->parseNameOnAccount();
		}
		return $this->lastName;
	}
	
	private function parseNameOnAccount()
	{
		$nameParts = explode(" ", $this->nameOnAccount, 2);
		
		if ( count($nameParts) >= 2 )
		{
			$this->firstName = $nameParts[0];
			$this->lastName = $nameParts[1];
		}
		else
		{
			$this->firstName = $this->nameOnAccount;
			$this->lastName = $this->nameOnAccount;
		}
	}
	
	protected function getTypeMap()
	{
		return array(
			"paymentTypeID" => DataTypes::Int,
			"doSave" => DataTypes::Bit
		);
	}
}
?>