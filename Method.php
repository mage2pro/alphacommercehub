<?php
namespace Dfe\AlphaCommerceHub;
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