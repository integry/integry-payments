<?php

include_once(dirname(__file__) . '/../abstract/ExternalPayment.php');

/**
 *
 * @package library.payment.method
 * @author Integry Systems
 */
class VCS extends ExternalPayment
{
	public function getUrl()
	{
		return 'https://www.vcs.co.za/vvonline/ccform.asp';
	}

	public function isPostRedirect()
	{
		return true;
	}

	public function getPostParams()
	{
		$params = array();

		// VCS Terminal ID allocated by VCS
		$params['p1'] = $this->getConfigValue('account');

		// Unique Transaction Reference Number
		$params['p2'] = $this->details->invoiceID->get();

		// Description of Goods is a short description generated by you.
		$params['p3'] = $this->getConfigValue('description');

		// Transaction Amount with a decimal point, calculated by you.
		$params['p4'] = $this->details->amount->get();

		// ISO Currency, i.e. zar, usd, gbp, etc. If you do not send a currency then our system will default to the merchant’s default currency.
		$params['p5'] = $this->details->currency->get();

		// URL for Cancelled Transactions.
		$params['p10'] = $this->siteUrl;

		// customer information
		$params['p'] = $this->details->email->get();

		return $params;
	}

	public function notify($requestArray)
	{
		if (empty($requestArray['test']))
		{
			echo '<CallBackResponse>Accepted</CallBackResponse>';
		}

		if (substr($requestArray['p3'], -8) == 'APPROVED')
		{
			$result = new TransactionResult();
			$result->gatewayTransactionID->set($requestArray['p2']);
			$result->amount->set($requestArray['p6']);
			$result->currency->set('ZAR');
			$result->rawResponse->set($requestArray);
			$result->setTransactionType(TransactionResult::TYPE_SALE);

			return $result;
		}
	}

	public function getOrderIdFromRequest($requestArray)
	{
		return $requestArray['p2'];
	}

	public function isHtmlResponse()
	{
		return true;
	}

	public function getValidCurrency($currentCurrencyCode)
	{
		return 'ZAR';
	}

	public function isVoidable()
	{
		return false;
	}

	public function void()
	{
		return false;
	}
}

?>