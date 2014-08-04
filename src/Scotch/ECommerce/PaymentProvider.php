<?php
namespace Scotch\ECommerce;

use Scotch\ECommerce\IPaymentProvider as IPaymentProvider;
use Scotch\Exceptions\NotImplementedException as NotImplementedException;

abstract class PaymentProvider implements IPaymentProvider 
{	
/* credit card */
	
	function chargeCreditCard($paymentTransaction)
	{
		throw new NotImplementedException("chargeCreditCard is not implemented for this Payment Provider");
	}
	
	function authorizeCreditCard($paymentTransaction)
	{
		throw new NotImplementedException("authorizeCreditCard is not implemented for this Payment Provider");
	}
	
	function voidCreditCardTransaction($transactionID)
	{
		throw new NotImplementedException("voidCreditCardTransaction is not implemented for this Payment Provider");
	}
	
	function creditCreditCardTransaction($transactionID,$amount,$cardNumber)
	{
		throw new NotImplementedException("creditCreditCardTransaction is not implemented for this Payment Provider");
	}
	
	
/* echeck */
	function chargeACH($paymentTransaction)
	{
		throw new NotImplementedException("chargeACH is not implemented for this Payment Provider");
	}
	
	function voidACH()
	{
		throw new NotImplementedException("voidACHTransaction is not implemented for this Payment Provider");
	}
	
	function creditACH()
	{
		throw new NotImplementedException("creditACHTransaction is not implemented for this Payment Provider");
	}
	
/* gateway */
	abstract function getGatewayID();
}
?>