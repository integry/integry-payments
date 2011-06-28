<?php

/**
 * DirectPayment Object
 *
 * This class is used to perform the DoDirectPayment operation
 * 
 * @author Israel Ekpo <perfectvista@users.sourceforge.net>
 * @copyright Copyright 2007, Israel Ekpo
 * @license http://phppaypalpro.sourceforge.net/LICENSE.txt BSD License
 * @version 0.2.1 
 * @package DoDirectPayment
 * @filesource
 */


/**
 * Used to invoke the DoDirectPayment Operation
 * 
 * @author Israel Ekpo <perfectvista@users.sourceforge.net>
 * @copyright Copyright 2007, Israel Ekpo
 * @license http://phppaypalpro.sourceforge.net/LICENSE.txt BSD License
 * @package DoDirectPayment
 */
final class DoDirectPayment extends PaypalAPI implements OperationsTemplate
{   
    /**
     * Message Sent to the Webservice
     *
     * @var array
     * @access private
     */
    private $apiMessage;

    /**
     * Prepares the message to be sent
     *
     * This method prepares the message to be sent to the 
     * Paypal Webservice
     * 
     * @access public
     * @param string $PaymentAction
     * @param PaymentDetailsType $PaymentDetails
     * @param CreditCardDetailsType $CreditCard
     * @param string $IPAddress
     * @param string $MerchantSessionId
     */
    public function setParams($PaymentAction, $PaymentDetails, $CreditCard, $IPAddress, $MerchantSessionId)
    {
        $this->apiMessage = PayPalTypes::DoDirectPaymentRequestDetailsType($PaymentAction, $PaymentDetails, $CreditCard, $IPAddress, $MerchantSessionId);
    }
    
    /**
     * Adds Item to the Payment Details
     *
     * @param string $Name
     * @param float $Number
     * @param integer $Quantity
     * @param float $Tax
     * @param float $Amount
     * @param string $currencyID
     */
    public function addPaymentItem($Name = '', $Number = '', $Quantity = 1, $Tax = '', $Amount = '', $currencyID = 'USD')
    {              
        $this->apiMessage['PaymentDetails']['PaymentDetailsItem'][] = PayPalTypes::PaymentDetailsItemType($Name, $Number, $Quantity, $Tax, $Amount, $currencyID);
    }
    
    /**
     * Executes the DoDirectPayment Operation
     *
     * Prepares the final message and the calls the Webservice operation. If it is successfull the response is registered
     * and the OperationStatus is set to true, otherwise the Operation status will be set to false and an Exception of the type
     * soapFault will be registered instead.
     * 
     * @throws SoapFault
     * @access public
     */
    public function execute($call = 'DoDirectPayment')
    {
        try
        {
            $this->apiMessage['Version'] = API_VERSION;
            
            if ('DoDirectPayment' == $call)
            {
				$this->apiMessage['DoDirectPaymentRequestDetails'] = $this->apiMessage;	            
	            $this->apiMessage = array('DoDirectPaymentRequest' => $this->apiMessage);
			}
			else if ('DoAuthorization' == $call)
			{
				$this->apiMessage['DoDirectPaymentRequestDetails'] = $this->apiMessage;	            
	            $this->apiMessage = array('DoAuthorizationRequest' => $this->apiMessage);				
			}
            
	        $this->apiMessage = array($this->apiMessage);				            
            
            parent::registerAPIResponse(PayPalBase::getSoapClient()->__soapCall($call, $this->apiMessage, null, PayPalBase::getSoapHeader()));
            
            PaypalBase::setOperationStatus(true);           
        }
        
        catch (SoapFault $Exception)
        {
            parent::registerAPIException($Exception);
            
            PaypalBase::setOperationStatus(false);
        }
    }
}

?>
