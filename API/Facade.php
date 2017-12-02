<?php
namespace Dfe\AlphaCommerceHub\API;
// 2017-12-03
/** @see \Dfe\AlphaCommerceHub\API\Facade\PaymentStatus */
abstract class Facade extends \Df\API\Facade {
	/**
	 * 2017-12-03
	 * @override
	 * @see \Df\API\Facade::path()
	 * @used-by \Df\API\Facade::p()
	 * @param int|string|null $id
	 * @param string|null $suffix
	 * @return string
	 */
	final protected function path($id, $suffix) {return df_class_l($this);}
}