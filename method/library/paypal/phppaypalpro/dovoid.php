<?php

/**
 * Used to invoke the DoCapture Operation
 * 
 * @author Integry Systems
 * @package DoDirectPayment
 */
final class DoVoid extends PaypalAPI implements OperationsTemplate
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
    public function setParams($authorizationID, $note)
    {
        $this->apiMessage = array();
        $this->apiMessage['AuthorizationID'] = $authorizationID;
        $this->apiMessage['Note'] = $note;
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
            
            $this->apiMessage = array('DoVoidRequest' => $this->apiMessage);
            
	        $this->apiMessage = array($this->apiMessage);				            
            
            parent::registerAPIResponse(PayPalBase::getSoapClient()->__soapCall('DoVoid', $this->apiMessage, null, PayPalBase::getSoapHeader()));
            
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
