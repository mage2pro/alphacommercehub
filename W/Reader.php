<?php
namespace Dfe\AlphaCommerceHub\W;
use Df\API\Operation as Op;
use Df\Payment\TM;
use Dfe\AlphaCommerceHub\API\Facade\PayPal as fPayPal;
# 2017-11-18 https://mage2.pro/tags/alphacommercehub-api-response
final class Reader extends \Df\Payment\W\Reader {
	/**
	 * 2017-11-18
	 * @override
	 * @see \Df\Payment\W\Reader::http()
	 * @used-by \Df\Payment\W\Reader::__construct()
	 * @return array(string => mixed)
	 */
	protected function http():array {
		$r = parent::http(); /** @var array(string => string) $r */
		# 2017-11-21
		# "A `SuccessURL` response to a PayPal payment is not a JSON
		# in contrary to other `SuccessURL` responses, so I should handle this special case":
		# https://github.com/mage2pro/alphacommercehub/issues/46
		return !isset($r['data']) ? $r : df_json_decode($r['data']);
	}

	/**
	 * 2017-11-22 "A `SuccessURL` response to a PayPal payment": https://mage2.pro/t/4953
	 * 2017-12-01
	 * "I should save the order's MerchantTxnID to the customer's PHP session
	 * before redirecting the customer to an AlphaCommerceHub's hosted payment page,
	 * because AlphaCommerceHub does not provide it in a `SuccessURL` request for a PayPal payment":
	 * https://github.com/mage2pro/alphacommercehub/issues/62
	 * 2017-12-08
	 * 1) "PayPal: implement the `PaymentStatus` transaction": https://github.com/mage2pro/alphacommercehub/issues/48
	 * 2) "A PayPal's `PaymentStatus` API request, and a response to it": https://mage2.pro/t/5120
	 * 3) "A PayPal's `CapturePayment` API request, and a response to it": https://mage2.pro/t/5127
	 * @override
	 * @see \Df\Payment\W\Reader::reqFilter()
	 * @used-by \Df\Payment\W\Reader::__construct()
	 * @param array(string => mixed) $r
	 * @return array(string => mixed)
	 */
	protected function reqFilter(array $r):array {
		if (isset($r['token'], $r['PayerID'])) {
			$tm = df_tm(dfpm(df_order_last())); /** @var TM $tm */
			$f = fPayPal::s(); /** @var fPayPal $s */
			$s = $f->status($tm->req(['MerchantTxnID', 'Amount', 'Currency'])); /** @var Op $s */
			$c = $f->capture($s); /** @var Op $c */
			$r = $s->a(['MethodResult']) + df_extend($s->a(), $c->a());
		}
		return $r;
	}
}