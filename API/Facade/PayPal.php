<?php
namespace Dfe\AlphaCommerceHub\API\Facade;
use Df\API\Operation as Op;
// 2017-12-08
/** @method static $this s() */
final class PayPal extends \Dfe\AlphaCommerceHub\API\Facade {
	/**
	 * 2017-12-08
	 * @used-by \Dfe\AlphaCommerceHub\W\Reader::reqFilter()
	 * @param Op $s
	 * @return Op
	 */
	function capture(Op $s) {return $this->post([
		'Transaction' => [
			'Amount' => $s->a('Result/Amount')
			,'Currency' => $s->a('Result/Currency')
			,'MerchantTxnID' => $s->a('Result/MerchantTxnID')
			// 2017-12-08
			// If this parameter is absent, then AlphaCommerceHub will respond:
			// «Original transaction not found».
			,'Method' => 'PP'
		]
		// 2017-12-08
		// If this parameter is absent, then AlphaCommerceHub will respond:
		// «Wallet is missing or invalid».
		,'Wallet' => ['WalletID' => $s->a('MethodResult/WalletID')]
	], 'CapturePayment');}

	/**
	 * 2017-12-08
	 * 1) "Why is AlphaCommerceHub unable to detect `Method` automatically by `MerchantTxnID`
	 * and responds «No routing available for the supplied parameters»
	 * to a PayPal's `PaymentStatus` request if `Method` is not provided?"
	 * https://mage2.pro/t/5119
	 * 2) "A PayPal's `PaymentStatus` API request, and a response to it": https://mage2.pro/t/5120
	 * @used-by \Dfe\AlphaCommerceHub\W\Reader::reqFilter()
	 * @param string $id
	 * @return Op
	 */
	function status($id) {return $this->post(
		['Transaction' => ['MerchantTxnID' => $id, 'Method' => 'PP']], 'PaymentStatus'
	);}
}