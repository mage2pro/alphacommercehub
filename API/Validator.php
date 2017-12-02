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
	 * @see \Df\API\Exception::long()
	 * @used-by \Df\API\Client::_p()
	 * @return string
	 */
	function long() {return "[{$this->code()}] {$this->result('ResponseMessage')}";}

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
	 * @used-by long()
	 * @used-by valid()
	 * @return string
	 */
	private function code() {return $this->result('ResponseCode');}

	/**
	 * 2017-12-02
	 * @used-by code()
	 * @used-by long(
	 * @param string $k
	 * @return string|null
	 */
	private function result($k) {return dfa_deep($this->r(), "Result/$k");}
}