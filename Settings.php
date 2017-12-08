<?php
namespace Dfe\AlphaCommerceHub;
use Df\Payment\Settings\_3DS;
use Df\Payment\Settings\Options as O;
use Dfe\AlphaCommerceHub\Source\Option as OptionSource;
// 2017-10-25
/** @method static Settings s() */
final class Settings extends \Df\Payment\Settings {
	/**
	 * 2017-12-09 «Test API Domain» / «Live API Domain»
	 * "Provide an ability to a Magento backend user to choose a different AlphaCommerceHub's API domain"?
	 * https://github.com/mage2pro/alphacommercehub/issues/66
	 * @used-by \Dfe\AlphaCommerceHub\Method::urlBase()
	 * @return string
	 */
	function apiDomain() {return $this->testable();}

	/**
	 * 2017-11-02
	 * @used-by \Dfe\AlphaCommerceHub\Charge::pCharge()
	 * @return _3DS
	 */
	function _3ds() {return dfc($this, function() {return new _3DS($this);});}

	/**
	 * 2017-10-28
	 * @used-by \Dfe\AlphaCommerceHub\ConfigProvider::options()
	 * @return O
	 */
	function options() {return $this->_options(OptionSource::class);}

	/**
	 * 2017-12-09 «Test Pay Page Domain» / «Live Pay Page Domain»
	 * "Provide an ability to a Magento backend user to choose a different AlphaHPP domain"?
	 * https://github.com/mage2pro/alphacommercehub/issues/80
	 * @used-by \Dfe\AlphaCommerceHub\Method::urlBase()
	 * @return string
	 */
	function payPageDomain() {return $this->testable();}

	/**
	 * 2017-11-02 «Test Pay Page Path» / «Live Pay Page Path»
	 * "[AlphaCommerceHub] What is a «pay page path»"? https://mage2.pro/t/4856
	 * @used-by \Dfe\AlphaCommerceHub\Method::urlBase()
	 * @return string
	 */
	function payPagePath() {return $this->testable();}
}