<?php
namespace Dfe\AlphaCommerceHub\T\CaseT\PayPal;
use Dfe\AlphaCommerceHub\API\Facade\PaymentStatus as PS;
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
		$r = PS::s()->post(['Transaction' => [
			'Amount' => '161000'
			,'Currency' => 'AUD'
			,'MerchantTxnID' => '1122.2L758'
			,'Method' => 'PP'
		]]);
	}
}