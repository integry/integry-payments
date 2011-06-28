<?php

/**
 * Paypal Base Files
 *
 * This file contains very important classes and interfaces that are going to be used in this program
 *
 * - interface OperationsTemplate
 * - abstract class PaypalAPI
 * - static class PaypalBase
 * - final class PaypalRegistrar
 * - final class WebsitePaymentsPro
 *
 * phpPaypalPro currently supports 4 major operations available for the Website Payments Pro SOAP API namely:
 *
 * (a) DoDirectPayment
 * (b) SetExpressCheckout
 * (c) GetExpressCheckoutDetails
 * (d) DoExpressCheckoutPayment
 *
 * Please be on the lookout as more operations are scheduled to be added in the immediate future.
 *
 * @author Israel Ekpo <perfectvista@users.sourceforge.net>
 * @copyright Copyright 2007, Israel Ekpo
 * @license http://phppaypalpro.sourceforge.net/LICENSE.txt BSD License
 * @version 0.2.1
 * @package PaypalBase
 * @filesource
 */

/**
 * Contains Webservice Data Types
 *
 * All the data types used by this program is prepared
 * by a class called PaypalTypes in this file.
 */
require_once('paypal_types.php');

/**
 * Contains Configuration Data
 *
 * Things like the API version, connection timeouts etc are
 * registered in this module.
 */
require_once('config.inc.php');

/**
 * Paypal Base Class
 *
 * This class contains important variables that are going to be
 * used throughout the entire system.
 *
 * @author Israel Ekpo <perfectvista@users.sourceforge.net>
 * @copyright Copyright 2007, Israel Ekpo
 * @license http://phppaypalpro.sourceforge.net/LICENSE.txt BSD License
 * @package PaypalBase
 */
class PaypalBase
{
    public static $isLive = false;

 /**
     * Soap Client
     *
     * @access private
     * @var soapClient
     */
    private static $soapClient;

    /**
     * Soap Header
     *
     * @access private
     * @var soapHeader
     */
    private static $soapHeader;

    /**
     * Was the Last Operation a Success
     *
     * @access private
     * @var bool
     */
    private static $lastOperationSuccessful;

    /**
     * API Reponse
     *
     * This is the last reponse recieved from the paypal web service
     *
     * @access private
     * @var mixed
     */
    private static $apiResponse;

    /**
     * API Exception
     *
     * @access private
     * @var soapFault
     */
    private static $apiSoapException;

    /**
     * Sets the Value of the soapClient Attribute
     *
     * @static
     * @access public
     * @param soapClient $soapClient
     */
    public static function setSoapClient($soapClient)
    {
        self::$soapClient = $soapClient;
    }

    /**
     * Sets the Value of the SoapHeader
     *
     * @static
     * @access public
     * @param SoapHeader $soapHeader
     */
    public static function setSoapHeader($soapHeader)
    {
        self::$soapHeader = $soapHeader;
    }

    /**
     * Sets API response from Webservice
     *
     * @static
     * @access public
     * @param mixed $apiResponse
     */
    public static function setApiResponse($apiResponse)
    {
        self::$apiResponse = $apiResponse;
    }

    /**
     * Registers the Exception that was thrown
     *
     * @static
     * @access public
     * @param soapFault $apiSoapException
     */
    public static function setAPISoapFault($apiSoapException)
    {
        self::$apiSoapException = $apiSoapException;
    }

    /**
     * Sets the Last Operation Status
     *
     * This tells us whether or not the last operation was a success.
     *
     * @static
     * @access public
     * @param bool $status
     */
    public static function setOperationStatus($status)
    {
        self::$lastOperationSuccessful = $status;
    }

    /**
     * Returns the Soap Client
     *
     * @static
     * @access public
     * @return SoapClient
     */
    public static function getSoapClient()
    {
        return self::$soapClient;
    }

    /**
     * Returns the Soap Header
     *
     * @static
     * @access public
     * @return SoapHeader
     */
    public static function getSoapHeader()
    {
        return self::$soapHeader;
    }

