<?php
namespace Dfe\AlphaCommerceHub\Block;
use Dfe\AlphaCommerceHub\W\Event;
/**
 * 2017-11-19
 * @final Unable to use the PHP «final» keyword here because of the M2 code generation.
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
	}
}