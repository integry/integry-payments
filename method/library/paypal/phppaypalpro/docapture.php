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
 * Used to invoke the DoCapture Operation
 * 
 * @author Integry Systems
 * @package DoDirectPayment
 */
final class DoCapture extends PaypalAPI implements OperationsTemplate
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
    public function setParams($authorizationID, $amount, $currency, $completeType, $note, $invoiceID)
    {
        $this->apiMessage = array();
        $this->apiMessage['AuthorizationID'] = $authorizationID;
        $this->apiMessage['Amount'] = array('_' => $amount, 'currencyID' => $currency);
        $this->apiMessage['CompleteType'] = $completeType;
        $this->apiMessage['Note'] = $note;
        $this->apiMessage['InvoiceID'] = $invoiceID;
    }
    
    /**
     * Executes the DoCapture Operation
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
            
            $this->apiMessage = array('DoCaptureRequest' => $this->apiMessage);
            
	        $this->apiMessage = array($this->apiMessage);				            
            
            parent::registerAPIResponse(PayPalBase::getSoapClient()->__soapCall('DoCapture', $this->apiMessage, null, PayPalBase::getSoapHeader()));
            
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
