<?php
namespace Dfe\AlphaCommerceHub;
use Df\API\Operation;
use Df\Payment\TM;
use Df\Payment\W\Event as Ev;
use Dfe\AlphaCommerceHub\API\Facade as F;
use Magento\Sales\Model\Order\Creditmemo as CM;
use Magento\Sales\Model\Order\Payment as OP;
use Magento\Sales\Model\Order\Payment\Transaction as T;
// 2017-10-25
/** @method Settings s() */
final class Method extends \Df\PaypalClone\Method {
	/**
	 * 2017-11-14
	 * Note 1.
	 * "The last 3 digits of every payment amount should be «000» in the test mode for Westpac":
	 * https://github.com/mage2pro/alphacommercehub/issues/17
	 * Note 2.
	 * SecurePay (another Australian payment service provider, I have already integrated it with Magento 2)
	 * has a similar rule: @see \Dfe\SecurePay\Method::amountFormat()
	 * https://github.com/mage2pro/securepay/blob/1.6.5/Method.php#L7-L26
	 * @override
	 * @see \Df\Payment\Method::amountFormat()
	 * @used-by _refund()
	 * @used-by charge()
	 * @used-by \Df\Payment\ConfigProvider::config()
	 * @used-by \Df\Payment\Operation::amountFormat()
	 * @used-by \Df\StripeClone\Method::_refund()
	 * @used-by \Df\StripeClone\Method::charge()
	 * @param float $a
	 * @return float|int|string
	 */
	function amountFormat($a) {
		$r = round($a * 1000); /** @var int $r */
		return !$this->test() ? $r : 1000 * round($r / 1000);
	}

	/**
	 * 2017-12-07
	 * "Implement an ability to capture a preauthorized bank card payment from the Magento backend
	 * (the `CapturePayment` transaction)": https://github.com/mage2pro/alphacommercehub/issues/60
	 * @override
	 * @see \Df\Payment\Method::canCapture()
	 * 1) @used-by \Magento\Sales\Model\Order\Payment::canCapture():
	 *		if (!$this->getMethodInstance()->canCapture()) {
	 *			return false;
	 *		}
	 * https://github.com/magento/magento2/blob/2.0.0/app/code/Magento/Sales/Model/Order/Payment.php#L246-L269
	 * https://github.com/magento/magento2/blob/2.2.1/app/code/Magento/Sales/Model/Order/Payment.php#L277-L301
	 * 2) @used-by \Magento\Sales\Model\Order\Payment::_invoice():
	 *		protected function _invoice() {
	 *			$invoice = $this->getOrder()->prepareInvoice();
	 *			$invoice->register();
	 *			if ($this->getMethodInstance()->canCapture()) {
	 *				$invoice->capture();
	 *			}
	 *			$this->getOrder()->addRelatedObject($invoice);
	 *			return $invoice;
	 *		}
	 * https://github.com/magento/magento2/blob/2.0.0/app/code/Magento/Sales/Model/Order/Payment.php#L509-L526
	 * https://github.com/magento/magento2/blob/2.2.1/app/code/Magento/Sales/Model/Order/Payment.php#L542-L560
	 * 3) @used-by \Magento\Sales\Model\Order\Payment\Operations\AbstractOperation::invoice():
	 *		protected function invoice(OrderPaymentInterface $payment) {
	 *			$invoice = $payment->getOrder()->prepareInvoice();
	 *			$invoice->register();
	 *			if ($payment->getMethodInstance()->canCapture()) {
	 *				$invoice->capture();
	 *			}
	 *			$payment->getOrder()->addRelatedObject($invoice);
	 *			return $invoice;
	 *		}
	 * https://github.com/magento/magento2/blob/2.0.0/app/code/Magento/Sales/Model/Order/Payment/Operations/AbstractOperation.php#L56-L75
	 * https://github.com/magento/magento2/blob/2.2.1/app/code/Magento/Sales/Model/Order/Payment/Operations/AbstractOperation.php#L59-L78
	 * @return bool
	 */
	final function canCapture() {return true;}