    /**
     * Returns the last API Response
     *
     * This method returns the last response from the paypal webservice.
     *
     * @static
     * @access public
     * @return mixed
     */
    public static function getAPIResponse()
    {
        return self::$apiResponse;
    }

    /**
     * Returns the Exception object that was thrown
     *
     * @static
     * @access public
     * @return soapFault
     */
    public static function getAPIException()
    {
        return self::$apiSoapException;
    }

    /**
     * Returns the Last Operation Status
     *
     * It returns true if the last operation was a success and false
     * if it was not.
     *
     * @static
     * @access public
     * @return bool
     */
    public static function getOperationStatus()
    {
        return self::$lastOperationSuccessful;
    }
}

/**
 * Template for all Operations Objects
 *
 * This is the template for all the classes that are used for each operation.
 * Each and every one of them must have these two methods. The other methods will
 * be inherited from the abstract PaypalAPI class.
 *
 * @author Israel Ekpo <perfectvista@users.sourceforge.net>
 * @copyright Copyright 2007, Israel Ekpo
 * @license http://phppaypalpro.sourceforge.net/LICENSE.txt BSD License
 * @package PaypalBase
 */
interface OperationsTemplate
{
    /**
     * Calls the Paypal Web Service
     *
     * This method prepares the final message for the operation,
     * puts it in the right format and then invokes the __soapCall method
     * from the static soapClient. The name of the operation, the message, and the
     * input headers are passed to the __soapCall() method
     */
    public function execute();

    /**
     * Tells us if the Operation was Successful
     *
     * If the operation was successful, this method will return true,
     * otherwise it will return false
     *
     * @return bool
     */
    public function success();
}

/**
 * Abstract Class for Paypal API Core Methods
 *
 * This is an abstract class that contains 6 important methods that
 * are used by all the Operations classes.
 *
 * @abstract
 * @author Israel Ekpo <perfectvista@users.sourceforge.net>
 * @copyright Copyright 2007, Israel Ekpo
 * @license http://phppaypalpro.sourceforge.net/LICENSE.txt BSD License
 * @package PaypalBase
 */
abstract class PaypalAPI
{
    /**
     * Operation Status Registration
     *
     * This methods sets the lastOperationStatus attribute in the PaypalBase class.
     * If the last operation was a success, this attribute is set to true, otherwise
     * it will be set to false.
     *
     * @access private
     * @param bool $status
     */
    protected function registerLastOperationStatus($status)
    {
        PayPalBase::setLastOperationStatus($status);
    }

    /**
     * API Response Registration
     *
     * This method registers the response recieved from the Paypal Webservice.
     * As long as there were no errors it will be set. If it returns only one value
     * then it may be an integer, float or a string. However it is returning
     * multiple values then it may be an associative array or an object.
     *
     * @access private
     * @param mixed $APIResponse
     */
    protected function registerAPIResponse($APIResponse)
    {
        PayPalBase::setApiResponse($APIResponse);
    }

    /**
     * API Exception Registration
     *
     * This method registers any exception that occurs as a result of a soapFault
     * being thrown. If a soapFault object was throw while the Operations object
     * was calling the execute method, then this method will be invoked instead
     * of the registerAPIResponse() method.
     *
     * @access private
     * @param SoapFault $APIException
     */
    protected function registerAPIException($APIException)
    {
        PayPalBase::setAPISoapFault($APIException);
    }

    /**
     * Was the Operation Successful
     *
     * This method tells us whether or not the last operation was a success
     *
     * @access public
     * @return bool
     */
    public function success()
    {
        return PaypalBase::getOperationStatus();
    }

    /**
     * Returns the API Response
     *
     * The response from the Paypal Webservice can be accessed from this method
     *
     * @access public
     * @return mixed
     */
    public function getAPIResponse()
    {
    	return PaypalBase::getAPIResponse();
    }

    /**
     * Returns any SoapFault thrown
     *
     * Any exception that is thrown can be accessed from this method
     *
     * @access public
     * @return SoapFault
     */
    public function getAPIException()
    {
    	return PaypalBase::getAPIException();
    }
}

