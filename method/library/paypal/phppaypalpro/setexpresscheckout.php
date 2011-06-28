<?php

/**
 * SetExpressCheckout Object
 *
 * This class is used to perform the SetExpressCheckout operation
 *
 * @author Israel Ekpo <perfectvista@users.sourceforge.net>
 * @copyright Copyright 2007, Israel Ekpo
 * @license http://phppaypalpro.sourceforge.net/LICENSE.txt BSD License
 * @version 0.2.1
 * @package ExpressCheckout
 * @filesource
 */


/**
 * Used to invoke the SetExpressCheckout Operation
 *
 * @author Israel Ekpo <perfectvista@users.sourceforge.net>
 * @copyright Copyright 2007, Israel Ekpo
 * @license http://phppaypalpro.sourceforge.net/LICENSE.txt BSD License
 * @package ExpressCheckout
 */
final class SetExpressCheckout extends PaypalAPI implements OperationsTemplate
{
    /**
     * Message Sent to the Webservice
     *
     * @var array
     * @access private
     */
    private $apiMessage;


	private $recurringItems = array();

	public function setRecurringItems($recurringItems)
	{
		$this->recurringItems = $recurringItems;
	}

    /**
     * Prepares the message to be sent
     *
     * This method prepares the message to be sent to the
     * Paypal Webservice
     *
     * @access public
     *
     * @param double $OrderTotal
     * @param string $ReturnURL
     * @param string $CancelURL
     * @param string $PaymentAction Sale or Order
     */
    public function setParams($OrderTotal, $ReturnURL, $CancelURL, $PaymentAction, $currencyID)
    {
        $this->apiMessage = PayPalTypes::SetExpressCheckoutRequestDetailsType($OrderTotal, $ReturnURL, $CancelURL, $PaymentAction, $currencyID);
    }

    /**
     * Executes the Operation
     *
     * Prepares the final message and the calls the Webservice operation. If it is successfull the response is registered
     * and the OperationStatus is set to true, otherwise the Operation status will be set to false and an Exception of the type
     * soapFault will be registered instead.
     *
     * @access public
     */
	public function execute()
	{
		try
		{
			$this->apiMessage['Version'] = API_VERSION;
			$this->apiMessage['SetExpressCheckoutRequestDetails'] = $this->apiMessage;
			$this->apiMessage = array('SetExpressCheckoutRequest' => $this->apiMessage);
			if (count($this->recurringItems))
			{
				$this->apiMessage['SetExpressCheckoutRequest']['SetExpressCheckoutRequestDetails']['BillingAgreementDetails'] = array();
				foreach($this->recurringItems as $recurringItem)
				{
					$this->apiMessage['SetExpressCheckoutRequest']['SetExpressCheckoutRequestDetails']['BillingAgreementDetails'][] = PayPalTypes::BillingAgreementDetailsType($recurringItem);
				}
			}
			$this->apiMessage = array($this->apiMessage);

			parent::registerAPIResponse(PayPalBase::getSoapClient()->__soapCall('SetExpressCheckout', $this->apiMessage, null, PayPalBase::getSoapHeader()));
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
