<?php
namespace Dfe\AlphaCommerceHub\Test\CaseT\PayPal;
use Df\API\Operation as Op;
use Dfe\AlphaCommerceHub\API\Facade as F;
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
 * 2017-12-08
 * 1) https://mage2.pro/tags/alphacommercehub-paypal-payment-status
 * 2) "A PayPal's `PaymentStatus` API request, and a response to it": https://mage2.pro/t/5120
 * 2017-12-13
 * "The PayPal's `PaymentStatus` transaction returns wrong `Result.Amount` values (like «186240.00»)
 * since 2017-12-13": https://github.com/mage2pro/alphacommercehub/issues/82
 */
final class PaymentStatus extends \Dfe\AlphaCommerceHub\Test\CaseT {
	/** @test 2017-12-03 */
	function t00():void {}

	/** 2017-12-02 */
	function t01():void {
		# token=EC-37F54734B9677552F&PayerID=7EY65DU75L82G
		$r = F::s()->post(['Transaction' => [
			/**
			 * 2017-12-11
			 * From now on, if the `Amount` and `Currency` request parameters are not provided,
			 * then AlphaCommerceHub responds `1059` / «Internal Processing Error please resend the request»
			 * to a PayPal's PaymentStatus API request:
			 * https://github.com/mage2pro/alphacommercehub/issues/81
			 *
			 * 2017-12-13
			 * The PayPal's `PaymentStatus` is working again without `Amount` and `Currency`.
			 * But the corresponding rows are empty in the AlphaCommerceHub's merchant interface in this case.
			 */
			'Amount' => '168950'
			,'Currency' => 'AUD'
			,'MerchantTxnID' => '1208L.781'
			/**
			 * 2017-12-08
			 * "Why is AlphaCommerceHub unable to detect `Method` automatically by `MerchantTxnID`
			 * and responds «No routing available for the supplied parameters»
			 * to a PayPal's `PaymentStatus` request if `Method` is not provided?"
			 * https://mage2.pro/t/5119
			 */
			,'Method' => 'PP'
		]], df_class_l($this)); /** @var Op $r */
		$r['Result/Amount'] = dfe_alphacommercehub_fix_amount_bug($r['Result/Amount']);
		echo df_json_encode($r->req());
		echo $r->j();
	}
}