<?php

/**
 * Preparation of WebService DataTypes
 * 
 * Copyright (C) 2007 Israel Ekpo
 * 
 * Contains the class which has methods that generates a list of datatypes (arrays) used by the webs service.
 * Most of the datatypes are passed to the SoapClient->__soapCall() method as multi-dimensional arrays.
 * 
 * @package PaypalTypes
 * @author Israel Ekpo <israelekpo@sourceforge.net>
 * @copyright Copyright 2007, Israel Ekpo
 * @version 0.2.1
 * @license http://phppaypalpro.sourceforge.net/LICENSE.txt BSD License
 * @filesource
 */

/**
 * This class contains the list of all the Data Types used when contacting the Paypal webservice
 * 
 * @package PaypalTypes
 * @author Israel Ekpo <israelekpo@sourceforge.net>
 * @copyright Copyright 2007, Israel Ekpo
 * @license http://phppaypalpro.sourceforge.net/LICENSE.txt BSD License
 * @filesource
 */
class PayPalTypes
{   
    /**
     * Formats the Amount according to the BasicAmountType
     *
     * This method creates a classical example of an element that has an attribute.
     * For example <element attribute1='attr1' attribute2='attr2'>value</element>
     * will be created as array('_' => 'value', 'attr1' => 'attribute1', 'attr2' => 'attribute2');
     * 
     * @param string $amount
     * @param string $currencyID
     * @usedby PaypalTypes::PaymentDetailsType()
     * @usedby PaypalTypes::PaymentDetailsItemType()
     * @return BasicAmountType
     */
    static public function BasicAmountType($amount = '0.00', $currencyID = 'USD')
    {
        $BasicAmountType['_'] = number_format($amount, 2);
        $BasicAmountType['currencyID'] = $currencyID;
        
        return $BasicAmountType;
    }
    
    /**
     * Prepares the AddressType
     * 
     * This method is used to generate the AddressType for Payment information.
     *
     * @param string $Name
     * @param string $Street1
     * @param string $Street2
     * @param string $CityName
     * @param string $StateOrProvince
     * @param string $PostalCode
     * @param string $Country
     * @param string $Phone
     * @return AddressType
     */
    static public function AddressType($Name = '', $Street1 = '', $Street2 = '', $CityName = '', $StateOrProvince = '', $PostalCode = '', $Country = '', $Phone = '')
    {
        $AddressType['Name'] = $Name;
        $AddressType['Street1'] = $Street1;
        $AddressType['Street2'] = $Street2;
        $AddressType['CityName'] = $CityName;
        $AddressType['StateOrProvince'] = $StateOrProvince;
        $AddressType['Country'] = $Country;
        $AddressType['PostalCode'] = $PostalCode;
        $AddressType['Phone'] = $Phone;
        
        return $AddressType;
    }
    
    /**
     * Prepares the PersonNameType
     * 
     * The person name type is used to format the name information for onward inclusion in the SOAP message.
     *
     * @param string $Salutation
     * @param string $FirstName
     * @param string $MiddleName
     * @param string $LastName
     * @param string $Suffix
     * @return PersonNameType
     */
    static public function PersonNameType($Salutation = '', $FirstName = '', $MiddleName = '', $LastName = '', $Suffix = '')
    {
        if ($Salutation)
        {
            $PersonNameType['Salutation'] = $Salutation;
        }
        
        $PersonNameType['FirstName'] = $FirstName;
        
        if ($MiddleName)
        {
            $PersonNameType['MiddleName'] = $MiddleName;
        }
        
        $PersonNameType['LastName']  = $LastName;
        
        if ($Suffix)
        {
            $PersonNameType['Suffix'] = $Suffix;
        }
        
        return $PersonNameType;
    }
    
    /**
     * Prepares the PayerInfoType
     *
     * The PayerInfoType uses the AddressType and PersonNameType to generate a multi-dimensional array
     * that will in turn be used as part of a SOAP message.
     * 
     * @param EmailAddressType $Payer e.g buyer@gmail.com
     * @param string $PayerID
     * @param string $PayerStatus verified, unverified
     * @param PersonNameType $PayerName
     * @param string $PayerCountry
     * @param string $PayerBusiness
     * @param AddressType $Address
     * @param string $ContactPhone
     * @return PayerInfoType
     */
    static public function PayerInfoType($Payer = '', $PayerID = '', $PayerStatus = 'verified', $PayerName, $PayerCountry= 'US', $PayerBusiness = '', $Address, $ContactPhone = '')
    {
		$PayerInfoType['Payer'] = $Payer;
		$PayerInfoType['PayerID'] = $PayerID;
		$PayerInfoType['PayerStatus'] = $PayerStatus;
		$PayerInfoType['PayerName'] = $PayerName;
		$PayerInfoType['PayerCountry'] = $PayerCountry;
		
		if ($PayerBusiness)
		{
		    $PayerInfoType['PayerBusiness'] = $PayerBusiness;
		}
		
		$PayerInfoType['Address'] = $Address;
		
		if ($ContactPhone)
		{
		    $PayerInfoType['ContactPhone'] = $ContactPhone;
		}
		
		return $PayerInfoType;
    }
    
