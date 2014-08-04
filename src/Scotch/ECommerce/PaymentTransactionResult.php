<?php
namespace Scotch\ECommerce;

class PaymentTransactionResult
{
	public $wasApproved;
	public $transactionID;
	public $errorCode;
	public $errorMessage;
	public $errorMessageCode;
	public $gatewayID;
	public $amountError;
	public $creditCardTypeError;
	public $creditCardNameError;
	public $creditCardNumberError;
	public $creditCardExpirationError;
	public $creditCardCVVError;
	public $bankRoutingNumberError;
	public $bankAccountNumberError;
	public $bankAccountTypeError;
	public $bankAccountNameError;
	public $driversLicenseNumberError;
	public $driversLicenseStateError;
	public $transactionIDError;
	public $transactionError;
	public $addressError;
	public $cityError;
	public $countryError;
	public $companyError;
	public $checkTypeError;
}
?>