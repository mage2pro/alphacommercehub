<?php
namespace Dfe\AlphaCommerceHub\T\CaseT\BankCard;
use Dfe\AlphaCommerceHub\API\Facade as F;
/**
 * 2017-12-06
 * "Implement an ability to refund a bank card payment from the Magento backend
 * (the `RefundPayment` transaction)": https://github.com/mage2pro/alphacommercehub/issues/61
 */
final class RefundPayment extends \Dfe\AlphaCommerceHub\T\CaseT {
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
			'Amount' => '161000'
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
			,'MerchantTxnID' => '1203L.761'
			/**
			 * 2017-12-06
			 * «The payment method used. See Payment Methods.»
			 * String (2), conditional.
			 * «API Integration Guide(Nov 2017)» → «API Reference» → «Request Message»
			 * http://developer.alphacommercehub.com.au/docs/api-integration-guidenov-2017#request-message-
			 */
			,'Method' => 'CC'
		]], df_class_l($this));
	}
}