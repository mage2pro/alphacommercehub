<?php
namespace Dfe\AlphaCommerceHub\T\CaseT\BankCard;
use Dfe\AlphaCommerceHub\API\Facade as F;
/**
 * 2017-12-06
 * Note 1.
 * "Implement an ability to refund a bank card payment from the Magento backend
 * (the `RefundPayment` transaction)": https://github.com/mage2pro/alphacommercehub/issues/61
 * Note 2. "A `RefundPayment` API request and a response to it" https://mage2.pro/t/5077
 */
final class RefundPayment extends \Dfe\AlphaCommerceHub\T\CaseT {
	/** 2017-12-06 */
	function t00() {}

	/** @test 2017-12-06 */
	function t01() {
		$r = F::s()->post(['Transaction' => ['MerchantTxnID' => '1206L.763']], df_class_l($this));
		echo $r->j();
	}
}