<?php
namespace Dfe\AlphaCommerceHub\Settings;
use Df\Payment\Settings\_3DS;
/**
 * 2017-12-12
 * @used-by \Dfe\AlphaCommerceHub\Settings::card()
 * @method static Card s()
 */
final class Card extends \Df\Payment\Settings {
	/**
	 * 2017-11-02
	 * @used-by \Dfe\AlphaCommerceHub\Charge::pCharge()
	 * @return _3DS
	 */
	function _3ds() {return dfc($this, function() {return new _3DS($this);});}

	/**
	 * 2017-12-12
	 * @override
	 * @see \Df\Payment\Settings::prefix()
	 * @used-by \Df\Config\Settings::v()
	 */
	protected function prefix():string {return dfc($this, function() {return parent::prefix() . '/CC';});}
}