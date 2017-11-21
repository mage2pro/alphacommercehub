<?php
namespace Dfe\AlphaCommerceHub\W;
// 2017-11-18 https://mage2.pro/tags/alphacommercehub-api-response
final class Reader extends \Df\Payment\W\Reader {
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