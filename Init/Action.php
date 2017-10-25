<?php
namespace Dfe\AlphaCommerceHub\Init;
// 2017-10-25
/** @method \Dfe\AlphaCommerceHub\Method m() */
final class Action extends \Df\PaypalClone\Init\Action {
	/**
	 * 2017-10-25
	 * Note 1. «Where is my merchant interface (AlphaHub Portal) located?» https://mage2.pro/t/4744
	 * Note 2.
	 * «Which formal rules should my Magento 2 extension use to choose the API's production domain
	 * when a merchant has switched my extension from the test/sandbox mode to the production mode?»
	 * https://mage2.pro/t/4775
	 * Note 3.
	 * Will the «Pay Pages» paths be preserved after switching the merchant's account
	 * from test/sandox mode to the production one? https://mage2.pro/t/4776
	 * @override
	 * @see \Df\Payment\Init\Action::redirectUrl()
	 * @used-by \Df\Payment\Init\Action::action()
	 * @return string
	 */
	protected function redirectUrl() {return null;}
}