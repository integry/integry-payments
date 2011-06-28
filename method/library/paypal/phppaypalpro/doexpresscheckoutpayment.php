<?php

/**
 * DoExpressCheckoutPayment Object
 *
 * This class is used to perform the DoExpressCheckoutPayment operation
 * 
 * @author Israel Ekpo <perfectvista@users.sourceforge.net>
 * @copyright Israel Ekpo, 2006-2007
 * @license http://phppaypalpro.sourceforge.net/LICENSE.txt BSD License
 * @version 0.2.1
 * @package ExpressCheckout
 * @filesource
 */


/**
 * Used to invoke the DoExpressCheckoutPayment Operation
 * 
 * @author Israel Ekpo <perfectvista@users.sourceforge.net>
 * @copyright Copyright 2007, Israel Ekpo
 * @license http://phppaypalpro.sourceforge.net/LICENSE.txt BSD License
 * @package ExpressCheckout
 */
final class DoExpressCheckoutPayment extends PaypalAPI implements OperationsTemplate
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
     * @param string $Token
     * @param string $PayerID
     * @param string $PaymentDetails
     */
    public function setParams($PaymentAction, $Token, $PayerID, $PaymentDetails)
    {
        $this->apiMessage = PayPalTypes::DoExpressCheckoutPaymentRequestDetailsType($PaymentAction, $Token, $PayerID, $PaymentDetails);
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
     * Executes the DoExpressCheckoutPayment Operation
     *
     * Prepares the final message and the calls the Webservice operation. If it is successfull the response is registered
     * and the OperationStatus is set to true, otherwise the Operation status will be set to false and an Exception of the type
     * soapFault will be registered instead.
     * 
     * @throws SoapFault
     * @access public
     */
    public function execute()
    {
        try
        {
            $this->apiMessage['Version'] = API_VERSION;
        	
            $this->apiMessage['DoExpressCheckoutPaymentRequestDetails'] = $this->apiMessage;
        
            $this->apiMessage = array('DoExpressCheckoutPaymentRequest' => $this->apiMessage);
            
            $this->apiMessage = array($this->apiMessage);
            
            parent::registerAPIResponse(PayPalBase::getSoapClient()->__soapCall('DoExpressCheckoutPayment', $this->apiMessage, null, PayPalBase::getSoapHeader()));
            
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
