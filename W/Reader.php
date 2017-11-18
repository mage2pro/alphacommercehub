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
	protected function http() {return df_json_decode(dfa(parent::http(), 'data', '[]'));}
}