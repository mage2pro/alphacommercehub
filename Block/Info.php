<?php
namespace Dfe\AlphaCommerceHub\Block;
use Dfe\AlphaCommerceHub\Choice;
use Dfe\AlphaCommerceHub\W\Event;
/**
 * 2017-11-19
 * @final Unable to use the PHP «final» keyword here because of the M2 code generation.
 * @method Choice choice()
 * @method Event|string|null e(...$k)
 */
class Info extends \Df\Payment\Block\Info {
	/**
	 * 2017-11-19
	 * @override
	 * @see \Df\Payment\Block\Info::prepare()
	 * @used-by \Df\Payment\Block\Info::prepareToRendering()
	 */
	final protected function prepare() {
		$e = $this->e(); /** @var Event $e */
		/**
		 * 2017-11-18 «The APC ID for the payment». An example: «104543502».
		 * 2017-11-22
		 * Some AlphaCommerceHub's payment options do not return `Result/PaymentID`:
		 * *) A `SuccessURL` response to a POLi Payments payment: https://mage2.pro/t/4961
		 * *) "Which payment options `SuccessURL` responses contain `PaymentID`, and which do not?":
		 * https://mage2.pro/t/4996
		 * @see \Dfe\AlphaCommerceHub\W\Event::k_idE():
		 * https://github.com/mage2pro/alphacommercehub/blob/0.4.2/W/Event.php#L28-L49
		 * 2017-12-08
		 * AlphaCommerceHub does not provide `Result/PaymentID` for PayPal payments too:
		 * *) "A PayPal's `PaymentStatus` API request, and a response to it": https://mage2.pro/t/5120
		 * *) "A PayPal's `CapturePayment` API request, and a response to it": https://mage2.pro/t/5127
		 */
		if ($eId = $e->r('Result/PaymentID')) { /** @var string $eId */
			/**
			 * 2017-11-20
			 * "Show the AlphaCommerceHub's payment ID (`PaymentID`)
			 * in the Magento's backend «Payment Information» block":
			 * https://github.com/mage2pro/alphacommercehub/issues/41
			 */
			$this->siEx('AlphaCommerceHub ID', $eId);
		}
		/**
		 * 2017-11-21
		 * 1) "What is the recommended way to detect the chosen payment option
		 * from a `SuccessURL` response?" https://mage2.pro/t/4978
		 * 2) "What is the recommended way to detect the chosen payment option
		 * from a `CancelURL` response?" https://mage2.pro/t/4979
		 */
		$this->si('Payment Option', $this->choiceT());
		if ($e->isSuccessful()) {
			/** @var string $choiceId */
			switch ($choiceId = $this->choice()->id()) {
				case 'CC':
					/**
					 * 2017-11-21
					 * "Show the cardholder and bank card information in the Magento's «Payment Information» blocks
					 * (backend, frontend, emails)": https://github.com/mage2pro/alphacommercehub/issues/38
					 */
					$this->si([
						'Card Number' => "{$e->r('PaymentInfo/BIN')} ···· ({$e->r('Result/CardType')})"
						,'Cardholder' => $e->r('Result/CardHolder')
					]);
					$this->siEx(['Issuer Country' => df_country_ctn($e->r('PaymentInfo/PaymentIssuerCountry'))]);
					break;
				case 'PO':
					/**
					 * 2017-11-22
					 * "POLi Payments: show the POLi Payments' `ProviderReference`
					 * in the Magento's backend «Payment Information» block":
					 * https://github.com/mage2pro/alphacommercehub/issues/56
					 */
					$this->siEx('POLi Payments ID', $e->providerRespL('ProviderReference'));
					break;
				case 'PP':
					/**
					 * 2017-12-08
					 * "A PayPal's `PaymentStatus` API request, and a response to it": https://mage2.pro/t/5120
					 */
					$pd = $e->r('PayerDetails'); /** @var array(string => string) $pd */
					$this->siEx([
						/**
						 * 2017-12-08
						 * 1) "Show the PayPal's `PayerDetails`.`CustomerFirstName` (the payer's first name)
						 * in the Magento's backend «Payment Information» block":
						 * https://github.com/mage2pro/alphacommercehub/issues/75
						 * 2) "Show the PayPal's `PayerDetails`.`CustomerName` (the payer's last name)
						 * in the Magento's backend «Payment Information» block":
						 * https://github.com/mage2pro/alphacommercehub/issues/76
						 * 3) "Does the `PayerDetails`.`CustomerName` field
						 * of a response to the PayPal's `PaymentStatus` API request
						 * always contain only the last name of the customer?": https://mage2.pro/t/topic/5133
						 */
						'[PayPal] Customer' => df_cc_s(dfa($pd, ['CustomerFirstName', 'CustomerName']))
						/**
						 * 2017-11-21
						 * "Show the `WalletID` (`PayerID`) for a successful PayPal payment
						 * in the Magento's backend «Payment Information» block":
						 * https://github.com/mage2pro/alphacommercehub/issues/45
						 * 2017-12-08 A string like «7EY65DU75L82G».
						 */
						,'[PayPal] Wallet ID' => $e->r('MethodResult/WalletID')
						/**
						 * 2017-12-08
						 * "Show the PayPal's `PayerDetails`.`Country` (the payer's country)
						 * in the Magento's backend «Payment Information» block":
						 * https://github.com/mage2pro/alphacommercehub/issues/72
						 */
						,'[PayPal] Country' => df_country_ctn(dfa($pd, 'Country'))
						/**
						 * 2017-12-09
						 * "Show the PayPal's `PayerDetails`.`State` (the payer's state/region)
						 * in the Magento's backend «Payment Information» block":
						 * https://github.com/mage2pro/alphacommercehub/issues/77
						 */
						,'[PayPal] State' => dfa($pd, 'State')
						/**
						 * 2017-12-09
						 * "Show the PayPal's `PayerDetails`.`City` (the payer's city)
						 * in the Magento's backend «Payment Information» block":
						 * https://github.com/mage2pro/alphacommercehub/issues/71
						 */
						,'[PayPal] City' => dfa($pd, 'City')
						/**
						 * 2017-12-09
						 * 1) "Show the PayPal's `PayerDetails`.`Custom1`
						 * (the payer's account confirmation status)
						 * in the Magento's backend «Payment Information» block":
						 * https://github.com/mage2pro/alphacommercehub/issues/73
						 * 2) "What are the possible values of the `PayerDetails`.`Custom1` field
						 * of a response to the PayPal's `PaymentStatus` API request?":
						 * https://mage2.pro/t/5130
						 */
						,'[PayPal] Custom1' => dfa($pd, 'Custom1')
						/**
						 * 2017-12-09
						 * 1) "Show the PayPal's `PayerDetails`.`Custom2` (the payer's email)
						 * in the Magento's backend «Payment Information» block":
						 * https://github.com/mage2pro/alphacommercehub/issues/74
						 * 2) "What are the possible values of the `PayerDetails`.`Custom2` field
						 * of a response to the PayPal's `PaymentStatus` API request?":
						 * https://mage2.pro/t/5132
						 */
						,'[PayPal] Custom2' => dfa($pd, 'Custom2')
					]);
					break;
			}
			/**
			 * 2017-11-21
			 * "Show the used payment currency in the Magento's «Payment Information» blocks
			 * (backend, frontend, emails) for bank card payments and POLi Payment payments":
			 * https://github.com/mage2pro/alphacommercehub/issues/44
			 * 2017-11-21
			 * "A `CancelURL` response to an interrupted bank card payment": https://mage2.pro/t/4937
			 * 2017-12-08
			 * "Show the used payment currency in the Magento's «Payment Information» blocks
			 * (backend, frontend, emails) for the PayPal payments":
			 * https://github.com/mage2pro/alphacommercehub/issues/70
			 */
			if (in_array($choiceId, ['CC', 'PO', 'PP'])) {
				$this->si('Payment Currency', $e->currencyName());
			}
		}
	}
}