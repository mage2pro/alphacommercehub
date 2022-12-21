<?php
namespace Dfe\AlphaCommerceHub\API;
/**
 * 2017-12-03
 * 2017-12-08
 * This class is not abstract because it is used directly by my unit tests:
 * @used-by \Dfe\AlphaCommerceHub\Test\CaseT\BankCard\CancelPayment::t01()
 * @used-by \Dfe\AlphaCommerceHub\Test\CaseT\BankCard\CapturePayment::t01()
 * @used-by \Dfe\AlphaCommerceHub\Test\CaseT\BankCard\RefundPayment::t01()
 * @used-by \Dfe\AlphaCommerceHub\Test\CaseT\PayPal\CapturePayment::t01()
 * @used-by \Dfe\AlphaCommerceHub\Test\CaseT\PayPal\PaymentStatus::t01()
 * @see \Dfe\AlphaCommerceHub\API\Facade\BankCard
 * @see \Dfe\AlphaCommerceHub\API\Facade\PayPal
 */
class Facade extends \Df\API\Facade {
	/**
	 * 2017-12-03
	 * @override
	 * @see \Df\API\Facade::path()
	 * @used-by \Df\API\Facade::p()
	 */
	protected function path(string $id, string $suf = ''):string {return $suf;}
}