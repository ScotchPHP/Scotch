<?php
namespace Scotch\ECommerce;

use DateTime as DateTime;
use Scotch\DataTypes as DataTypes;
use Scotch\ECommerce\PaymentTypes as PaymentTypes;
use Scotch\ECommerce\CreditCardTypes as CreditCardTypes;
use Scotch\Utilities\WebUtilities as WebUtilities;
use Scotch\ECommerce\PaymentMethod as PaymentMethod;

class CreditCard extends PaymentMethod
{
/* public properties */
	public $nameOnCard;
	public $creditCardNumber;
	public $creditCardExpirationMonth;
	public $creditCardExpirationYear;
	public $creditCardCode;
	
	public $address;
	public $city;
	public $stateID;
	public $postalCode;

/* private properties */
	private $firstName;
	private $lastName;

/* constructor */
	function __construct($properties = array())
	{
		parent::__construct($properties);
		
		$this->paymentType = PaymentTypes::CreditCard;
		$this->creditCardNumber = $this->cleanseCreditCardNumber($this->creditCardNumber);
	}

/* public methods */
	public function validate(&$errors = array())
	{
		$util = WebUtilities::getInstance();
		
		//nameOnCard
		if (!$util->hasValue($this->nameOnCard))
		{
			$errors["creditCardName_Error"] = $util->getString("errorNameOnCardRequired");
		}
		
		//creditCardNumber
		$creditCardNumberError = $this->validateCreditCardNumber($this->creditCardNumber);
		if ($util->hasValue($creditCardNumberError))
		{
			$errors["creditCardNumber_Error"] = $creditCardNumberError;
		}
		
		//creditCardExpiration
		$creditCardExpirationError = $this->validateExpirationDate($this->creditCardExpirationMonth, $this->creditCardExpirationYear);
		if ($util->hasValue($creditCardExpirationError))
		{
			$errors["creditCardExpiration_Error"] = $creditCardExpirationError;
		}
		
		//creditCardCode
		$creditCardCodeError = $this->validateCardCode($this->creditCardCode);
		if ($util->hasValue($creditCardCodeError))
		{
			$errors["creditCardCode_Error"] = $creditCardCodeError;
		}
		
		return (count($errors) == 0);
	}
	
	public function getAccountName()
	{
		return $this->nameOnCard;
	}
	
	public function getAccountType()
	{
		return $this->getTypeFromNumber($this->creditCardNumber);
	}
	
	public function getAccountNumber()
	{
		return $this->creditCardNumber;
	}
	
	public function processCharge($paymentProvider, $paymentTransaction)
	{
		return $paymentProvider->chargeCreditCard($paymentTransaction);
	}
	
	public function processAuthorization($paymentProvider, $paymentTransaction)
	{
		return $paymentProvider->authorizeCreditCard($paymentTransaction);
	}
	
	public function processVoid($paymentProvider, $paymentTransaction)
	{
		return $paymentProvider->voidCreditCardTransaction($transactionID);
	}
	
	public function processCredit($paymentProvider, $paymentTransaction)
	{
		return $paymentProvider->creditCreditCardTransaction($paymentTransaction->transactionID, $paymentTransaction->amount, $this->creditCardNumber);
	}
	
	public function cleanseCreditCardNumber($creditCardNumber)
	{
		return str_replace("-","",str_replace(" ","",$creditCardNumber));
	}
	
	public function isValidCreditCardNumber($creditCardNumber)
	{
		return (CreditCardTypes::getTypeFromNumber($creditCardNumber) == null) ? false : true;
	}
	
	public function isValidExpirationDate($month, $year)
	{
		return ($this->validateExpirationDate($month, $year) == null) ? true : false;
	}
	
	public function isValidCardCode($cardCode)
	{
		return ($this->validateCardCode($cardCode) == null) ? true : false;
	}
	
	public function getTypeFromNumber($creditCardNumber)
	{
		return CreditCardTypes::getTypeFromNumber($creditCardNumber);
	}
	
	public function getFirstName()
	{
		if(!isset($this->firstName))
		{
			$this->parseNameOnCard();
		}
		return $this->firstName;
	}
	
	public function getLastName()
	{
		if(!isset($this->lastName))
		{
			$this->parseNameOnCard();
		}
		return $this->lastName;
	}

/* private methods */
	private function parseNameOnCard()
	{
		$nameParts = explode(" ", $this->nameOnCard, 2);
		
		if ( count($nameParts) >= 2 )
		{
			$this->firstName = $nameParts[0];
			$this->lastName = $nameParts[1];
		}
		else
		{
			$this->firstName = $this->nameOnCard;
			$this->lastName = $this->nameOnCard;
		}
	}
	
	private function validateCreditCardNumber($creditCardNumber)
	{
		$util = WebUtilities::getInstance();
		$error = null;
		
		if (!$util->hasValue($this->creditCardNumber))
		{
			$error = $util->getString("errorCreditCardNumberRequired");
		}
		else if (CreditCardTypes::getTypeFromNumber($creditCardNumber) == null)
		{
			$error = $util->getString("errorCreditCardNumberInvalid");
		}
		
		return $error;
	}
	
	private function validateExpirationDate($month, $year)
	{
		$util = WebUtilities::getInstance();
		$error = null;
		
		if(!$util->hasValue($month))
		{
			$error = $util->getString("errorCreditCardExpirationMonthRequired");
		}
		else if(!$util->hasValue($year))
		{
			$error = $util->getString("errorCreditCardExpirationYearRequired");
		}
		else
		{
			$expirationDate = $util->getLastDayOfMonth(new DateTime($year . "-" . $month . "-01"));
			$today = new DateTime();
			if($today > $expirationDate)
			{
				$error = $util->getString("errorCreditCardExpirationExpired");
			}
		}
		
		return $error;
	}
	
	private function validateCardCode($cardCode)
	{
		$util = WebUtilities::getInstance();
		$error = null;
		
		if ( !$util->hasValue($cardCode) )
		{
			$error = $util->getString("errorCreditCardCodeRequired");
		}
		else if( !preg_match("/^[0-9]{3,4}$/", $cardCode) )
		{
			$error = $util->getString("errorCreditCardCodeInvalid");
		}
		
		return $error;
	}
	
	protected function getTypeMap()
	{
		return array(
			"paymentTypeID" => DataTypes::Int,
			"creditCardTypeID" => DataTypes::Int,
			"doSave" => DataTypes::Bit
		);
	}
}
?>