    /**
     * Makes the CreditCardDetailsType
     * 
     * This is a very essential data type because it has to be formatted correctly. This method is used to 
     * generate an array that is going to be part of the payment details submitted to paypal.
     *
     * @param string $CreditCardType Visa,MasterCard,Amex,Discover,Solo,Switch
     * @param string $CreditCardNumber
     * @param int $ExpMonth
     * @param int $ExpYear
     * @param PayerInfoType $CardOwner
     * @param string $CVV2
     * @return CreditCardDetailsType
     */
    static public function CreditCardDetailsType($CreditCardType, $CreditCardNumber, $ExpMonth, $ExpYear, $CardOwner, $CVV2 = '')
    {
        $CreditCardDetailsType['CreditCardType'] = $CreditCardType;
        $CreditCardDetailsType['CreditCardNumber'] = $CreditCardNumber;
        $CreditCardDetailsType['ExpMonth'] = $ExpMonth;
        $CreditCardDetailsType['ExpYear'] = $ExpYear;
        $CreditCardDetailsType['CardOwner'] = $CardOwner;
        
        if($CVV2)
        {
            $CreditCardDetailsType['CVV2'] = $CVV2;
        }
        
        return $CreditCardDetailsType;
    }
    
    /**
     * Makes PaymentDetailsItemType
     *
     * This generates details about a particular payment item. An array is returned which contains the name, quantity
     * and amound of each item added to the payment.
     * 
     * @param string $Name
     * @param string $Number
     * @param integer $Quantity
     * @param BasicAmountType $Tax
     * @param BasicAmountType $Amount
     * @param string $CurrencyID
     * @return PaymentDetailsItemType
     */
    static public function PaymentDetailsItemType($Name = '', $Number = '', $Quantity = 1, $Tax = '', $Amount = '', $CurrencyID = 'USD')
    {
        $PaymentDetailsItemType['Name'] = $Name;
        $PaymentDetailsItemType['Number'] = $Number;
        $PaymentDetailsItemType['Quantity'] = $Quantity;
        
        if ($Tax)
        {
            $PaymentDetailsItemType['Tax'] = self::BasicAmountType($Tax, $CurrencyID);
        }
        
        if ($Amount)
        {
            $PaymentDetailsItemType['Amount'] = self::BasicAmountType($Amount, $CurrencyID);
        }
        
        return $PaymentDetailsItemType;
    }
    
    /**
     * Creates the PaymentDetailsType
     *
     * This is a critical part of the DoDirectPayment and DoExpressCheckOutPayment operations.
     * The payment details is an overall summary of the payment to be sent to the paypal web service.
     * 
     * @param BasicAmountType $OrderTotal
     * @param BasicAmountType $ItemTotal
     * @param BasicAmountType $ShippingTotal
     * @param BasicAmountType $HandlingTotal
     * @param BasicAmountType $TaxTotal
     * @param string $OrderDescription
     * @param string $Custom
     * @param string $InvoiceID
     * @param string $ButtonSource
     * @param string $NotifyURL Paypal IPN URL
     * @param AddressType $ShipToAddress
     * @param PaymentDetailsItemType $PaymentDetailsItem
     * @param string $CurrencyID
     * @return PaymentDetailsType
     */
    static public function PaymentDetailsType($OrderTotal, $ItemTotal ='0.00', $ShippingTotal = '0.00', $HandlingTotal = '0.00', $TaxTotal = '0.00', $OrderDescription = '', $Custom = '', $InvoiceID = '', $ButtonSource = '', $NotifyURL = '', $ShipToAddress = '', $PaymentDetailsItem = array(), $CurrencyID= 'USD')
    {
        $PaymentDetailsType['OrderTotal'] = self::BasicAmountType($OrderTotal, $CurrencyID);
        $PaymentDetailsType['ItemTotal'] = self::BasicAmountType($ItemTotal, $CurrencyID);
        $PaymentDetailsType['ShippingTotal'] = self::BasicAmountType($ShippingTotal, $CurrencyID);
        $PaymentDetailsType['HandlingTotal'] = self::BasicAmountType($HandlingTotal, $CurrencyID);
        $PaymentDetailsType['TaxTotal'] = self::BasicAmountType($TaxTotal, $CurrencyID);
        $PaymentDetailsType['OrderDescription'] = $OrderDescription;
        $PaymentDetailsType['Custom'] = $Custom;
        $PaymentDetailsType['InvoiceID'] = $InvoiceID;
        $PaymentDetailsType['ButtonSource'] = $ButtonSource;
        $PaymentDetailsType['NotifyURL'] = $NotifyURL;
        $PaymentDetailsType['ShipToAddress'] = $ShipToAddress;
        $PaymentDetailsType['PaymentDetailsItem'] = $PaymentDetailsItem;

        return $PaymentDetailsType;
    }
    
