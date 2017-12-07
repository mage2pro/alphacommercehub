<?php
namespace Dfe\AlphaCommerceHub\T\CaseT\BankCard;
use Df\API\Operation as Op;
use Dfe\AlphaCommerceHub\API\Facade as F;
/**
 * 2017-12-08
 * "Implement an ability to void a preauthorized bank card payment from the Magento backend
 * (the `CancelPayment` transaction)" https://github.com/mage2pro/alphacommercehub/issues/69
 */
final class CancelPayment extends \Dfe\AlphaCommerceHub\T\CaseT {
	/** 2017-12-08 */
	function t00() {}

	/** @test 2017-12-08 */
	function t01() {
		/** @var Op $r */
		$r = F::s()->post(['Transaction' => ['MerchantTxnID' => '1208L.768']], df_class_l($this));
		echo $r->j();
	}
}