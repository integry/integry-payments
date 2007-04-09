<?php

include_once('PaymentException.php');
include_once('TransactionResult.php');
include_once('TransactionError.php');

abstract class TransactionPayment
{		
	protected $details;
	
	protected $isTestTransaction = false;
	
	public function __construct(TransactionDetails $transactionDetails)
	{
		$this->details = $transactionDetails;
	}
	
	public function setAsTestTransaction($test = true)
	{
		$this->isTestTransaction = true;
	}
	
	/**
	 *	Determine if the payment method supports crediting a refund payment back to customer
	 */
	public abstract static function isCreditable();
}

abstract class IPNPaymentMethod
{
	
}

abstract class OfflinePaymentMethod
{
	public function isCreditable()
	{
		return false;
	}		
}

?>