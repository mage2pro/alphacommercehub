<?php
namespace Dfe\AlphaCommerceHub\Test\CaseT\PayPal;
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
 * 2017-12-08 "A PayPal's `CapturePayment` API request, and a response to it": https://mage2.pro/t/5127
 * https://mage2.pro/t/4985
 */
final class CapturePayment extends \Dfe\AlphaCommerceHub\Test\CaseT {
	/** 2017-12-04 @test */
	function t00():void {}

	/** 2017-12-03 */
	function t01():void {
		# 2017-12-03 token=EC-3UY10820730337844&PayerID=7EY65DU75L82G
		$r = F::s()->post([
			'Transaction' => [
				'Amount' => '168950'
				,'Currency' => 'AUD'
				,'MerchantTxnID' => '1208L.781'
				# 2017-12-08
				# If this parameter is absent, then AlphaCommerceHub will respond:
				# «Original transaction not found».
				,'Method' => 'PP'
			]
			# 2017-12-08
			# If this parameter is absent, then AlphaCommerceHub will respond:
			# «Wallet is missing or invalid».
			,'Wallet' => ['WalletID' => '7EY65DU75L82G']
		], df_class_l($this)); /** @var Op $r */
		echo df_json_encode($r->req());
		echo $r->j();
	}
}