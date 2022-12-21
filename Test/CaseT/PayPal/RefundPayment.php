<?php
namespace Dfe\AlphaCommerceHub\Test\CaseT\PayPal;
use Df\API\Operation as Op;
use Dfe\AlphaCommerceHub\API\Facade as F;
/**
 * 2017-12-11
 * 1) "PayPal: implement the `RefundPayment` transaction":
 * https://github.com/mage2pro/alphacommercehub/issues/50
 * 2) "Where is the request and response parameters specification
 * for the PayPal's `RefundPayment` transaction?": https://mage2.pro/t/4993
 */
final class RefundPayment extends \Dfe\AlphaCommerceHub\Test\CaseT {
	/** 2017-12-11 */
	function t00():void {}

	/** 2017-12-11 @test */
	function t01():void {
		# 2017-12-03 token=EC-57L73665MJ662912E&PayerID=7EY65DU75L82G
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
			//,'Wallet' => ['WalletID' => '7EY65DU75L82G']
		], df_class_l($this)); /** @var Op $r */
		echo df_json_encode($r->req());
		echo $r->j();
	}
}