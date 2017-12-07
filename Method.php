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
			$pid = $tm->req('MerchantTxnID'); /** @var string $pid */
			$cm = $ii->getCreditmemo(); /** @var CM|null $cm */
			if ('CC' === $tm->req('Method')) {
				/** @var Operation $r */
				$r = F::s()->post(['Transaction' => ['MerchantTxnID' => $pid]], 'RefundPayment');
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