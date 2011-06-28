<?php

/**
 * List of Examples
 *
 * This file contains an example of how each operation is supposed to be carried out.
 * 
 * I have commented out all the examples, simply uncomment the operations you wish to test
 * 
 * The examples here are presented using fictitious data, to reproduce the results you will have
 * to substitute the equivalent data with those of your own. Thank you.
 * 
 * You are required to sign up for your own API username, password and signature
 * 
 * The examples will be in the following order for all the operations supported by this program:
 * 
 * Simply include the file paypal_base.php and then you can go ahead to select any of the operations available
 * 
 * - DoDirectPayment
 * - SetExpressCheckout
 * - GetExpressCheckoutDetails
 * - DoExpressCheckoutPayment
 * 
 * More of the API operations shall be supported in the subsequent versions of this program
 *  
 * @author Israel Ekpo <perfectvista@users.sourceforge.net>
 * @copyright Copyright 2007, Israel Ekpo
 * @license http://phppaypalpro.sourceforge.net/LICENSE.txt BSD License
 * @version 0.2.1
 * @package PaypalBase
 * @filesource
 */


require_once('paypal_base.php');

$paymentAction = 'Sale';

$currencyId = 'USD';

$salutation = 'Mr.';
$fname      = 'Israel';
$lname      = 'Ekpo';
$address1   = '123 Main Street';
$address2   = 'Apt 987';
$city       = 'North Miami Beach';
$state      = 'FL';
$zip        = '33181';
$cc_country = 'US';

$phone      = '3059449455';

$cc_type    = 'Visa';
$cc_number  = '4147706547894046';
$cv2        = '917';
$exp_month  = '12';
$exp_year   = '2007';

$email       = 'paypal@example.net';

$item_desc   = 'Cool Sourceforge Software Example';

$order_desc  = 'Purchase from PerfectVista Technologies, Inc';
$custom      = 'WebPurchase';

$invoice     =  date('U');

$ip  = $_SERVER['REMOTE_ADDR'];

$unique_session_id = session_id();

$item1 = 40.00;
$item2 = 20.00;
$item3 = 30.00;

$tax1 = 7.00;
$tax2 = 14.00;
$tax3 = 21.00;

$item_total  = $item1 + $item2 + $item3;

$tax         = $tax1 + $tax2 + $tax3;	

$shipping    = 25.00;
$handling    = 75.00;

$order_total = $item_total + $shipping + $handling + $tax;


// Setting up the Authentication information
// Such as Username, Password, Signature and Subject

$API = new WebsitePaymentsPro();

$API_USERNAME = 'ibb_api1.perfectvista.net';

$API_PASSWORD = 'RMMP25ATEC3PZJX8';

$API_SIGNATURE = 'AiPC9BjkCyDFQXbSkoZcgqH3hpacA4EAG6uE6TDmFNlGFx6LWKnsoGLG';

$API->prepare($API_USERNAME, $API_PASSWORD, $API_SIGNATURE);

