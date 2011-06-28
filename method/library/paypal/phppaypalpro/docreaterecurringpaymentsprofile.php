<?php

final class DoCreateRecurringPaymentsProfile extends PaypalAPI
{
	private $tokenValue = '';

	private $recurringItems = array();

	public function setParams($Token)
	{
		$this->tokenValue = $Token;
	}

	public function setRecurringItems($recurringItems)
	{
		$this->recurringItems = $recurringItems;
	}

	public function execute()
	{
		$mapping = array (
			RecurringProductPeriod::TYPE_PERIOD_DAY => 'Day',
			RecurringProductPeriod::TYPE_PERIOD_WEEK => 'Week',
			RecurringProductPeriod::TYPE_PERIOD_MONTH => 'Month',
			RecurringProductPeriod::TYPE_PERIOD_YEAR => 'Year'
		);

		$result = array();
		try
		{
			foreach ($this->recurringItems as $recurringItem)
			{
				$quantity = $recurringItem->orderedItem->get()->count->get();

				$RecurringPaymentsProfileDetails = array('BillingStartDate' => PayPalTypes::dateTimeType());
				$periodLength = $recurringItem->periodLength->get();
				$periodType = $recurringItem->periodType->get();
				$rebillCount = $recurringItem->rebillCount->get();
				$setupPrice = $recurringItem->setupPrice->get();
				$periodPrice = $recurringItem->periodPrice->get();

				$ScheduleDetails = array
				(
					'Description' => PayPalTypes::RecurringItemDescription($recurringItem),
					'PaymentPeriod' => array
					(
						'BillingPeriod' => $mapping[$periodType],
						'BillingFrequency' => $periodLength,
						'TotalBillingCycles' => $rebillCount,
						'Amount' => PayPalTypes::BasicAmountType($periodPrice * $quantity)
					),
					'AutoBillOutstandingAmount' => 'NoAutoBill'
				);

				if ($rebillCount > 0)
				{
					$ScheduleDetails['PaymentPeriod']['TotalBillingCycles'] = $rebillCount;
				}

				if ($setupPrice > 0)
				{
					$ScheduleDetails['ActivationDetails'] =  array(
						'InitialAmount' => PayPalTypes::BasicAmountType($setupPrice * $quantity),
						'FailedInitialAmountAction'=> 'ContinueOnFailure'
					);
				}

				$this->apiMessage = array
				(
					'CreateRecurringPaymentsProfileRequest' => array
					(
						'Version' => API_VERSION,
						'CreateRecurringPaymentsProfileRequestDetails' => array
						(
							'Token' => $this->tokenValue,
							'RecurringPaymentsProfileDetails' => $RecurringPaymentsProfileDetails,
							'ScheduleDetails' => $ScheduleDetails
						)
					)
				);
				$this->apiMessage = array($this->apiMessage);
				
				$response = PayPalBase::getSoapClient()->__soapCall('CreateRecurringPaymentsProfile', $this->apiMessage, null, PayPalBase::getSoapHeader());
				parent::registerAPIResponse($response);
				PaypalBase::setOperationStatus(true);

				if ($response->Ack == 'Success')
				{
					$result[$recurringItem->getID()] = array(
						'Timestamp'=>$response->Timestamp,
						'Ack'=>$response->Ack,
						'Version' => $response->Version,
						'Build' => $response->Build,
						'CreateRecurringPaymentsProfileResponseDetails' => array(
							'ProfileID' => $response->CreateRecurringPaymentsProfileResponseDetails->ProfileID,
							'ProfileStatus' => $response->CreateRecurringPaymentsProfileResponseDetails->ProfileStatus
						)
					);
				}
			}
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
