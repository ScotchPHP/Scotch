<?php
namespace Scotch\ECommerce;

interface IPaymentProvider 
{
/* credit card methods */
	function chargeCreditCard($paymentTransaction);
	
	function authorizeCreditCard($paymentTransaction);
	
	function voidCreditCardTransaction($transactionID);
	
	function creditCreditCardTransaction($transactionID,$amount,$cardNumberTruncated);
	
/* echeck methods */
	function chargeACH($paymentTransaction);
	
	function voidACH();
	
	function creditACH();
}
?>