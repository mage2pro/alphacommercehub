<?php
namespace Dfe\AlphaCommerceHub\API;
// 2017-12-03
final class Facade extends \Df\API\Facade {
	/**
	 * 2017-12-03
	 * @override
	 * @see \Df\API\Facade::path()
	 * @used-by \Df\API\Facade::p()
	 * @param int|string|null $id
	 * @param string|null $suffix
	 * @return string
	 */
	protected function path($id, $suffix) {return $suffix;}
}