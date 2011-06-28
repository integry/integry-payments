<?php

final class ManageRecurringPaymentsProfileStatus extends PaypalAPI
{
	private $tokenValue = '';

	private $ProfileID = '';

	private $Action = '';

	public function setParams($Token)
	{
		$this->tokenValue = $Token;
	}

	public function setRecurringProfileID($ProfileID)
	{
		$this->ProfileID = $ProfileID;
	}

	public function setAction($Action)
	{
		$this->Action = $Action;
	}


	public function execute()
	{
		$result = array();
		try {
			$this->apiMessage = array
				(
					'ManageRecurringPaymentsProfileStatusRequest' => array
					(
						'Version' => API_VERSION,
						'ManageRecurringPaymentsProfileStatusRequestDetails' => array(
							'ProfileID' => $this->ProfileID,
							'Action' => $this->Action
						)
					)
				);

			$this->apiMessage = array($this->apiMessage);
			$response = PayPalBase::getSoapClient()->__soapCall('ManageRecurringPaymentsProfileStatus', $this->apiMessage, null, PayPalBase::getSoapHeader());
			parent::registerAPIResponse($response);
			PaypalBase::setOperationStatus(true);
			if (false) // test
			{
				$response = new  stdClass();
				$response->Timestamp = '2011-01-28T15:28:24Z';
				$response->Ack = 'Success';
				$response->CorrelationID = '6f61538933b9';
				$response->Version = '65.1';
				$response->Build = '1704252';
				$r2 = new stdClass();
				$r2->ProfileID='I-XFD5FSRJAT6G';
				$response->ManageRecurringPaymentsProfileStatusResponseDetails = $r2;
			}
			$result = $response;
		}
		catch (SoapFault $Exception)
		{
			parent::registerAPIException($Exception);
			PaypalBase::setOperationStatus(false);
			return false;
		}


		return $result;
	}

}
?>
