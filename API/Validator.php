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
	 */
	function short():string {
		list($c, $m) = [$this->code(), $this->result('ResponseMessage')]; /** @var string $c */  /** @var string $m */
		return !$c ? $m : "[$c] $m";
	}

	/**
	 * 2017-12-02
	 * @override
	 * @see \Df\API\Response\Validator::valid()
	 * @used-by \Df\API\Client::_p()
	 */
	function valid():bool {return '1000' === $this->code();}

	/**
	 * 2017-12-02
	 * 2017-13-03
	 * `ResponseCode` can be absent despite the documentation says it is a mandatory response parameter:
	 * "The AlphaCommerceHub's response «No Processor flow found for Transaction type: PaymentStatus»
	 * to a PayPal's `PaymentStatus` transaction does not contain a `ResponseCode`
	 * despite the documentation says it is a mandatory parameter":
	 * https://mage2.pro/t/5041
	 * @used-by self::short()
	 * @used-by self::valid()
	 */
	private function code():string {return $this->result('ResponseCode');}

	/**
	 * 2017-12-02
	 * @used-by self::code()
	 * @used-by self::short()
	 */
	private function result(string $k):string {return strval($this->r("Result/$k"));}
}