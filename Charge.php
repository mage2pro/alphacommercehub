<?php
namespace Dfe\AlphaCommerceHub;
/**
 * 2017-10-27
 * The charge parameters are described
 * in the «AlphaHPP» → «Paypage Request Reference» → «Request a Paypage Session» section
 * of the documentation: http://alpha.pwstaging.com.au/docs/alphahpp#request-a-paypage-session
 * @method Method m()
 * @method Settings s()
 */
final class Charge extends \Df\PaypalClone\Charge {
	/**
	 * 2017-10-27 «Amount of the payment», Numeric, 17 (14,3), required.
	 * @see \Dfe\AlphaCommerceHub\Method::amountFactor()
	 * @override
	 * @see \Df\PaypalClone\Charge::k_Amount()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return string
	 */
	protected function k_Amount() {return 'Amount';}
	
	/**
	 * 2017-10-27 «The currency of the order. ISO-4217 A3 codes e.g. USD, GBP.», required.
	 * @override
	 * @see \Df\PaypalClone\Charge::k_Currency()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return string
	 */
	protected function k_Currency() {return 'Currency';}

	/**
	 * 2017-10-27 «The customers email address», String(64), optional.
	 * @override
	 * @see \Df\PaypalClone\Charge::k_Email()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return string
	 */
	protected function k_Email() {return 'EmailAddress';}

	/**
	 * 2017-10-27
	 * Note 1. «The merchant identifier assigned by ACH», String, required.
	 * Note 2.
	 * [AlphaCommerceHub] Will the `MerchantID` and `UserID` values be preserved
	 * for a particular merchant after switching its account from the test mode to the production one?
	 * https://mage2.pro/t/4798
	 * Note 3. [AlphaCommerceHub] Where can a merchant find his `MerchantID` and `UserID` values?
	 * https://mage2.pro/t/4799
	 * @override
	 * @see \Df\PaypalClone\Charge::k_MerchantId()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return string
	 */
	protected function k_MerchantId() {return 'MerchantID';}
	
	/**
	 * 2017-10-27
	 * Note 1.
	 * «The merchants order id for the payment. Supported special characters are / and .». String, required.
	 * Note 2.
	 * [AlphaCommerceHub] Are any limitations on the «MerchantTxnID» parameter allowed characters or length?
	 * https://mage2.pro/t/4779
	 * @override
	 * @see \Df\PaypalClone\Charge::k_RequestId()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return string
	 */
	protected function k_RequestId() {return 'MerchantTxnID';}

	/**
	 * 2017-10-27
	 * Note 1. «User id for authentication», String, required.
	 * Note 2. [AlphaCommerceHub] How is the APIs authentication security implemented? https://mage2.pro/t/4768
	 * Note 3.
	 * [AlphaCommerceHub] Will the `MerchantID` and `UserID` values be preserved
	 * for a particular merchant after switching its account from the test mode to the production one?
	 * https://mage2.pro/t/4798
	 * Note 4. [AlphaCommerceHub] Where can a merchant find his `MerchantID` and `UserID` values?
	 * https://mage2.pro/t/4799
	 * Note 5. AlphaCommerceHub does not use signatures. `UserId` is really a merchant password.
	 * @override
	 * @see \Df\PaypalClone\Charge::k_Signature()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return string
	 */
	protected function k_Signature() {return 'UserId';}

	/**
	 * 2017-10-27
	 * @override
	 * @see \Df\PaypalClone\Charge::pCharge()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return array(string => mixed)
	 */
	protected function pCharge() {return [
		/**
		 * 2017-10-27
		 * Note 1.
		 * «The payment method to be used, default to `CC`.
		 * `ALL` will display all configured payment methods.
		 * Merchant can select a subset by delimiting.
		 * E.g. `CC,ID,AL` would display cards, iDeal, Alipay if supported by AlphaHPP.»
		 * String(14), required.
		 * Note 2. [AlphaCommerceHub] The payment method codes: https://mage2.pro/t/4809
		 * Note 3.
		 * [AlphaCommerceHub] Which codes are used for the
		 * Visa Checkout, UnionPay Online Payments, and ApplePay payment options?
		 * https://mage2.pro/t/4811
		 */
		'Method' => 'ALL'
		/**
		 * 2017-10-27
		 * «The date and time of the transaction.
		 * If not provided defaults to ACH system timestamp (UTC).
		 * Format: DDMMYYYYHHMMSS»
		 * String(14), optional.
		 */
		,'TransactionTimestamp' => null
	];}
}