<?php

/**
 * Web Service Configuration File
 *
 * This file contains important constants and resources that are going to be
 * used throughout the entire phpPaypalPro system. This file contains the location of the 2 sets of WSDL documents.
 * The first one is used when making live transactions and the second one is used for Sanbox transactions
 * 
 * You cannot use your sandbox login info for the live transactions or vice versa.
 * 
 * @author Israel Ekpo <perfectvista@users.sourceforge.net>
 * @copyright Israel Ekpo, 2006-2007
 * @license http://phppaypalpro.sourceforge.net/LICENSE.txt BSD License
 * @version 0.2.1
 * @package PaypalBase
 * @filesource
 */

/**
 * Location of Live WSDL File
 *
 * LIVE WSDL Files; Downloaded for Live Paypal Server using wget
 * The names spaces from the .xsd files are imported into the WSDL file.
 *
 * https://www.paypal.com/wsdl/PayPalSvc.wsdl 
 * https://www.paypal.com/wsdl/eBLBaseComponents.xsd 
 * https://www.paypal.com/wsdl/CoreComponentTypes.xsd
 * 
 * @since version 0.1.1 This was modified in version 0.1.1 to this new location
 * @name LIVE_WSDL
 */
define('LIVE_WSDL', dirname(__file__) . '/wsdl/live/PayPalSvc.wsdl');

/**
 * Location of Sandbox WSDL File
 *
 * Sandbox WSDL Files; Downloaded from the Sandbox Server using wget
 * The namespaces from the .xsd files are imported into the WSDL file.
 *
 * https://www.sandbox.paypal.com/wsdl/PayPalSvc.wsdl
 * https://www.sandbox.paypal.com/wsdl/CoreComponentTypes.xsd
 * https://www.sandbox.paypal.com/wsdl/eBLBaseComponents.xsd
 * 
 * @since version 0.1.1 This was modified in version 0.1.1 to this new location
 * @name SANDBOX_WSDL
 */
define('SANDBOX_WSDL', dirname(__file__) . '/wsdl/sandbox/PayPalSvc.wsdl');

/**
 * API STATUS
 *
 * Whether or not we are running the script in live mode
 * 
 * @name API_IS_LIVE
 */
define('API_IS_LIVE', false);

/**
 * Connection TimeOut
 *
 * The connection timeout is the number of seconds
 * the service will wait before timeing out.
 * 
 * @name API_CONNECTION_TIMEOUT
 */
define('API_CONNECTION_TIMEOUT', 600);

$API_WSDL = (API_IS_LIVE)? LIVE_WSDL : SANDBOX_WSDL;

/**
 * WSDL File Location
 *
 * This file contains a list of all the operations available
 * in the webservice.
 * 
 * @name API_WSDL
 */
define('API_WSDL', $API_WSDL);

/**
 * Payload Schema Version
 *
 * This refers to the version of the request payload schema.
 * As of January 21, 2007, the version was 2.4
 * 
 * @name API_VERSION
 */

// define('API_VERSION', '2.4');

define('API_VERSION', '65.1'); // needed for recurring payment management calls.
?>
