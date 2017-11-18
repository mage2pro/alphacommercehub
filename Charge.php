<?php
namespace Dfe\AlphaCommerceHub;
use Df\Payment\Init\Action;
use Df\Payment\Settings\Options;
use Dfe\AlphaCommerceHub\Settings as S;
use Magento\Sales\Model\Order as O;
use Magento\Sales\Model\Order\Address as OA;
use Magento\Sales\Model\Order\Item as OI;
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
	 * 2017-11-01 «Which currencies are allowed by each payment option?» https://mage2.pro/t/4820
	 * @override
	 * @see \Df\PaypalClone\Charge::k_Currency()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return string
	 */
	protected function k_Currency() {return 'Currency';}

	/**
	 * 2017-10-27 «The customers email address», String(64), optional.
	 * 2017-11-13
	 * "The `EmailAddress` AlphaHPP request field is wrongly marked as «Alpha» («alphanumeric»)"
	 * https://mage2.pro/t/4925
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
	 * 2017-11-01
	 * Which characters are considered «special» and «not special» for the AlphaHPP's `MerchantTxnID` parameter?
	 * https://mage2.pro/t/4842
	 * @override
	 * @see \Df\PaypalClone\Charge::k_RequestId()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return string
	 */
	protected function k_RequestId() {return 'MerchantTxnID';}

	/**
	 * 2017-11-01
	 * AlphaCommerceHub does not use signatures.
	 * It requires a plain merchant password (`UserId`) instead.
	 * *) How is the APIs authentication security implemented? https://mage2.pro/t/4768
	 * *) Will the `MerchantID` and `UserID` values be preserved for a particular merchant
	 * after switching its account from the test mode to the production one? ttps://mage2.pro/t/4798
	 * *) Where can a merchant find his `MerchantID` and `UserID` values? https://mage2.pro/t/4799
	 * @override
	 * @see \Df\PaypalClone\Charge::k_Signature()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return null
	 */
	protected function k_Signature() {return null;}

	/**
	 * 2017-10-27
	 * @override
	 * @see \Df\PaypalClone\Charge::pCharge()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return array(string => mixed)
	 */
	protected function pCharge() {
		$s = $this->s(); /** @var S $s */
		$o = $s->options(); /** @var Options $o */
		/**
		 * 2017-11-01
		 * «Whether the `Street1`, `Street2`, `City`, `Zip`, `Country` parameters
		 * are related to a shipping address, or to a billing address?» https://mage2.pro/t/4855
		 * 2017-11-02
		 * An order/quote can be without a shipping address (consist of the Virtual products).
		 * In this case $sa is an empty object.
		 * https://en.wikipedia.org/wiki/Null_object_pattern
		 * @var OA $sa
		 */
		$sa = $this->addressS(true);
		return df_clean([
			/**
			 * 2017-11-02
			 * Note 1.
			 * «If a merchant configured for 3D Secure wants to bypass it for a transaction they may send ‘Y’.»
			 * Note 2.
			 * «What will happen if a not configured for 3D Secure merchant
			 * will pass `3DSecureBypass = Y` in an AlphaHPP request?» https://mage2.pro/t/4857
			 * Note 3.
			 * «Is `Y` the only allowed value of the `3DSecureBypass` parameter,
			 * or `N` (or an empty string) is also allowed?» https://mage2.pro/t/4858
			 *
			 * 2017-11-04
			 * AlphaHPP 3D Secure: «No Processor flow found for Transaction type: EnrolCheck»:
			 * https://github.com/mage2pro/alphacommercehub/issues/7
			 *
			 * 2017-11-13
			 * «Today I have got a response from the AlphaCommerceHub team:
			 * I didn't set-up the merchant, the `EnrolCheck` process flow was missing.
			 * Has been added but there is another configuration step of 3D Secure certs needed
			 * which I need one of my colleagues in Dublin to get later today.»
			 * https://github.com/mage2pro/alphacommercehub/issues/7#issuecomment-343819432
			 */
			'3DSecureBypass' => 'Y'
			//$s->_3ds()->enable_($sa->getCountryId(), $this->o()->getCustomerId()) ? null : 'Y'
			/**
			 * 2017-11-02
			 * Note 1. «Allows the merchant to define a URL that is redirected to for unsuccessful transactions».
			 * String, optional.
			 * Note 2. "What is `CancelURL` («Cancel URL»)" https://mage2.pro/t/4804
			 */
			,'CancelURL' => $this->callback(null)
			/**
			 * 2017-11-01
			 * «Y/N – Allows a merchant to flag that a transaction should be captured (settled) if the auth is».
			 * String(1), optional.
			 */
			,'Capture' => Action::sg($this->m())->preconfiguredToCapture() ? 'Y' : 'N'
			/**
			 * 2017-11-03
			 * Note 1.
			 * «The type of card selected by the user e.g.
			 * `VISA` or `MASTER` or allows merchant to prepopulate reduced set of cards to display
			 * by using comma delimiter.
			 * Current values supported:
			 * 		`AMEX`
			 * 		`CUP DINERS`
			 * 		`JCB`
			 * 		`MAESTRO`
			 * 		`MASTER`
			 * 		`MASTERCARDEBIT`
			 * 		`VISA`
			 * 		`VISADEBIT`
			 * 		`VISAELECTRON`
			 * ».
			 * String(50), optional.
			 * Note 2.
			 * «Is any practical sense to limit a buyer to particular bank card brands
			 * by the `CardType` parameter, and disallow to pay with a card of another brand?»
			 * https://mage2.pro/t/4861
			 */
			,'CardType' => null
			/**
			 * 2017-11-01 «The customers City». String(25), optional.
			 * 2017-11-13
			 * @todo "Are the address fields really alphanumeric?
			 * (`Street1`, `Street2`, `City`, `Zip`, `State`)"
			 * «So, should the commas, hyphens, brackets be stripped?
			 * What about non-English letters?»
			 * https://mage2.pro/t/4926
			 */
			,'City' => $this->text($sa->getCity(), 25)
			// 2017-11-02 «ISO A2 country code e.g. US». String(2), optional.
			,'Country' => $sa->getCountryId()
			/**
			 * 2017-11-01
			 * «A customer identifier assigned by the merchant». String(20), optional.
			 * Should `CustomerID` be unique or not? https://mage2.pro/t/4854
			 */
			,'CustomerID' => $this->customerEmail()
			// 2017-11-03
			// «Allows a merchant that is configured for fraud screening
			// to bypass screening for a particular transaction by passing Y as the value».
			// String(1), optional, `Y` or `N`.
			,'FraudCheckBypass' => 'N'
			// 2017-11-01 «The customers IP address». String(15), optional.
			,'IPAddress' => df_visitor_ip()
			/**
			 * 2017-11-01
			 * Note 1. «AlphaHPP» → «Paypage Request Reference» → «Request a Paypage Session»
			 * «The language the customer selected on the merchants site.
			 * For example `EN` for English, `DE` for German.
			 * If not provided the page will default to English
			 * or if a language is provided that is not supported the page will default to.»
			 * String(2), optional.
			 * http://developer.alphacommercehub.com.au/docs/alphahpp-#request-a-paypage-session
			 * Note 2. «AlphaHPP» → «Paypage Request Reference» → «Supported Languages»
			 * http://developer.alphacommercehub.com.au/docs/alphahpp-#supported-languages
			 */
			,'Language' => df_lang(df_visitor_locale())
			/**
			 * 2017-11-01
			 * Note 1.
			 * «Allows the merchant to specify a statement descriptor for the transaction.
			 * Please note the narrative supported by different card schemes varies.»
			 * http://alpha.pwstaging.com.au/docs/alphahpp#request-a-paypage-session
			 * String(64), optional.
			 * Note 2.
			 * «[AlphaCommerceHub] Is the `MerchantDescriptor` parameter really accepts up to 64 characters?»
			 * https://mage2.pro/t/4800
			 * «The value is truncated depending on the provider of the payment processing.
			 * The Wikipedia article mainly focuses on card schemes,
			 * but as the platform supports many different payment methods worldwide
			 * this can be passed through to various providers where appropriate.
			 * The 64 character limit is simply what the hub will accept,
			 * not what is sent through to the end provider.»
			 * Note 3.
			 * «Are any unallowed characters for the `MerchantDescriptor` parameter values,
			 * and how does AlphaCommerceHub handle them
			 * (silently strip them, or reject the payment API request at whole)?»
			 * https://mage2.pro/t/4801
			 * Note 4.  We provide also `TxnDetails` for a long description.
			 */
			,'MerchantDescriptor' => $this->text($s->dsd(), 64)
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
			 *
			 * 2017-11-01
			 * I have implemented it similar to @see \Dfe\AllPay\Charge::pChoosePayment():
			 * https://github.com/mage2pro/allpay/blob/1.10.0/Charge.php#L538-L562
			 */
			,'Method' => $this->m()->option() ?: (!$o->isLimited() ? 'ALL' : df_csv($o->allowed()))
			/**
			 * 2017-11-03 «Product Line Information»
			 * http://developer.alphacommercehub.com.au/docs/alphahpp-#product-line-information
			 */
			,'OrderDetails' => $this->pOrderItems()
			// 2017-11-01 «A social or tax id for the customer». String(20), optional.
			,'SocialID' => $this->customerVAT()
			/**
			 * 2017-11-02 «The State/Province element of the customers shipping address». String(50), optional.
			 * 2017-11-13
			 * @todo "Are the address fields really alphanumeric?
			 * (`Street1`, `Street2`, `City`, `Zip`, `State`)"
			 * «So, should the commas, hyphens, brackets be stripped?
			 * What about non-English letters?»
			 * https://mage2.pro/t/4926
			 */
			,'State' => $this->text($sa->getRegion(), 50)
			/**
			 * 2017-11-01 «The first line of the customers street address». String(100), optional.
			 * 2017-11-13
			 * @todo "Are the address fields really alphanumeric?
			 * (`Street1`, `Street2`, `City`, `Zip`, `State`)"
			 * «So, should the commas, hyphens, brackets be stripped?
			 * What about non-English letters?»
			 * https://mage2.pro/t/4926
			 */
			,'Street1' => $this->text($sa->getStreetLine(1), 100)
			/**
			 * 2017-11-01 «The second line of the customers street address». String(100), optional.
			 * 2017-11-13
			 * @todo "Are the address fields really alphanumeric?
			 * (`Street1`, `Street2`, `City`, `Zip`, `State`)"
			 * «So, should the commas, hyphens, brackets be stripped?
			 * What about non-English letters?»
			 * https://mage2.pro/t/4926
			 */
			,'Street2' => $this->text($sa->getStreetLine(2), 100)
			/**
			 * 2017-11-02
			 * Note 1.
			 * «Allows the merchant to define a different URL the response should be sent to
			 * from the default configured URL, for example for mobile support».
			 * String, optional.
			 * Note 2. "What is `SuccessURL` («Success URL»)?" https://mage2.pro/t/4803
			 * Note 3. "Whether a customer is redirected to `SuccessURL` or not?" https://mage2.pro/t/4805
			 * Note 4. "What is «result page» for AlphaHPP?" https://mage2.pro/t/4807
			 */
			,'SuccessURL' => $this->callback(null)
			// 2017-11-01 «The customers phone number. Numbers only.» Numeric(6-14), optional.
			,'TelNo' => substr(preg_replace('/\D/', '', $this->customerPhone()), 0, 14)
			/**
			 * 2017-10-27
			 * «The date and time of the transaction.
			 * If not provided defaults to ACH system timestamp (UTC).
			 * Format: DDMMYYYYHHMMSS»
			 * String(14), optional.
			 */
			,'TransactionTimestamp' => null
			/**
			 * 2017-11-01
			 * Note 1. «Information on the product/service being purchased». String(2000), optional.
			 * Note 2.
			 * We provide also `MerchantDescriptor` for a very short description:
			 * e.g., it is used as a dynamic statement descriptor for the bank card payment option:
			 * https://en.wikipedia.org/wiki/Billing_descriptor#Dynamic_descriptors
			 * 2017-11-04
			 * AlphaHPP 3D Secure: «Request failed, format error: TxnDetails»:
			 * https://github.com/mage2pro/alphacommercehub/issues/6
			 * 2017-11-13
			 * Note 1.
			 * «This field is only accepting alphanumerics.
			 * The brackets are not supported.
			 * We will look to extend this field for special characters
			 * but for now please remove the brackets and comma from your test string.»
			 * https://github.com/mage2pro/alphacommercehub/issues/6#issuecomment-343819287
			 * Note 2.
			 * "Is it normal that AlphaCommerceHub treats a bracket
			 * in a payment description or in a product name as a critical error,
			 * and rejects the whole payment?" https://mage2.pro/t/4922
			 * Note 3.
			 * "The maximum length of a payment description (`TxnDetails`) is 2000 characters"
			 * https://mage2.pro/t/4852
			 * Note 4. "What does the «Alpha» type mean?" https://mage2.pro/t/4920
			 * Note 5. "What is the full set of characters treated as «alphanumeric» in the specification?"
			 * https://mage2.pro/t/4921
			 */
			,'TxnDetails' => $this->description()
			/**
			 * 2017-10-27
			 * Note 1. «User id for authentication», String, required.
			 * Note 2. [AlphaCommerceHub] How is the APIs authentication security implemented?
			 * https://mage2.pro/t/4768
			 * Note 3.
			 * [AlphaCommerceHub] Will the `MerchantID` and `UserID` values be preserved
			 * for a particular merchant after switching its account from the test mode to the production one?
			 * https://mage2.pro/t/4798
			 * Note 4. [AlphaCommerceHub] Where can a merchant find his `MerchantID` and `UserID` values?
			 * https://mage2.pro/t/4799
			 * Note 5. AlphaCommerceHub does not use signatures. `UserId` is really a merchant password.
			 */
			,'UserId' => $s->privateKey()
			/**
			 * 2017-11-01
			 * «The customers Zip/Postal code. See note on validated countries.». String(20), optional.
			 * 2017-11-13
			 * @todo "Are the address fields really alphanumeric?
			 * (`Street1`, `Street2`, `City`, `Zip`, `State`)"
			 * «So, should the commas, hyphens, brackets be stripped?
			 * What about non-English letters?»
			 * https://mage2.pro/t/4926
			 */
			,'Zip' => $this->text($sa->getPostcode())
		]);
	}

	/**
	 * 2017-11-13
	 * Note 1.
	 * «This field is only accepting alphanumerics.
	 * The brackets are not supported.
	 * We will look to extend this field for special characters
	 * but for now please remove the brackets and comma from your test string.»
	 * https://github.com/mage2pro/alphacommercehub/issues/6#issuecomment-343819287
	 * Note 2.
	 * "Is it normal that AlphaCommerceHub treats a bracket
	 * in a payment description or in a product name as a critical error,
	 * and rejects the whole payment?" https://mage2.pro/t/4922
	 * Note 3. "What does the «Alpha» type mean?" https://mage2.pro/t/4920
	 * Note 4. "What is the full set of characters treated as «alphanumeric» in the specification?"
	 * https://mage2.pro/t/4921
	 * @override
	 * @see \Df\Payment\Charge::textFilter()
	 * @used-by \Df\Payment\Charge::text()
	 * @param string $s
	 * @return string
	 */
	protected function textFilter($s) {return preg_replace("#[^a-zA-Z0-9\s]+#", '',
		str_replace('-', ' ', df_translit($s))
	);}

	/**
	 * 2017-11-03
	 * http://developer.alphacommercehub.com.au/docs/alphahpp-#product-line-information
	 * @used-by pOrderItems()
	 * @param string $name
	 * @param int $amountF
	 * @param int $qty [optional]
	 * @return array(string => mixed)
	 */
	private function pOrderItem($name, $amountF, $qty = 1) {return [
		// 2017-11-03
		// «The product line price expressed with 3 virtual decimal places e.g. $1 is 1000».
		// Integer, optional.
		'ItemAmount' => $amountF
		//
		/**
		 * 2017-11-03 «The name of the product line». String, optional.
		 * 2017-11-13
		 * It should be «Alpha» («alphanumeric»)
		 * "[AlphaCommerceHub] What does the «Alpha» type mean?" https://mage2.pro/t/4920
		 * "[AlphaCommerceHub] What is the full set of characters
		 * treated as «alphanumeric» in the specification?" https://mage2.pro/t/4921
		 * So I use @uses text() here.
		 */
		,'ItemName' => $this->text($name)
		// 2017-11-03 «The quantity of the product». Integer, optional.
		,'ItemQuantity' => $qty
		/**
		 * 2017-11-03
		 * Note 1.
		 * «Is the product tax exempt or not.
		 * `TRUE` – the product is tax exempt and AlphaHPP will not calculate tax for the product.»
		 * String, optional.
		 * http://developer.alphacommercehub.com.au/docs/alphahpp-#product-line-information
		 * Note 2.
		 * "Whether an `OrderDetails` → `ItemTaxExempt` positive value should be really expressed
		 * as the «TRUE» string, not as «Y» string (like for other boolean parameters)?"
		 * https://mage2.pro/t/4871
		 */
		,'ItemTaxExempt' => 'TRUE'
	];}

	/**
	 * 2017-11-03
	 * Note 1.
	 * «Product Line Information»
	 * http://developer.alphacommercehub.com.au/docs/alphahpp-#product-line-information
	 * Note 2.
	 * In which cases does AlphaHPP calculate the shopping cart product contents for a merchant?
	 * https://mage2.pro/t/4868
	 * Note 3.
	 * How does AlphaHPP handle the situation
	 * when the `Amount` value differs from the amount calculated from `OrderDetails`?
	 * https://mage2.pro/t/4869
	 * Note 4.
	 * Can an `OrderDetails` → `ItemAmount` be negative?
	 * https://mage2.pro/t/4870
	 * @used-by pCharge()
	 * @return array(array(string => string|int))
	 */
	private function pOrderItems() {
		$o = $this->o(); /** @var O $o */
		$r = array_merge(
			$this->oiLeafs(function(OI $i) {return $this->pOrderItem(
				$i->getName(), $this->cFromDocF(df_oqi_price($i, true, true)), df_oqi_qty($i)
			);})
			/**
			 * 2017-10-03
			 * I need the shipping cost WITH discount and WITH tax.
			 * `shipping_amount`: it is the shipping cost WITHOUT discount and tax.
			 * `shipping_tax_amount`: it is the tax.
			 * `shipping_discount_amount`: it is the discount (a positive value, so I subtract it).
			 */
			,[$this->pOrderItem('Shipping', $this->cFromDocF(
				$o->getShippingAmount() - $o->getShippingDiscountAmount() + $o->getShippingTaxAmount()
			))]
		); /** @var array(array(string => mixed)) $r */
		/**
		 * 2017-11-14
		 * Note 1.
		 * We can have a mismatch between the `Amount` value and the aggregated order items amounts
		 * because of the 2 reasons:
		 * 1) \Dfe\AlphaCommerceHub\Method::amountFormat() uses @see round()
		 * https://github.com/mage2pro/alphacommercehub/blob/0.2.6/Method.php#L5-L21
		 * "The last amounts digit should be 0 for all currencies except JPY and OMR":
		 * https://github.com/mage2pro/alphacommercehub/issues/14
		 * 2) The Magento's base currency can be different from the current order's currency,
		 * and in this case we will have conversions and roundings.
		 *
		 * An example of such mismatch: https://github.com/mage2pro/alphacommercehub/issues/15
		 * 		`Amount`: 160710
		 * 		---------
		 * 		OrderDetails[0][ItemAmount]: 45550
		 * 		OrderDetails[0][ItemQuantity]: 2
		 * 		OrderDetails[1][ItemAmount]: 56940
		 * 		OrderDetails[1][ItemQuantity]: 1
		 * 		OrderDetails[2][ItemAmount]: 12650
		 * 		OrderDetails[2][ItemName]: Shipping
		 * 		OrderDetails[2][ItemQuantity]: 1
		 * 		--------
		 * 		The calculated amount is 160690 != 160710
		 *
		 * Because of this potential amounts mismatch, we make an adjustment.
		 * https://github.com/mage2pro/alphacommercehub/issues/16
		 *
		 * Note 2.
		 * @todo "How does AlphaHPP handle the situation when the `Amount` value differs
		 * from the amount calculated from `OrderDetails`?" https://mage2.pro/t/4869
		 */
		/** @var int $adjustment */
		if ($adjustment = $this->amountF() - array_sum(array_map(function(array $i) {return
			$i['ItemQuantity'] * $i['ItemAmount']
		;}, $r))) {
			/**
			 * 2017-11-14
			 * 1) @todo "Can an `OrderDetails` → `ItemAmount` be negative?" https://mage2.pro/t/4870
			 * 2) "In which cases does AlphaHPP calculate the shopping cart product contents for a merchant?"
			 * https://mage2.pro/t/4868
			 */
			$r[]= $this->pOrderItem('Adjustment', $adjustment);
		}
		return $r;
	}
}