/**
 * Paypal Registrar
 *
 * The purpose of this class is to create SoapClients and SoapHeaders just before each operation
 * is carried out.
 *
 * @author Israel Ekpo <perfectvista@users.sourceforge.net>
 * @copyright Copyright 2007, Israel Ekpo
 * @license http://phppaypalpro.sourceforge.net/LICENSE.txt BSD License
 * @package PaypalBase
 */
final class PayPalRegistrar
{
	/**
	 * Creates the Static SoapClient
	 *
	 * The soap_version element is used to indicate whether you are using SOAP 1.1 or SOAP 1.2
	 * The exceptions option is a boolean value defining whether soap errors throw exceptions of type SoapFault.
	 *
	 * Setting the boolean trace option enables use of the methods  SoapClient->__getLastRequest,  SoapClient->__getLastRequestHeaders,
	 * SoapClient->__getLastResponse and  SoapClient->__getLastResponseHeaders
	 *
	 * The trace option is necessary if you will need to use the SoapClient->__getLastRequest() or SoapClient->__getLastReponse() methods
	 * later for debugging purposes. The connection_timeout option defines a timeout in seconds for the connection to the SOAP service.
	 * I have set it to 10 minutes.
	 *
	 * If for any reason you believe you software is going to take longer than usually, please feel free to
	 * adjust the maximum_execution_time value either during runtime or in the php.ini configuration file.
	 *
	 * @access public
	 */
    public static function registerSoapClient()
    {
        $clientOptions = array("soap_version" => SOAP_1_1, "exceptions" => true, "trace" => true, "connection_timeout " => API_CONNECTION_TIMEOUT);

        if (!class_exists('ActiveRecordModel', false))
        {
        	ClassLoader::import('application.model.ActiveRecordModel');
		}

		$config = ActiveRecordModel::getApplication()->getConfig();
		if ($config->get('PROXY_HOST'))
		{
			$clientOptions['proxy_host'] = $config->get('PROXY_HOST');
			$clientOptions['proxy_port'] = $config->get('PROXY_PORT');
		}
		PayPalBase::setSoapClient(new PayPalSoapClient(PayPalBase::$isLive ? LIVE_WSDL : SANDBOX_WSDL, $clientOptions));
    }

    /**
     * Creates the static SoapHeader
     *
     * This is the Custom Security Header passed to paypal.
     *
     * The username here is not the same as you account username and so is the password.
     * The subject is the payment on whose behalf you have been authorized to make the operation call.
     *
     * @param string $Username
     * @param string $Password
     * @param string $Signature
     * @param string $Subject
     */
    public static function registerSoapHeader($Username, $Password, $Signature, $Subject = '')
    {
        $Credentials     = new SoapVar(PayPalTypes::UserIdPasswordType($Username, $Password, $Signature, $Subject), SOAP_ENC_OBJECT);
        $headerNameSpace = 'urn:ebay:api:PayPalAPI';
        $headerName      = 'RequesterCredentials';
        $headerData      = array("Credentials" =>  $Credentials);
        $mustUnderstand  = true;

        PayPalBase::setSoapHeader(new SoapHeader($headerNameSpace, $headerName, $headerData, $mustUnderstand));
    }
}

/**
 * Website Payments Pro Operations Factory
 *
 * The purpose of this class is to provide a way of selecting any operation object
 * from just a single source. It also intializes the soapClient and soapHeaders that
 * are going to be used for any of the operations.
 *
 * @author Israel Ekpo <perfectvista@users.sourceforge.net>
 * @copyright Copyright 2007, Israel Ekpo
 * @license http://phppaypalpro.sourceforge.net/LICENSE.txt BSD License
 * @package PaypalBase
 */
final class WebsitePaymentsPro
{
    /**
     * Prepares the System for an Operation call
     *
     * This is when the SoapClient and SoapHeader static objects are actually created.
     *
     * @param string $Username The API user name
     * @param string $Password The password for the API call. Different from account password
     * @param string $Signature The signature for the 3-token authentication
     * @param string $Subject The person on whose behalf the operation is made
     * @uses PaypalRegistrar::registerSoapClient()
     * @uses PaypalRegistrar::registerSoapHeader()
     * @access public
     */
    public function prepare($Username, $Password, $Signature, $Subject='')
    {
       PayPalRegistrar::registerSoapClient();

       PayPalRegistrar::registerSoapHeader($Username, $Password, $Signature, $Subject);
    }

