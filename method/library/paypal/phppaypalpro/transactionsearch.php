<?php

/**
 * TransactionSearch Object
 *
 * This class is used to perform the TransactionSearch operation
 * 
 * @author Israel Ekpo <perfectvista@users.sourceforge.net>
 * @copyright Copyright 2007, Israel Ekpo
 * @license http://phppaypalpro.sourceforge.net/LICENSE.txt BSD License
 * @version 0.2.1
 * @package TransactionSearch
 * @filesource
 */


/**
 * Used to invoke the TransactionSearch Operation
 * 
 * @author Israel Ekpo <perfectvista@users.sourceforge.net>
 * @copyright Copyright 2007, Israel Ekpo
 * @license http://phppaypalpro.sourceforge.net/LICENSE.txt BSD License
 * @package TransactionSearch
 */
final class TransactionSearch extends PaypalAPI implements OperationsTemplate
{   
    /**
     * Message Sent to the Webservice
     *
     * @var array
     * @access private
     */
    private $apiMessage;
    
    
    /**
     * Generates the TransactionSearchRequestType
     * 
     * Prepares a multi-dimensional array for the message to be used in the search.
     *
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
     * @param float $Amount
     * @param string $CurrencyCode
     * @param string $Status
     * @param string $currencyID
     */

    public function setParams($StartDate, $EndDate = 0, $PayerEmail = '', $ReceiverEmail = '', $ReceiptID = '', $TransactionID = '', $PayerName = '', $AuctionItemNumber = '', $InvoiceID = '', $CardNumber = '', $TransactionClass = '', $Amount = '', $CurrencyCode = '', $Status = '', $currencyID = 'USD')
    {
        $this->apiMessage = PayPalTypes::TransactionSearchRequestType($StartDate, $EndDate, $PayerEmail, $ReceiverEmail, $ReceiptID, $TransactionID, $PayerName, $AuctionItemNumber, $InvoiceID, $CardNumber, $TransactionClass, $Amount, $CurrencyCode, $Status, $currencyID);
    }
    
    /**
     * Executes the Operation
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
            
            $this->apiMessage = array('TransactionSearchRequest' => $this->apiMessage);
            
            $this->apiMessage = array($this->apiMessage);
            
            parent::registerAPIResponse(PayPalBase::getSoapClient()->__soapCall('TransactionSearch', $this->apiMessage, null, PayPalBase::getSoapHeader()));
            
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
