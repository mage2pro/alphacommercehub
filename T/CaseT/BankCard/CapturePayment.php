<?php
namespace Dfe\AlphaCommerceHub\T\CaseT\BankCard;
use Df\API\Operation as Op;
use Dfe\AlphaCommerceHub\API\Facade as F;
/**
 * 2017-12-06
 * "Implement an ability to capture a preauthorized bank card payment from the Magento backend
 * (the `CapturePayment` transaction)": https://github.com/mage2pro/alphacommercehub/issues/60
 * 2017-12-07
 * 1) "A `CapturePayment` API request for a preauthorized bank card payment, and a response to it":
 * https://mage2.pro/t/5107
 * 2) "Why does AlphaCommerceHub allow to capture more money from a customer than it was preauthorized?"
 * https://mage2.pro/t/5104
 * 3) "Why does AlphaCommerceHub allow to capture zero amounts?" https://mage2.pro/t/5105
 * 4) "Why does AlphaCommerceHub allow to capture some money without a currency specified?"
 * https://mage2.pro/t/5106
 * 5) "Is it possible to capture a preauthorized bank card payment partially?" https://mage2.pro/t/5102
 * 6) "Is it possible to capture a preauthorized bank card payment partially multiple times?"
 * https://mage2.pro/t/5103
 * 7) "Neither the documentation nor the API Explorer provides a sample of the `CapturePayment` transaction
 * for a bank card payment" https://mage2.pro/t/5075
 */
final class CapturePayment extends \Dfe\AlphaCommerceHub\T\CaseT {
	/** 2017-12-06 */
	function t00() {}

	/** @test 2017-12-06 */
	function t01() {
		$r = F::s()->post(['Transaction' => [
			/**
			 * 2017-12-06
			 * «Amount of the payment. Please see note below on formatting of amount for different currencies.»
			 * Numeric (14,3), conditional.
			 * «API Integration Guide(Nov 2017)» → «API Reference» → «Request Message»
			 * http://developer.alphacommercehub.com.au/docs/api-integration-guidenov-2017#request-message-
			 */
			'Amount' => '999999' //'161000'
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
			,'MerchantTxnID' => '1206L.767'
			/**
			 * 2017-12-06
			 * «The payment method used. See Payment Methods.»
			 * String (2), conditional.
			 * «API Integration Guide(Nov 2017)» → «API Reference» → «Request Message»
			 * http://developer.alphacommercehub.com.au/docs/api-integration-guidenov-2017#request-message-
			 */
			//,'Method' => 'CC'
		]], df_class_l($this)); /** @var Op $r */
		echo df_json_encode($r->req());
		echo $r->j();
	}
}