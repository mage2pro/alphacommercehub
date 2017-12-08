<?php
namespace Dfe\AlphaCommerceHub\T\CaseT\PayPal;
use Df\API\Operation as Op;
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
	/** @test 2017-12-04 */
	function t00() {}

	/** 2017-12-03 */
	function t01() {
		// 2017-12-03 token=EC-3UY10820730337844&PayerID=7EY65DU75L82G
		$r = F::s()->post(['Transaction' => [
			'Amount' => '161000'
			,'Currency' => 'AUD'
			,'MerchantTxnID' => '1203L.759'
			,'Method' => 'PP'
		]], df_class_l($this)); /** @var Op $r */
		echo df_json_encode($r->req());
		echo $r->j();
	}
}