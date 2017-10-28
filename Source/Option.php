<?php
namespace Dfe\AlphaCommerceHub\Source;
/**
 * 2017-10-28
 * [AlphaCommerceHub] The payment method codes: https://mage2.pro/t/4809
 * [AlphaCommerceHub]
 * Which codes are used for the Visa Checkout, UnionPay Online Payments, and ApplePay payment options?
 * https://mage2.pro/t/4811
 * 
 * @method static Option s()
 * @used-by \Dfe\AlphaCommerceHub\Settings::options()
 */
final class Option extends \Df\Config\Source {
	/**
	 * 2017-10-28
	 * @override
	 * @see \Df\Config\Source::map()
	 * @used-by \Df\Config\Source::toOptionArray()
	 * @return array(string => string)
	 */
	protected function map() {return df_sort_names(array_map(
		function(array $a) {return $a['title'];}, df_module_json($this, 'options')
	));}
}