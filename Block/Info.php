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
					 * 2017-11-21
					 * "Show the `PayerID` and `token` response parameters for a successful PayPal payment
					 * in the Magento's backend «Payment Information» block":
					 * https://github.com/mage2pro/alphacommercehub/issues/45
					 */
					$this->siEx(['PayerID' => $e->r('PayerID'), 'token' => $e->r('EC-5P235520W1795473P')]);
					break;
			}
			/**
			 * 2017-11-21
			 * "Show the used payment currency in the Magento's «Payment Information» blocks
			 * (backend, frontend, emails) for bank card payments and POLi Payment payments":
			 * https://github.com/mage2pro/alphacommercehub/issues/44
			 * 2017-11-21
			 * "A `CancelURL` response to an interrupted bank card payment": https://mage2.pro/t/4937
			 */
			if (in_array($choiceId, ['CC', 'PO'])) {
				$this->si('Payment Currency', $e->currencyName());
			}
		}
	}
}