<?php
namespace Dfe\AlphaCommerceHub\T\CaseT\PayPal;
/**
 * 2017-12-02
 * Note 1. «API Explorer» → «POST PaymentStatus»
 * http://developer.alphacommercehub.com.au/api-explorer/10/paypal---payment-status
 * Note 2. "Where is the response parameters specification for the PayPal's `PaymentStatus` transaction?":
 * https://mage2.pro/t/4991/4
 * Note 3.
 * "How can I know `MerchantID`
 * (which is required for the `PaymentStatus` and `CapturePayment` PayPal transactions)
 * if a `SuccessURL` response to a PayPal payment does not provide it?":
 * https://mage2.pro/t/5017
 * Note 4.
 * "Why a `SuccessURL` response to a PayPal payment does not contain any information about the buyer?"
 * https://mage2.pro/t/4985
 */
final class PaymentStatus extends \Dfe\AlphaCommerceHub\T\CaseT {
	/** @test 2017-12-02 */
	function t00() {
		$amount = '161000'; /** @var string $amount */
		$currency = 'AUD'; /** @var string $currency */
		$merchantTxnID = '1122.2L758'; /** @var string $merchantTxnID */
		$method = 'PP'; /** @var string $method */
		$payerID = '7EY65DU75L82G'; /** @var string $payerID */
		$token = 'EC-19L893520Y2493608'; /** @var string $token */
	}
}