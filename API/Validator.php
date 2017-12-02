<?php
namespace Dfe\AlphaCommerceHub\API;
/**
 * 2017-12-02
 * «API Integration Guide(Nov 2017)» → «Response Codes»
 * http://developer.alphacommercehub.com.au/docs/api-integration-guidenov-2017#response-codes
 * @used-by \Dfe\AlphaCommerceHub\API\Client::responseValidatorC()
 */
final class Validator extends \Df\API\Response\Validator {
	/**
	 * 2017-10-08
	 * @override
	 * @see \Df\API\Exception::short()
	 * @used-by \Df\API\Client::_p()
	 * @return string
	 */
	function short() {
		/** @var string|null $c */  /** @var string $m */
		list($c, $m) = [$this->code(), $this->result('ResponseMessage')];
		return !$c ? $m : "[$c] $m";
	}

	/**
	 * 2017-12-02
	 * @override
	 * @see \Df\API\Response\Validator::valid()
	 * @used-by \Df\API\Response\Validator::validate()
	 * @return bool
	 */
	function valid() {return '1000' === strval($this->code());}

	/**
	 * 2017-12-02
	 * 2017-13-03
	 * `ResponseCode` can be absent despite the documentation says it is a mandatory response parameter:
	 * "The AlphaCommerceHub's response «No Processor flow found for Transaction type: PaymentStatus»
	 * to a PayPal's `PaymentStatus` transaction does not contain a `ResponseCode`
	 * despite the documentation says it is a mandatory parameter":
	 * https://mage2.pro/t/5041
	 * @used-by short()
	 * @used-by valid()
	 * @return string|null
	 */
	private function code() {return $this->result('ResponseCode');}

	/**
	 * 2017-12-02
	 * @used-by code()
	 * @used-by short(
	 * @param string $k
	 * @return string|null
	 */
	private function result($k) {return dfa_deep($this->r(), "Result/$k");}
}