	/**
	 * 2017-12-06
	 * @override
	 * @see \Df\Payment\Method::canRefund()
	 * 1) @used-by \Magento\Sales\Model\Order\Payment::canRefund():
	 *		public function canRefund() {
	 *			return $this->getMethodInstance()->canRefund();
	 *		}
	 * https://github.com/magento/magento2/blob/2.0.0/app/code/Magento/Sales/Model/Order/Payment.php#L271-L277
	 * https://github.com/magento/magento2/blob/2.2.1/app/code/Magento/Sales/Model/Order/Payment.php#L303-L309
	 * 2) @used-by \Magento\Sales\Model\Order\Payment::refund()
	 *		$gateway = $this->getMethodInstance();
	 *		$invoice = null;
	 *		if ($gateway->canRefund()) {
	 *			<...>
	 *		}
	 * https://github.com/magento/magento2/blob/2.0.0/app/code/Magento/Sales/Model/Order/Payment.php#L617-L654
	 * https://github.com/magento/magento2/blob/2.2.1/app/code/Magento/Sales/Model/Order/Payment.php#L655-L698
	 * 3) @used-by \Magento\Sales\Model\Order\Invoice\Validation\CanRefund::canPartialRefund()
	 *		private function canPartialRefund(MethodInterface $method, InfoInterface $payment) {
	 *			return $method->canRefund() &&
	 *			$method->canRefundPartialPerInvoice() &&
	 *			$payment->getAmountPaid() > $payment->getAmountRefunded();
	 *		}
	 * https://github.com/magento/magento2/blob/2.2.1/app/code/Magento/Sales/Model/Order/Invoice/Validation/CanRefund.php#L84-L94
	 * It is since Magento 2.2: https://github.com/magento/magento2/commit/767151b4
	 * 2017-12-07
	 * @todo "An ability to refund a POLi Payments payment from the Magento 2 backend should be disabled
	 * because AlphaCommerceHub does not support it": https://github.com/mage2pro/alphacommercehub/issues/58
	 * @return bool
	 */
	function canRefund() {return true;}

	/**
	 * 2017-12-08
	 * @override
	 * @see \Df\Payment\Method::canVoid()
	 * @used-by \Magento\Sales\Model\Order\Payment::canVoid():
	 *		public function canVoid() {
	 *			if (null === $this->_canVoidLookup) {
	 *				$this->_canVoidLookup = (bool)$this->getMethodInstance()->canVoid();
	 *				if ($this->_canVoidLookup) {
	 *					$authTransaction = $this->getAuthorizationTransaction();
	 *					$this->_canVoidLookup = (bool)$authTransaction && !(int)$authTransaction->getIsClosed();
	 *				}
	 *			}
	 *			return $this->_canVoidLookup;
	 *		}
	 * https://github.com/magento/magento2/blob/2.0.0/app/code/Magento/Sales/Model/Order/Payment.php#L528-L543
	 * https://github.com/magento/magento2/blob/2.2.1/app/code/Magento/Sales/Model/Order/Payment.php#L562-L578
	 * @return bool
	 */
	function canVoid() {return true;}

