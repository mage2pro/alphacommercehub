<?php
namespace Dfe\AlphaCommerceHub\T\CaseT\PayPal;
use Dfe\AlphaCommerceHub\API\Facade as F;
/**
 * 2017-12-03
 * Note 1. «API Explorer» → «POST CapturePayment»
 * http://developer.alphacommercehub.com.au/api-explorer/11/paypal---capture-payment
 * Note 2. "Where is the response parameters specification for the PayPal's `CapturePayment` transaction?":
 * https://mage2.pro/t/4992/5
 * Note 3.
 * "How can I know `MerchantID`
 * (which is required for the `PaymentStatus` and `CapturePayment` PayPal transactions)
 * if a `SuccessURL` response to a PayPal payment does not provide it?":
 * https://mage2.pro/t/5017
 * Note 4.
 * "Why a `SuccessURL` response to a PayPal payment does not contain any information about the buyer?"
 * https://mage2.pro/t/4985
 */
final class CapturePayment extends \Dfe\AlphaCommerceHub\T\CaseT {
	/** @test 2017-12-03 */
	function t00() {
		$r = F::s()->post(['Transaction' => [
			'Amount' => '161000'
			,'Currency' => 'AUD'
			,'MerchantTxnID' => '1122.2L758'
			,'Method' => 'PP'
		]], df_class_l($this));
	}
}