    /**
     * Prepares the DoDirectPaymentRequestDetailsType
     *
     * This basically returns a multi-dimensional array with the Payment Action. Credit Card information, User IP
     * address and the merchant's session id.
     * 
     * @param string $PaymentAction This could be a Sale or Order.
     * @param PaymentDetailsType $PaymentDetails
     * @param CreditCardDetailsType $CreditCard
     * @param string $IPAddress
     * @param string $MerchantSessionId
     * @return DoDirectPaymentRequestDetailsType
     */
    static public function DoDirectPaymentRequestDetailsType($PaymentAction, $PaymentDetails, $CreditCard, $IPAddress, $MerchantSessionId)
    {
        $DoDirectPaymentRequestDetailsType['PaymentAction'] = $PaymentAction;
        $DoDirectPaymentRequestDetailsType['PaymentDetails'] = $PaymentDetails;
        $DoDirectPaymentRequestDetailsType['CreditCard'] = $CreditCard;
        $DoDirectPaymentRequestDetailsType['IPAddress'] = $IPAddress;
        $DoDirectPaymentRequestDetailsType['MerchantSessionId'] = $MerchantSessionId;
        
        return $DoDirectPaymentRequestDetailsType;
    }
    
    /**
     * Prepares the SetExpressCheckoutRequestDetailsType
     * 
     * This is an array with the Amount, Return URL, Cancellation URL and Payment Action (Sale or Order)
     *
     * @param BasicAmountType $OrderTotal
     * @param string $ReturnURL
     * @param string $CancelURL
     * @param string $PaymentAction Sale or Order
     * @return SetExpressCheckoutRequestDetailsType
     */
    static public function SetExpressCheckoutRequestDetailsType($OrderTotal, $ReturnURL, $CancelURL, $PaymentAction, $currencyID = 'USD')
    {
    	$SetExpressCheckoutRequestDetailsType['OrderTotal'] = self::BasicAmountType($OrderTotal, $currencyID);
    	$SetExpressCheckoutRequestDetailsType['ReturnURL'] = $ReturnURL;
    	$SetExpressCheckoutRequestDetailsType['CancelURL'] = $CancelURL;
    	$SetExpressCheckoutRequestDetailsType['PaymentAction'] = $PaymentAction;
    	
    	return $SetExpressCheckoutRequestDetailsType;
    }
    
    /**
     * Makes the DoExpressCheckoutPaymentRequestDetailsType
     *
     * This returns the Payment action, Token value, Payer ID and Payment details as a multi-dimensional array.
     * 
     * @param string $PaymentAction Order or Sale
     * @param string $Token
     * @param string $PayerID
     * @param PaymentDetailsType $PaymentDetails
     * @return DoExpressCheckoutPaymentRequestDetailsType
     */
    static public function DoExpressCheckoutPaymentRequestDetailsType($PaymentAction, $Token, $PayerID, $PaymentDetails)
    {
    	$DoExpressCheckoutPaymentRequestDetailsType['PaymentAction'] = $PaymentAction;
    	$DoExpressCheckoutPaymentRequestDetailsType['Token'] = $Token;
    	$DoExpressCheckoutPaymentRequestDetailsType['PayerID'] = $PayerID;
    	$DoExpressCheckoutPaymentRequestDetailsType['PaymentDetails'] = $PaymentDetails;
    	
    	return $DoExpressCheckoutPaymentRequestDetailsType;
    }
    
    /**
     * Makes the UserIdPasswordType
     *
     * This prepares the authentication message to be passed to paypal. It is an array
     * with the Username, Password and Signature of the user. If the payment is being made on behalf of
     * another account then the username of that account has to be passed as the subject.
     * 
     * @param string $Username
     * @param string $Password
     * @param string $Signature
     * @param string $Subject
     * @return UserIdPasswordType
     */
    static public function UserIdPasswordType($Username = '', $Password = '', $Signature = '', $Subject= '')
    {
        
       $UserIdPasswordType['Username'] = $Username;
       $UserIdPasswordType['Password'] = $Password;
       $UserIdPasswordType['Signature'] = $Signature;
       
       if ($Subject)
       {
           $UserIdPasswordType['Subject'] = $Subject;
       }
       
       return $UserIdPasswordType;
    }
    
