<?php
namespace Dfe\AlphaCommerceHub\W;
use Df\Payment\W\Exception\Critical;
// 2017-11-18 https://mage2.pro/tags/alphacommercehub-api-response
final class Reader extends \Df\Payment\W\Reader {
	/**
	 * 2017-11-22
	 * "PayPal: implement the `PaymentStatus` transaction":
	 * https://github.com/mage2pro/alphacommercehub/issues/48
	 * @override
	 * @see \Df\Payment\W\Reader::_construct()
	 * @used-by \Df\Payment\W\Reader::__construct()
	 */
	protected function _construct() {
		// 2017-11-22 "A `SuccessURL` response to a PayPal payment": https://mage2.pro/t/4953
		if ($this->r('token') && $this->r('PayerID')) {
			/**
			 * 2017-11-22
			 * 1) "The PayPal's `PaymentStatus` transaction is undocumented":
			 * https://github.com/mage2pro/alphacommercehub/issues/52
			 * 2) "Where are the request and response parameters specification
			 * for the PayPal's `PaymentStatus` transaction?" https://mage2.pro/t/4991
			 */
			throw new Critical($this->m(), $this,
				"A `SuccessURL` response to a PayPal payment is not yet handled, "
				."because the PayPal's `PaymentStatus` transaction is undocumented: "
				."https://github.com/mage2pro/alphacommercehub/issues/52"
			);
		}
	}

	/**
	 * 2017-11-18
	 * @override
	 * @see \Df\Payment\W\Reader::http()
	 * @used-by \Df\Payment\W\Reader::__construct()
	 * @return array(string => mixed)
	 */
	protected function http() {
		$r = parent::http(); /** @var array(string => string) $r */
		/**
		 * 2017-11-21
		 * "A `SuccessURL` response to a PayPal payment is not a JSON
		 * in contrary to other `SuccessURL` responses, so I should handle this special case":
		 * https://github.com/mage2pro/alphacommercehub/issues/46
		 */
		return !isset($r['data']) ? $r : df_json_decode($r['data']);
	}
}