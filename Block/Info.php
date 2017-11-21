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
		if ($this->choice()->isBankCard()) {
			$this->si([
				'Card Number' => "{$e->r('PaymentInfo/BIN')} ···· ({$e->r('Result/CardType')})"
				,'Cardholder' => $e->r('Result/CardHolder')
			]);
			$this->siEx(['Issuer Country' => df_country_ctn($e->r('PaymentInfo/PaymentIssuerCountry'))]);
		}
	}
}