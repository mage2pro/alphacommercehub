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
	 * @used-by \Df\Payment\Block\Info::_prepareSpecificInformation()
	 */
	final protected function prepare() {
		$e = $this->e(); /** @var Event $e */
		/**
		 * 2017-11-20
		 * "Show the AlphaCommerceHub's payment ID (`PaymentID`)
		 * in the Magento's backend «Payment Information» block":
		 * https://github.com/mage2pro/alphacommercehub/issues/41
		 */
		$this->siEx('AlphaCommerceHub ID', $e->idE());
		$this->si('Payment Option', $this->choiceT());
		$this->tm()->responseF();
		/**
		 * 2017-11-21
		 * 1) "What is the recommended way to detect the chosen payment option
		 * from a `SuccessURL` response?" https://mage2.pro/t/4978
		 * 2) "What is the recommended way to detect the chosen payment option
		 * from a `CancelURL` response?" https://mage2.pro/t/4979
		 */
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
		 */
		if (in_array($choiceId, ['CC', 'PO'])) {
			$this->si('Payment Currency', $e->currencyName());
		}
	}
}