    /**
     * WebSite Payments Pro Operations Factory
     *
     * The name of the operation is passed to this method so that
     * it will return the right object to do the job. It is not case sensitive,
     * so you do not have to worry about the case of the letters when passing the
     * operation name to the method.
     *
     * @access public
     * @param string $operation This is case insensitive
     * @return mixed The Operation you wish to call
     */
    public function selectOperation($operation)
    {
        switch (strtolower(trim($operation)))
        {
            case 'dodirectpayment':
            {
                require_once('dodirectpayment.php');
                return new DoDirectPayment();
            }

            case 'docapture':
            {
                require_once('docapture.php');
                return new DoCapture();
            }

            case 'dovoid':
            {
                require_once('dovoid.php');
                return new DoVoid();
            }

            case 'setexpresscheckout':
            {
                require_once('setexpresscheckout.php');
                return new SetExpressCheckout();
            }

            case 'getexpresscheckoutdetails':
            {
                require_once('getexpresscheckoutdetails.php');
                return new GetExpressCheckoutDetails();
            }

            case 'doexpresscheckoutpayment':
            {
                require_once('doexpresscheckoutpayment.php');
                return new DoExpressCheckoutPayment();
            }

			case 'transactionsearch':
			{
				require_once('transactionsearch.php');
				return new TransactionSearch();
			}

			case 'gettransactiondetails':
			{
				require_once('gettransactiondetails.php');
				return new GetTransactionDetails();
			}

			case 'docreaterecurringpaymentsprofile':
			{
				require_once('docreaterecurringpaymentsprofile.php');
				return new DoCreateRecurringPaymentsProfile();
			}

			case 'managerecurringpaymentsprofilestatus':
			{
				require_once('managerecurringpaymentsprofilestatus.php');
				return new ManageRecurringPaymentsProfileStatus();
			}
		}
	}
}

/**
 *  Sometimes (on some servers) the SOAP response is not parsed properly for some reason
 *  Until a better solution is found, this rather ugly workaround will have to suffice
 *
 *  @todo Figure out why the SOAP response is not parsed properly on some servers
 */
class PaypalSoapClient extends SOAPClient
{

	public function __soapCall()
	{
		$args = func_get_args();
		call_user_func_array(array(get_parent_class($this), '__soapCall'), $args);
		//$this->saveDebug($this->__getLastRequest(), 'XML');
		return self::postProcessResponse();
	}
	
	private function saveDebug($data, $name='')
	{
		$filename = ClassLoader::getRealPath('cache.').'paypal_soap_calls.log';

		file_put_contents(
			$filename,
			(
				file_exists($filename)
					? file_get_contents($filename). "\n"
					: ''
			).
			date("Y-m-d H:i:s", time()).' '. $name.":\n".
			(!is_string($data) ? print_r($data, true) : $data)
		);
	}

	public function postProcessResponse()
	{
		$dom = new DomDocument();
		$xml = PayPalBase::getSoapClient()->__getLastResponse();
		$dom->loadXML($xml);
		$arr = self::xml2array($dom);
		$arr = $arr['SOAP-ENV:Envelope']['SOAP-ENV:Body'];
		$res = self::array2stdclass(array_shift($arr));
		//$this->saveDebug($res, 'Response');

		// a sick hack for reading the currency code...
		if (preg_match('/currencyID="([A-Z]{3})"/', $xml, $match))
		{
			$res->Currency = $match[1];
		}
		return $res;
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
                    if(!(trim($domnode->nodeValue) == ""))
					{
						$nodearray = $domnode->nodeValue;
					}
                break;
                case XML_ELEMENT_NODE:
                    if ($domnode->hasAttributes() )
                    {
                        $elementarray = array();
                        foreach ($domnode->attributes as $index => $domobj)
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