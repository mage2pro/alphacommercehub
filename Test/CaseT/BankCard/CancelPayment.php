<?php
namespace Dfe\AlphaCommerceHub\Test\CaseT\BankCard;
use Df\API\Operation as Op;
use Dfe\AlphaCommerceHub\API\Facade as F;
/**
 * 2017-12-08
 * 1) "Implement an ability to void a preauthorized bank card payment from the Magento backend
 * (the `CancelPayment` transaction)" https://github.com/mage2pro/alphacommercehub/issues/69
 * 2) "A `CancelPayment` API request for a preauthorized bank card payment, and a response to it":
 * https://mage2.pro/t/5113
 */
final class CancelPayment extends \Dfe\AlphaCommerceHub\Test\CaseT {
	/** @test 2017-12-08 */
	function t00() {}

	/** 2017-12-08 */
	function t01() {
		/** @var Op $r */
		$r = F::s()->post(['Transaction' => [
			/**
			 * 2017-12-06
			 * «Amount of the payment. Please see note below on formatting of amount for different currencies.»
			 * Numeric (14,3), conditional.
			 * «API Integration Guide(Nov 2017)» → «API Reference» → «Request Message»
			 * http://developer.alphacommercehub.com.au/docs/api-integration-guidenov-2017#request-message-
			 */
			'Amount' => '999000'
			/**
			 * 2017-12-06
			 * «ISO 4217 Currency Code e.g. USD/GBP/EUR»
			 * String (3), conditional.
			 * «API Integration Guide(Nov 2017)» → «API Reference» → «Request Message»
			 * http://developer.alphacommercehub.com.au/docs/api-integration-guidenov-2017#request-message-
			 */
			,'Currency' => 'AUD'
			/**
			 * 2017-12-06
			 * «Internal ID assigned by the merchant to the transaction»
			 * String, conditional.
			 * «API Integration Guide(Nov 2017)» → «API Reference» → «Request Message»
			 * http://developer.alphacommercehub.com.au/docs/api-integration-guidenov-2017#request-message-
			 */
			,'MerchantTxnID' => '1208L.768'
		]], df_class_l($this));
		echo df_json_encode($r->req());
		echo $r->j();
	}
}