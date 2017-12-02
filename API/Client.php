<?php
namespace Dfe\AlphaCommerceHub\API;
use Dfe\AlphaCommerceHub\Method as M;
// 2017-12-02
final class Client extends \Df\API\Client {
	/**
	 * 2017-12-02
	 * @override
	 * @see \Df\API\Client::_construct()
	 * @used-by \Df\API\Client::__construct()
	 */
	protected function _construct() {parent::_construct(); $this->reqJson(); $this->resJson();}

	/**
	 * 2017-12-02
	 * «To distinguish whether the message content is sent as XML or JSON
	 * please provide `Content-Type` as either `application/xml` or `application/json`»
	 * http://developer.alphacommercehub.com.au/docs/api-integration-guidenov-2017#api-reference
	 * @override
	 * @see \Df\API\Client::headers()
	 * @used-by \Df\API\Client::__construct()
	 * @used-by \Df\API\Client::p()
	 * @return array(string => string)
	 */
	protected function headers() {return ['Content-Type' => 'application/json'];}

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