	/**
	 * 2017-12-07
	 * "Implement an ability to capture a preauthorized bank card payment from the Magento backend
	 * (the `CapturePayment` transaction)": https://github.com/mage2pro/alphacommercehub/issues/60
	 * @override
	 * @see \Df\Payment\Method::charge()
	 * @used-by \Df\Payment\Method::authorize()
	 * @used-by \Df\Payment\Method::capture()
	 * @param bool|null $capture [optional]
	 */
	function charge($capture = true) {
		df_assert($capture);
		df_sentry_extra($this, 'Amount', $a = dfp_due($this)); /** @var float $a */
		df_sentry_extra($this, 'Need Capture?', df_bts($capture));
		/**
		 * 2017-11-11
		 * Despite of its name, @uses \Magento\Sales\Model\Order\Payment::getAuthorizationTransaction()
		 * simply returns the previous transaction, and it can be not only an `authorization` transaction,
		 * but a transaction of another type (e.g. `payment`) too.
		 * https://github.com/mage2pro/core/blob/3.4.2/StripeClone/Method.php#L124-L159
		 */
		$tPrev = $this->ii()->getAuthorizationTransaction(); /** @var T|false|null $tPrev */
		/**
		 * 2017-11-11
		 * The @see \Magento\Sales\Api\Data\TransactionInterface::TYPE_AUTH constant
		 * is absent in Magento < 2.1.0,
		 * but is present as @uses \Magento\Sales\Model\Order\Payment\Transaction::TYPE_AUTH
		 * https://github.com/magento/magento2/blob/2.0.0/app/code/Magento/Sales/Model/Order/Payment/Transaction.php#L37
		 * https://github.com/magento/magento2/blob/2.0.17/app/code/Magento/Sales/Api/Data/TransactionInterface.php
		 */
		df_assert($tPrev && T::TYPE_AUTH === $tPrev->getTxnType());
		$tm = df_tm($this); /** @var TM $tm */
		df_sentry_extra($this, 'Parent Transaction ID', $txnId = $tPrev->getTxnId()); /** @var string $txnId */
		df_sentry_extra($this, 'Charge ID', $tid = $this->tid()->i2e($txnId, true)); /** @var string $tid */
		$r = F::s()->post(['Transaction' => [
			/**
			 * 2017-12-06
			 * «Amount of the payment. Please see note below on formatting of amount for different currencies.»
			 * Numeric (14,3), conditional.
			 * «API Integration Guide(Nov 2017)» → «API Reference» → «Request Message»
			 * http://developer.alphacommercehub.com.au/docs/api-integration-guidenov-2017#request-message-
			 */
			'Amount' => $this->amountFormat($a)
			/**
			 * 2017-12-06
			 * «ISO 4217 Currency Code e.g. USD/GBP/EUR»
			 * String (3), conditional.
			 * «API Integration Guide(Nov 2017)» → «API Reference» → «Request Message»
			 * http://developer.alphacommercehub.com.au/docs/api-integration-guidenov-2017#request-message-
			 */
			,'Currency' => $tm->req('Currency')
			/**
			 * 2017-12-06
			 * «Internal ID assigned by the merchant to the transaction»
			 * String, conditional.
			 * «API Integration Guide(Nov 2017)» → «API Reference» → «Request Message»
			 * http://developer.alphacommercehub.com.au/docs/api-integration-guidenov-2017#request-message-
			 */
			,'MerchantTxnID' => $tm->req('MerchantTxnID')
		]], 'RefundPayment'); /** @var Operation $r */
		// 2017-01-12, 2017-12-07
		// I log only a response to the local log.
		// I log the both a request and its response to Sentry.
		dfp_report($this, $respA = $r->a(), df_caller_ff()); /** @var array(string => mixed) $respA */
		$this->iiaSetTRR($r->req(), $respA);
		// 2016-12-16
		// Система в этом сценарии по-умолчанию формирует идентификатор транзации как
		// «<идентификатор родительской транзации>-capture».
		// У нас же идентификатор родительской транзации имеет окончание «<-authorize»,
		// и оно нам реально нужно (смотрите комментарий к ветке else ниже),
		// поэтому здесь мы окончание «<-authorize» вручную подменяем на «-capture».
		$this->ii()->setTransactionId($this->tid()->e2i($tid, Ev::T_CAPTURE));
	}

	/**
	 * 2017-11-01
	 * @used-by \Dfe\AlphaCommerceHub\Charge::pCharge()
	 * @return string|null
	 */
	function option() {return $this->iia(self::$II_OPTION);}

	/**
	 * 2017-11-02
	 * «These are the URLs that should be used.
	 * For API calls:
	 * 		UAT: https://hubapiuat.alphacommercehub.com.au
	 * 		Prod: https://hubapi.alphacommercehub.com.au
	 * For HPP calls:
	 * 		UAT: https://hubuat.alphacommercehub.com.au/pp/
	 * 		Prod: https://hub.alphacommercehub.com.au/pp/
	 * »
	 * https://mage2.pro/t/4775/3
	 * 2017-12-02
	 * "Why does the «API Integration Guide(Nov 2017)» recommend to send the API requests
	 * to an unknown `hub.apcld.net` domain?" https://mage2.pro/t/5034
	 * @used-by \Dfe\AlphaCommerceHub\API\Client::urlBase()
	 * @used-by \Dfe\AlphaCommerceHub\Init\Action::redirectUrl()
	 * @param bool $forRedirection [optional]
	 * @return string
	 */
	function urlBase($forRedirection) {
		/** @var string $api */ /** @var string $path */
		list($api, $path) = $forRedirection ? ['', $this->s()->payPagePath()] : ['api', ''];
		return "https://hub{$api}{$this->test('uat', '')}.alphacommercehub.com.au{$path}";
	}

