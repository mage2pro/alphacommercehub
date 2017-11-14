<?php
namespace Dfe\AlphaCommerceHub;
// 2017-10-25
final class Method extends \Df\PaypalClone\Method {
	/**
	 * 2017-11-14
	 * "The last amounts digit should be 0 for all currencies except JPY and OMR":
	 * https://github.com/mage2pro/alphacommercehub/issues/14
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
		$r = parent::amountFormat($a); /** @var int $r */
		return in_array($this->cPayment(), ['JPY', 'OMR']) ? $r : 10 * round($r / 10);
	}
	
	/**
	 * 2017-11-01
	 * @used-by \Dfe\AlphaCommerceHub\Charge::pCharge()
	 * @return string|null
	 */
	function option() {return $this->iia(self::$II_OPTION);}

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
	 * http://alpha.pwstaging.com.au/docs/alphahpp#handling-amounts
	 * @override
	 * @see \Df\Payment\Method::amountFactor()
	 * @used-by \Df\Payment\Method::amountFormat()
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