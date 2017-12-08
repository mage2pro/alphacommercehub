<?php
namespace Dfe\AlphaCommerceHub\API\Facade;
use Df\API\Operation as Op;
// 2017-12-08
/** @method static $this s() */
final class BankCard extends \Dfe\AlphaCommerceHub\API\Facade {
	/**
	 * 2017-12-08
	 * @used-by \Dfe\AlphaCommerceHub\Method::_refund()
	 * @param float $a
	 * @return Op
	 */
	function cancel($a) {return $this->op($a);}

	/**
	 * 2017-12-08
	 * @used-by \Dfe\AlphaCommerceHub\Method::charge()
	 * @param float $a
	 * @return Op
	 */
	function capture($a) {return $this->op($a);}

	/**
	 * 2017-12-08
	 * @used-by \Dfe\AlphaCommerceHub\Method::_refund()
	 * @param float $a
	 * @return Op
	 */
	function refund($a) {return $this->op($a);}

	/**
	 * 2017-12-08
	 * 1) `Amount`:
	 * 		«Amount of the payment. Please see note below on formatting of amount for different currencies.»
	 *		Numeric (14,3), conditional.
	 * 2) `Currency`: «ISO 4217 Currency Code e.g. USD/GBP/EUR», string(3), conditional.
	 * 3) `MerchantTxnID`: «Internal ID assigned by the merchant to the transaction», string, conditional.
	 * «API Integration Guide(Nov 2017)» → «API Reference» → «Request Message»
	 * http://developer.alphacommercehub.com.au/docs/api-integration-guidenov-2017#request-message-
	 * @used-by cancel()
	 * @used-by capture()
	 * @used-by refund()
	 * @param float $a
	 * @return Op
	 */
	private function op($a) {return $this->post(['Transaction' =>
		['Amount' => dfpm($this)->amountFormat($a)] + df_tm($this)->req(['Currency', 'MerchantTxnID'])
	], ucfirst(df_caller_f()) . 'Payment');}
}