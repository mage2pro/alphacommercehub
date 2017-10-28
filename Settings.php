<?php
namespace Dfe\AlphaCommerceHub;
use Df\Payment\Settings\Options as O;
use Dfe\AlphaCommerceHub\Source\Option as OptionSource;
// 2017-10-25
/** @method static Settings s() */
final class Settings extends \Df\Payment\Settings {
	/**
	 * 2017-10-28
	 * @used-by \Dfe\AlphaCommerceHub\ConfigProvider::options()
	 * @return O
	 */
	function options() {return $this->_options(OptionSource::class);}
}