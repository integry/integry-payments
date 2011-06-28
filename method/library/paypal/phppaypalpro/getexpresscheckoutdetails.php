<?php

/**
 * GetExpressCheckoutDetails Object
 *
 * This class is used to perform the GetExpressCheckoutDetails operation
 * 
 * @author Israel Ekpo <perfectvista@users.sourceforge.net>
 * @copyright Copyright 2007, Israel Ekpo
 * @license http://phppaypalpro.sourceforge.net/LICENSE.txt BSD License
 * @version 0.2.0
 * @package ExpressCheckout
 * @filesource
 */


/**
 * Used to invoke the GetExpressCheckoutDetails Operation
 * 
 * @author Israel Ekpo <perfectvista@users.sourceforge.net>
 * @copyright Copyright 2007, Israel Ekpo
 * @license http://phppaypalpro.sourceforge.net/LICENSE.txt BSD License
 * @package ExpressCheckout
 */
final class GetExpressCheckoutDetails extends PaypalAPI implements OperationsTemplate
{   
    /**
     * Message Sent to the Webservice
     *
     * @var array
     * @access private
     */
    private $apiMessage;
    
    /**
     * Token value returned from setExpressCheckout
     *
     * This is a 20 single-byte characters timestamped token, the value of which was returned by SetExpressCheckoutResponse
     * 
     * @var string
     * @access private
     */
    private $tokenValue;
    
    
    /**
     * Prepares the message to be sent
     *
     * This method prepares the message to be sent to the 
     * Paypal Webservice
     * 
     * @access public
     * @param string $Token
     */
    public function setParams($Token)
    {
        $this->tokenValue = $Token;
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
        	
            $this->apiMessage['Token']   = $this->tokenValue;
            
            $this->apiMessage = array('GetExpressCheckoutDetailsRequest' => $this->apiMessage);
            
            $this->apiMessage = array($this->apiMessage);
            
            $res = PayPalBase::getSoapClient()->__soapCall('GetExpressCheckoutDetails', $this->apiMessage, null, PayPalBase::getSoapHeader());
            
            /*
            $res = PayPalBase::postProcessResponse();
            
            var_dump($res);
            
            // sometimes the output doesn't get parsed for whatever reason...
            if (isset($res->GetExpressCheckoutDetailsResponseDetails) && !isset($res->GetExpressCheckoutDetailsResponseDetails->PayerInfo->Payer))
            {
                $dom = new DomDocument();
                $dom->loadXML(PayPalBase::getSoapClient()->__getLastResponse());
                $arr = self::xml2array($dom);
                $arr = self::array2stdclass($arr['SOAP-ENV:Envelope']['SOAP-ENV:Body']);
                $res = $arr->GetExpressCheckoutDetailsResponse;
            }
            */

            parent::registerAPIResponse($res);
            
            PaypalBase::setOperationStatus(true);           
        }
        
        catch (SoapFault $Exception)
        {
            parent::registerAPIException($Exception);
            
            PaypalBase::setOperationStatus(false);
        }
    }
    
    function xml2array($domnode)
    {
        $nodearray = array();
        $domnode = $domnode->firstChild;
        while (!is_null($domnode))
        {
            $currentnode = $domnode->nodeName;
            switch ($domnode->nodeType)
            {
                case XML_TEXT_NODE:
                    if(!(trim($domnode->nodeValue) == "")) $nodearray = $domnode->nodeValue;
                break;
                case XML_ELEMENT_NODE:
                    if ($domnode->hasAttributes() )
                    {
                        $elementarray = array();
                        $attributes = $domnode->attributes;
                        foreach ($attributes as $index => $domobj)
                        {
                            $elementarray[$domobj->name] = $domobj->value;
                        }
                    }
                break;
            }
            if ( $domnode->hasChildNodes() )
            {
                $nodearray[$currentnode] = self::xml2array($domnode);
                if (isset($elementarray))
                {
                    $currnodeindex = count($nodearray[$currentnode]) - 1;
                }
            } 
            
            $domnode = $domnode->nextSibling;
        }
        return $nodearray;
    }    
    
    function array2stdclass($array)
    {
        if (!is_array($array))
        {
            return $array;
        }
        
        $out = new StdClass;
        foreach ($array as $key => $data)
        {
            $out->$key = is_array($data) ? self::array2stdclass($data) : $data;
        }
        
        return $out;
    }
}

?>