    /**
     * Returns the date in the ISO 8601 format
     *
     * Generates the ISO 8601 format using the UNIX timestamp supplied. Like 2007-02-02T01:36:06-05:00
     * which is basically the year, month, day, 24-hour format, minutes, seconds and time zone like -05:00 for EST
     * 
     * @param integer $timeStamp the Unix Timestamp
     * @return string
     */
    static public function dateTimeType($timeStamp = 0)
    {
    	$timeStamp = (int) $timeStamp;
    	
    	if (!$timeStamp)
    	{
    		return date('c');
    	}
    	else 
    	{
    		return date('c', $timeStamp);
    	}
    }
    
    /**
     * Generates the TransactionSearchRequestType
     * 
     * Prepares a multi-dimensional array to be used in the search.
     *
     * @uses self::dateTimeType
     * @param integer $StartDate UNIXTIMESTAMP
     * @param intefer $EndDate UNIXTIMESTAMP
     * @param string $PayerEmail
     * @param string $ReceiverEmail
     * @param string $ReceiptID
     * @param string $TransactionID
     * @param string $PayerName
     * @param string $AuctionItemNumber
     * @param string $InvoiceID
     * @param string $CardNumber
     * @param string $TransactionClass
     * @param BasicAmountType $Amount
     * @param string $CurrencyCode
     * @param string $Status
     * @return TransactionSearchRequestType
     */
    static public function TransactionSearchRequestType($StartDate, $EndDate = 0, $PayerEmail = '', $ReceiverEmail = '', $ReceiptID = '', $TransactionID = '', $PayerName = '', $AuctionItemNumber = '', $InvoiceID = '', $CardNumber = '', $TransactionClass = '', $Amount = '', $CurrencyCode = '', $Status = '', $currencyID = 'USD')
    {
    	$TransactionSearchRequestType['StartDate'] = self::dateTimeType($StartDate);
    	
    	if ($EndDate)
    	{
    		$TransactionSearchRequestType['EndDate'] = self::dateTimeType($EndDate);
    	}
    	
    	if ($PayerEmail)
    	{
    		$TransactionSearchRequestType['Payer'] = $PayerEmail;
    	}
    	
    	if ($ReceiverEmail)
    	{
    		$TransactionSearchRequestType['Receiver'] = $ReceiverEmail;
    	}
    	
    	if ($ReceiptID)
    	{
    		$TransactionSearchRequestType['ReceiptID'] = $ReceiptID;
    	}
    	    	
    	if ($TransactionID)
    	{
    		$TransactionSearchRequestType['TransactionID'] = $TransactionID;
    	}
    	
    	if ($PayerName)
    	{
    		$TransactionSearchRequestType['PayerName'] = $PayerName;
    	}
    	
    	if ($AuctionItemNumber)
    	{
    		$TransactionSearchRequestType['AuctionItemNumber'] = $AuctionItemNumber;
    	}
    	    	
    	if ($InvoiceID)
    	{
    		$TransactionSearchRequestType['InvoiceID'] = $InvoiceID;
    	}
    	
    	if ($CardNumber)
    	{
    		$TransactionSearchRequestType['CardNumber'] = $CardNumber;
    	}
    	
    	if ($TransactionClass)
    	{
    		$TransactionSearchRequestType['TransactionClass'] = $TransactionClass; 
    	}
    	
    	if ($Amount)
    	{
    		$TransactionSearchRequestType['Amount'] = self::BasicAmountType($Amount, $currencyID);
    	}

    	if ($CurrencyCode)
    	{
    		$TransactionSearchRequestType['CurrencyCode'] = $CurrencyCode;
    	}
    	
    	if ($Status)
    	{
    		$TransactionSearchRequestType['Status'] = $Status;
    	}
    	
    	return $TransactionSearchRequestType;
    }

	/**
	 * wrapper for BillingAgreementDescription and 
	 * 
	 */
	static public function RecurringItemDescription(RecurringItem $recurringItem)
	{
		$recurringItem->recurringID->get()->load();
		$recurringItem->orderedItemID->get()->load();
		$recurringItemArray = $recurringItem->toArray();

		$productName = $recurringItemArray['OrderedItem']['name'];
		if (strlen($productName) > 3)
		{
			$productName = substr($productName, 0, 60).'..';
		}
		$planName = $recurringItemArray['Recurring']['name'];
		if (strlen($planName) > 63)
		{
			$planName = substr($planName, 0, 60).'..';
		}
		return  sprintf('%s %s', $productName, $planName);
	}

	static public function BillingAgreementDetailsType(RecurringItem $recurringItem, $BillingType = 'RecurringPayments', $PaymentType ='Any', $BillingAgreementCustom='')
	{
		return array(
			'BillingAgreementDescription' => PayPalTypes::RecurringItemDescription($recurringItem),
			'BillingType'=>$BillingType,
			'PaymentType'=>$PaymentType,
			'BillingAgreementCustom'=>$BillingAgreementCustom
		);
	}
}

?>