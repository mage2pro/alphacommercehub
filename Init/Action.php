<?php
namespace Dfe\AlphaCommerceHub\Init;
use Dfe\AlphaCommerceHub\Method as M;
use Dfe\AlphaCommerceHub\Settings as S;
/**
 * 2017-10-25
 * @method M m()
 * @method S s()
 */
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
	 * 2017-10-26
	 * Note 1.
	 * «Merchants communicate with the AlphaHPP by means of submission of hidden parameters in a HTTPS POST.
	 * These parameters retrieve the necessary configuration for the merchant paypage
	 * and seed necessary information for the transaction for example the currency and amount.
	 * The customer is then redirected to the hosted payment page
	 * or in the case of an iFrame deployment, the iFrame is displayed on the merchants site
	 * (under the merchants control).
	 * The results of the payment request are returned to a url
	 * configured by the merchant as the result page.
	 * Similar to the request hidden parameters are posted
	 * that inform the merchant of the result of the payment transaction.»
	 * http://developer.alphacommercehub.com.au/docs/alphahpp-#technical-integration-requirements
	 * Note 2.
	 * [AlphaCommerceHub] What is the difference between the «Test» and «Sandbox» environments?
	 * https://mage2.pro/t/4745
	 * 2017-10-31
	 * «A pay page URL will be provided in the activation email to the merchant»: https://mage2.pro/t/4785/2
	 * @override
	 * @see \Df\Payment\Init\Action::redirectUrl()
	 * @used-by \Df\Payment\Init\Action::action()
	 */
	protected function redirectUrl():string {return $this->m()->urlBase(true);}
}