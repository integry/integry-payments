<?php

/**
 * GetTransactionDetails Object
 *
 * This class is used to perform the GetTransactionDetails operation
 * 
 * @author Israel Ekpo <perfectvista@users.sourceforge.net>
 * @copyright Copyright 2007, Israel Ekpo
 * @license http://phppaypalpro.sourceforge.net/LICENSE.txt BSD License
 * @version 0.2.1
 * @package TransactionSearch
 * @filesource
 */


/**
 * Used to invoke the GetExpressCheckoutDetails Operation
 * 
 * @author Israel Ekpo <perfectvista@users.sourceforge.net>
 * @copyright Copyright 2007, Israel Ekpo
 * @license http://phppaypalpro.sourceforge.net/LICENSE.txt BSD License
 * @package TransactionSearch
 */
final class GetTransactionDetails extends PaypalAPI implements OperationsTemplate
{   
    /**
     * Message Sent to the Webservice
     *
     * @var array
     * @access private
     */
    private $apiMessage;
    
    /**
     * The TransactionID
     * 
     * The TransactionID to get details for.
     *  
     * @var string
     * @access private
     */
    private $TransactionID;
    
    
    /**
     * Prepares the message to be sent
     *
     * This method prepares the message containing the TransactionID to be sent to the 
     * Paypal Web service
     * 
     * @access public
     * @param string $TransactionID
     */
    public function setParams($TransactionID)
    {
        $this->TransactionID = $TransactionID;
    }
    
    /**
     * Executes the GetTransactionDetails Operation
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
        	
            $this->apiMessage['TransactionID']   = $this->TransactionID;
            
            $this->apiMessage = array('GetTransactionDetailsRequest' => $this->apiMessage);
            
            $this->apiMessage = array($this->apiMessage);
            
            parent::registerAPIResponse(PayPalBase::getSoapClient()->__soapCall('GetTransactionDetails', $this->apiMessage, null, PayPalBase::getSoapHeader()));
            
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
