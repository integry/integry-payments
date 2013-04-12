<?php
error_reporting(E_ALL); ini_set('display_errors', 'On');
include_once(dirname(__file__) . '/../abstract/ExternalPayment.php');

/**
 *
 * @package library.payment.method
 * @author Integry Systems
 */
class Mellat extends ExternalPayment
{
	private function getAmountCacheFile($transactionID)
	{
		$dir = ClassLoader::getRealPath('cache.mellat');
		if (!file_exists($dir))
		{
			mkdir($dir);
		}

		return $dir . '/' . $transactionID . '.php';
	}

	public function isPostRedirect()
	{
		return true;
	}

	public function getUrl()
	{
		return 'https://pgw.bpm.bankmellat.ir/pgwchannel/startpay.mellat';
	}

	private function getTransactionParams()
	{
		return array(
			'terminalId' => '677748',
			'userName' => 'snowa',
			'userPassword' => '3908'
			);
	}

	private function getSoapClient()
	{
		return new soapclient('https://pgws.bpm.bankmellat.ir/pgwchannel/services/pgw?wsdl');
	}

	public function getPostParams()
	{
		$parameters = array_merge($this->getTransactionParams(),
			array(
				'orderId' => $this->details->invoiceID->get() . '0' . str_replace('0', '1', str_pad(rand(1, 500000), 6, '1')),
				'amount' => $this->details->amount->get(),
				'localDate' => date('Ymd'),
				'localTime' => date('His'),
				'additionalData' => $this->details->amount->get(),
				'callBackUrl' => $this->notifyUrl,
				'payerId' => 0));

		// Call the SOAP method
		$result = $this->getSoapClient()->bpPayRequest($parameters);

		if (!$result || (substr($result->return, 0, 1) != '0'))
		{
			//var_dump($result); var_dump('Parameters: ', $parameters); exit;
			return;
		}

		list($status, $refId) = explode(',', $result->return);

		file_put_contents($this->getAmountCacheFile($parameters['orderId']), $this->details->amount->get());

		return array('RefId' => $refId);
	}

	public function notify($requestArray)
	{
		$this->saveDebug($requestArray);

		if ($requestArray['ResCode'] > 0)
		{
			return new TransactionError('Error (code: ' . $requestArray['ResCode'] . ')');
		}

//var_dump($requestArray);

		// verify request
		$parameters = array_merge($this->getTransactionParams(),
			array(
				'orderId' => $requestArray['SaleOrderId'],
				'saleOrderId' => $requestArray['SaleOrderId'],
				'saleReferenceId' => $requestArray['SaleReferenceId']
			));

		$result = $this->getSoapClient()->bpVerifyRequest($parameters);

//		var_dump($result);

		if (!$result || (substr($result->return, 0, 1) != '0'))
		{
			return;
		}

		$result = $this->getSoapClient()->bpSettleRequest($parameters);

//		var_dump($result);	exit;

		//$amount = array_shift(array_shift(explode('_', $requestArray['SaleOrderId']))) / 100;
		$amount = file_get_contents($this->getAmountCacheFile($requestArray['SaleOrderId']));

		$result = new TransactionResult();
		$result->gatewayTransactionID->set($requestArray['saleReferenceId']);
		$result->amount->set($amount);
		$result->currency->set($this->getValidCurrency(null));
		$result->rawResponse->set($requestArray);
		$result->setTransactionType(TransactionResult::TYPE_SALE);

		return $result;
	}

	public function getOrderIdFromRequest($requestArray)
	{
		$parts = explode('0', $requestArray['SaleOrderId']);
		array_pop($parts);
		return implode('', $parts);
	}

	public function getReturnUrlFromRequest($requestArray)
	{
		return $requestArray['complete_url'];
	}

	public function isHtmlResponse()
	{
		return false;
	}

	public function getValidCurrency($currentCurrencyCode)
	{
		//return 'USD';
		return 'IRR';
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