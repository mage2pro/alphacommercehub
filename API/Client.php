<?php
namespace Dfe\AlphaCommerceHub\API;
use Dfe\AlphaCommerceHub\Method as M;
// 2017-12-02
final class Client extends \Df\API\Client {
	/**
	 * 2017-12-02
	 * @override
	 * @see \Df\API\Client::urlBase()
	 * @used-by \Df\API\Client::__construct()
	 * @used-by \Df\API\Client::_p()
	 * @return string
	 */
	protected function urlBase() {$m = dfpm($this); /** @var M $m */ return $m->urlBase(false);}
}