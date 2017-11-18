<?php
namespace Dfe\AlphaCommerceHub\W;
use Magento\Sales\Model\Order\Payment\Transaction as T;
/**
 * 2017-11-18
 * «AlphaHPP» → «Paypage Request Reference» → «Response Message»
 * http://developer.alphacommercehub.com.au/docs/alphahpp-#response-message
 */
final class Event extends \Df\PaypalClone\W\Event {
	/**
	 * 2017-11-18
	 * AlphaCommerceHub does not uses signatures for an unknown reason.
	 * "How should my extension check whether an AlphaHPP's response message
	 * is sent by AlphaCommerceHub or by a hacker?" https://mage2.pro/t/4806
	 * @override
	 * @see \Df\PaypalClone\W\Event::validate()
	 * @used-by \Df\Payment\W\Handler::handle()
	 */
	function validate() {}

	/**
	 * 2017-11-18 «The APC ID for the payment».
	 * An example: «104543502».
	 * @override
	 * @see \Df\PaypalClone\W\Event::k_idE()
	 * @used-by \Df\PaypalClone\W\Event::idE()
	 * @return string
	 */
	protected function k_idE() {return 'Result/PaymentID';}

	/**
	 * 2017-11-18 «The merchants transaction id».
	 * @override
	 * @see \Df\Payment\W\Event::k_pid()
	 * @used-by \Df\Payment\W\Event::pid()
	 * @return string
	 */
	protected function k_pid() {return 'Result/MerchantTxnID';}

	/**
	 * 2017-11-18
	 * Note 1.
	 * AlphaCommerceHub does not uses signatures for an unknown reason.
	 * "How should my extension check whether an AlphaHPP's response message
	 * is sent by AlphaCommerceHub or by a hacker?" https://mage2.pro/t/4806
	 * Note 2. This method is never used: @see validate()
	 * @override
	 * @see \Df\PaypalClone\W\Event::k_signature()
	 * @return string
	 */
	protected function k_signature() {return null;}

	/**
	 * 2017-11-18 «The code for the result of the event. Refer to the appendix.»
	 * http://developer.alphacommercehub.com.au/docs/alphahpp-#response-codes
	 * @override
	 * @see \Df\PaypalClone\W\Event::k_status()
	 * @used-by \Df\PaypalClone\W\Event::status()
	 * @return string|null
	 */
	protected function k_status() {return 'Result/ResponseCode';}

	/**
	 * 2017-11-19 «The description of the result of the event».
	 * @override
	 * @see \Df\PaypalClone\W\Event::k_statusT()
	 * @used-by \Df\PaypalClone\W\Event::statusT()
	 * @return string|null
	 */
	protected function k_statusT() {return 'Result/ResponseMessage';}

	/**
	 * 2017-11-19 http://developer.alphacommercehub.com.au/docs/alphahpp-#response-codes
	 * @override
	 * @see \Df\PaypalClone\W\Event::statusExpected()
	 * @used-by \Df\PaypalClone\W\Event::isSuccessful()
	 * @return int
	 */
	protected function statusExpected() {return 1000;}
}