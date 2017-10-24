<?php
namespace Dfe\AlphaCommerceHub;
// 2017-10-25
final class Method extends \Df\PaypalClone\Method {
	/**
	 * 2017-10-25
	 * @override
	 * @see \Df\Payment\Method::amountLimits()
	 * @used-by \Df\Payment\Method::isAvailable()
	 * @return null
	 */
	protected function amountLimits() {return null;}
}