/*
// DoDirectPayment
//==========================================================================================================

$Paypal = $API->selectOperation('DoDirectPayment');

$Address = PayPalTypes::AddressType($fname . ''. $lname, $address1, $address2, $city, $state, $zip, $cc_country, $phone);

$PersonName = PayPalTypes::PersonNameType($salutation, $fname, '', $lname);

$PayerInfo = PayPalTypes::PayerInfoType($email, 'israelekpo', 'verified', $PersonName, $cc_country, '', $Address);

$CreditCardDetails = PayPalTypes::CreditCardDetailsType($cc_type, $cc_number, $exp_month, $exp_year, $PayerInfo, $cv2);

$PaymentDetails = PayPalTypes::PaymentDetailsType($order_total, $item_total, $shipping, $handling, $tax, $order_desc, $custom, $invoice, '', 'http://phppaypalpro.sourceforge.net/ipn_notify.php', $Address);

$Paypal->setParams($paymentAction, $PaymentDetails, $CreditCardDetails, $ip, $unique_session_id);

$Paypal->addPaymentItem('Perfume for Ladies', 'Item Number 90887', 1, $tax1, $item1, $currencyId);
$Paypal->addPaymentItem('Cologne for Gentlement', 'Item Number 90888', 1, $tax2, $item2, $currencyId);
$Paypal->addPaymentItem('Toys for Kids', 'Item Number 90889', 1, $tax3, $item3, $currencyId);

$Paypal->execute();

if ($Paypal->success())
{
	$response = $Paypal->getAPIResponse();
}
else 
{
	$response = $Paypal->getAPIException();
}


var_dump($response);




// SetExpressCheckout
// On Success Will return a token with value like EC-7EG51014BE327234S
// Customer should then be redirected by your server to URL like 
// https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=EC-7EG51014BE327234S
// Customer will then enter payment at paypal.com and upon success customer will be sent back to the $ReturnURL
// that you provided in the operation with the tokon and PayerID appended as parameters to the $ReturnURL
//==========================================================================================================

$Paypal = $API->selectOperation('SetExpressCheckout');

$OrderTotal = 300.00;

$ReturnURL = 'http://phppaypalpro.sourceforge.net/return_url.php';

$CancelURL = 'http://phppaypalpro.sourceforge.net/cancel_url.php';

$PaymentAction = 'Sale'; // or Order

$Paypal->setParams($OrderTotal, $ReturnURL, $CancelURL, $PaymentAction);

$Paypal->execute();

if ($Paypal->success())
{
	var_dump($Paypal->getAPIResponse());
} else 
{
	var_dump($Paypal->getAPIException());
} 


// GetExpressCheckoutDetails

// At the $Return URL get the ExpressCheckoutDetails by calling this operation using the token
// The PayperID will be used in the next step, DoExpressCheckoutPayment
// This operation returns all the details about the person making the payment (name, contact info etc)
//==========================================================================================================

$Paypal = $API->selectOperation('GetExpressCheckoutDetails');

$Token = $_REQUEST['token'];

$Paypal->setParams($Token);

$Paypal->execute();

if ($Paypal->success())
{
	var_dump($Paypal->getAPIResponse());
	
} 
else 
{
	var_dump($Paypal->getAPIException());
} 


// DoExpressCheckoutPayment
// The $token and $PayerID are used in this operation to complete the transaction.
// This is where the final Paypal Transaction ID like 4PJ31634YK772325W will be issued.
//==========================================================================================================
  

$Paypal = $API->selectOperation('DoExpressCheckoutPayment');

$PaymentAction = 'Sale'; // or Order

$Token = $_REQUEST['token'];

$PayerID = $_REQUEST['PayerID'];

$Address = PayPalTypes::AddressType($fname . ''. $lname, $address1, $address2, $city, $state, $zip, $cc_country, $phone);

$item_desc   = 'Cool Sourceforge Software';

$order_desc  = 'Purchase from PerfectVista Technologies';
$custom      = 'WebPurchase';

$invoice     =  date('U');

$ip  = $_SERVER['REMOTE_ADDR'];

$unique_session_id = session_id();

$item1 = 40.00;
$item2 = 20.00;
$item3 = 30.00;

$tax1 = 7.00;
$tax2 = 14.00;
$tax3 = 21.00;

$item_total  = $item1 + $item2 + $item3;

$tax         = $tax1 + $tax2 + $tax3;	

$shipping    = 25.00;
$handling    = 75.00;

$order_total = $item_total + $shipping + $handling + $tax;

$PaymentDetails = PayPalTypes::PaymentDetailsType($order_total, $item_total, $shipping, $handling, $tax, $order_desc, $custom, $invoice, '', 'http://phppaypalpro.sourceforge.net/ipn_notify.php', $Address);

$Paypal->setParams($PaymentAction, $Token, $PayerID, $PaymentDetails);

$Paypal->addPaymentItem('Perfume for Ladies', 'Item Number 90887', 1, $tax1, $item1, $currencyId);
$Paypal->addPaymentItem('Cologne for Gentlement', 'Item Number 90888', 1, $tax2, $item2, $currencyId);
$Paypal->addPaymentItem('Toys for Kids', 'Item Number 90889', 1, $tax3, $item3, $currencyId);


$Paypal->execute();

if ($Paypal->success())
{

	var_dump($Paypal->getAPIResponse());
} 
else 
{
	var_dump($Paypal->getAPIException());
} 
*/

/*
//
// TransactionSearch
// Searching for a set of Transactions by passing certain criteria to the Paypal Webservice
//==========================================================================================================

$Paypal = $API->selectOperation('TransactionSearch');
$StartDate  = strtotime('2006-11-19');
$EndDate    = strtotime('2006-11-25');

$PayerEmail = 'sbb@perfectvista.net';
$ReceiverEmail = 'perfectvista@users.sourceforge.net';
$ReceiptID = '2XF249546X016870H'; 
$TransactionID = '8A5447438G757964A'; 
$PayerName = PayPalTypes::PersonNameType($salutation, $fname, '', $lname); 
$AuctionItemNumber = ''; 
$InvoiceID = '9876542'; 
$CardNumber = '4010582588585520';
 
$TransactionClass = 'Sent'; // One of: Received, Masspay, MoneyRequest, 
// OR FundsAdded, FundsWithdrawn, Referral, Fee, Subscription, 
// OR Divdend, BillPay, Refund, CurrencyConversions, BalanceTransfer, Reversal, 
// OR Shipping, BalanceAffecting, Echeck

$Amount = '9.35';
$CurrencyCode = 'USD'; 
$Status = 'Success'; // One of: Processing, Success, Denied, Reversed
$currencyID = 'USD';

// If the value is an empty string that parameter will be excluded from the search criteria.

$Paypal->setParams($StartDate, $EndDate, $PayerEmail, $ReceiverEmail, $ReceiptID, $TransactionID, $PayerName, $AuctionItemNumber, $InvoiceID, $CardNumber, $TransactionClass, $Amount, $CurrencyCode, $Status, $currencyId);

$Paypal->execute();

if ($Paypal->success())
{
	$response = $Paypal->getAPIResponse();
	
} 
else 
{
	$response = $Paypal->getAPIException();
}

echo '<pre>';
var_dump($response);
echo '</pre>';

*/

/*
// GetTransactionDetails
// Retrieves the Transaction Details for a specific transaction by passing certain criteria to the Paypal Webservice.
// The details available here are better than those for the Transaction Search
//==========================================================================================================

$Paypal = $API->selectOperation('GetTransactionDetails');

$TransactionID = '11A317146P6709549';

$Paypal->setParams($TransactionID);

$Paypal->execute();

if ($Paypal->success())
{
	$response = $Paypal->getAPIResponse();
	
}
 
else 
{
	$response = $Paypal->getAPIException();
}

echo "<pre>";
var_dump($response);
echo "</pre>";
*/

?>