	/**
	 * 2017-12-06
	 * Note 1.
	 * "Implement an ability to refund a bank card payment from the Magento backend
	 * (the `RefundPayment` transaction)": https://github.com/mage2pro/alphacommercehub/issues/61
	 * Note 2.
	 * I have used @see \Df\StripeClone\Method::_refund() as a basic reference refund implementation:
	 * https://github.com/mage2pro/core/blob/3.4.1/StripeClone/Method.php#L372-L442
	 * 2017-12-07
	 * 1) @todo "An ability to refund a POLi Payments payment from the Magento 2 backend should be disabled
	 * because AlphaCommerceHub does not support it": https://github.com/mage2pro/alphacommercehub/issues/58
	 * 2) @todo "PayPal: implement the `RefundPayment` transaction":
	 * https://github.com/mage2pro/alphacommercehub/issues/50
	 * 3) @todo "Is it possible to make a partial refund with a `RefundPayment` API request?"
	 * https://mage2.pro/t/5079
	 * @override
	 * @see \Df\Payment\Method::_refund()
	 * @used-by \Df\Payment\Method::refund()
	 * @param float|null $amt
	 */
	protected function _refund($amt) {
		$ii = $this->ii(); /** @var OP $ii */
		/**
		 * 2016-03-17, 2017-11-11, 2017-12-06
		 * Despite of its name, @uses \Magento\Sales\Model\Order\Payment::getAuthorizationTransaction()
		 * simply returns the previous transaction,
		 * and it can be not only an `authorization` transaction,
		 * but a transaction of another type (e.g. `payment`) too:
		 * https://github.com/mage2pro/core/blob/3.4.1/StripeClone/Method.php#L381-L418
		 * It is exactly what we need,
		 * because the module can be set up to capture payments without a preliminary authorization.
		 */
		if ($tPrev = $ii->getAuthorizationTransaction() /** @var T|false $tPrev */) {
			$tm = df_tm($this); /** @var TM $tm */
			$tid = $this->tid()->i2e($tPrev->getTxnId(), true); /** @var string $tid */
			$cm = $ii->getCreditmemo(); /** @var CM|null $cm */
			if ('CC' === $tm->req('Method')) {
				$r = F::s()->post(['Transaction' => [
					/**
					 * 2017-12-06
					 * «Amount of the payment. Please see note below on formatting of amount for different currencies.»
					 * Numeric (14,3), conditional.
					 * «API Integration Guide(Nov 2017)» → «API Reference» → «Request Message»
					 * http://developer.alphacommercehub.com.au/docs/api-integration-guidenov-2017#request-message-
					 */
					'Amount' => $this->amountFormat($amt)
					/**
					 * 2017-12-06
					 * «ISO 4217 Currency Code e.g. USD/GBP/EUR»
					 * String (3), conditional.
					 * «API Integration Guide(Nov 2017)» → «API Reference» → «Request Message»
					 * http://developer.alphacommercehub.com.au/docs/api-integration-guidenov-2017#request-message-
					 */
					,'Currency' => $tm->req('Currency')
					/**
					 * 2017-12-06
					 * «Internal ID assigned by the merchant to the transaction»
					 * String, conditional.
					 * «API Integration Guide(Nov 2017)» → «API Reference» → «Request Message»
					 * http://developer.alphacommercehub.com.au/docs/api-integration-guidenov-2017#request-message-
					 */
					,'MerchantTxnID' => $tm->req('MerchantTxnID')
				]], 'RefundPayment'); /** @var Operation $r */
				// 2017-01-12, 2017-12-07
				// I log only a response to the local log.
				// I log the both a request and its response to Sentry.
				dfp_report($this, $respA = $r->a(), df_caller_ff()); /** @var array(string => mixed) $respA */
				$this->iiaSetTRR($r->req(), $respA);
				$ii->setTransactionId($this->tid()->e2i($tid, $cm ? Ev::T_REFUND : 'void'));
			}
		}
	}

	/**
	 * 2017-10-27
	 * «The amount fields are defined as fixed format 14,3 length.
	 * This allows for three exponent currencies such as OMR (Omani Rial) to be supported
	 * as well as supporting consistent formatting of zero exponent currencies such as JPY (Japanese Yen)
	 * as well as two exponent currencies such as USD (US Dollar).
	 * A merchant when submitting an amount
	 * should always submit assuming three minor units of the currency as is demonstrated:
	 * Amount		Currency	Submit As
	 * 250			JPY			250000
	 * 250.99		EUR			250990
	 * 250.999		OMR			250999»
	 * http://developer.alphacommercehub.com.au/docs/alphahpp-#handling-amounts
	 * @override
	 * @see \Df\Payment\Method::amountFactor()
	 * @used-by \Df\Payment\Method::amountParse()
	 * @return int
	 */
	protected function amountFactor() {return 1000;}

	/**
	 * 2017-10-25
	 * @override
	 * @see \Df\Payment\Method::amountLimits()
	 * @used-by \Df\Payment\Method::isAvailable()
	 * @return null
	 */
	protected function amountLimits() {return null;}

	/**
	 * 2017-11-01
	 * @override
	 * @see \Df\Payment\Method::iiaKeys()
	 * @used-by \Df\Payment\Method::assignData()
	 * @return string[]
	 */
	protected function iiaKeys() {return [self::$II_OPTION];}

	/**
	 * 2017-11-01 https://github.com/mage2pro/core/blob/2.12.17/Payment/view/frontend/web/withOptions.js#L56-L72
	 * @used-by iiaKeys()
	 * @used-by option()
	 */
	private static $II_OPTION = 'option';
}