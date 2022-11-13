<?php
namespace Dfe\AlphaCommerceHub;
/**
 * 2017-10-28
 * @method Method m()
 * @method Settings s()
 */
final class ConfigProvider extends \Df\Payment\ConfigProvider {
	/**
	 * 2017-12-12
	 * @override
	 * @see \Df\Payment\ConfigProvider::config()
	 * @used-by \Df\Payment\ConfigProvider::getConfig()
	 * @return array(string => mixed)
	 */
	protected function config():array {return
		$this->option('CC') + $this->option('PP') + $this->option('PO') +
		['common' => ['isTest' => $this->s()->test(), 'titleBackend' => $this->m()->titleB()]]
	;}

	/**
	 * 2017-12-12
	 * @used-by self::config()
	 * @param string $id
	 * @return array(string => mixed)
	 */
	private function option($id) {$s = $this->s(); return [$id => [
		'enable' =>
			$s->b("$id/enable")
			&& $s->applicableForQuoteByMinMaxTotal($id)
			&& $s->applicableForQuoteByCountry($id)
		/**
		 * 2017-12-13
		 * 1) "Provide an ability to the Magento backend users (merchants)
		 * to set up the «Require the billing address?» option separately
		 * for each AlphaCommerceHub's payment option (bank cards, PayPal, POLi Payments, etc.)":
		 * https://github.com/mage2pro/alphacommercehub/issues/84
		 * 2) It is implemented by analogy with @see \Df\Payment\ConfigProvider::config()
		 * https://github.com/mage2pro/core/blob/3.4.10/Payment/ConfigProvider.php#L123
		 * @see \Df\Payment\Settings::requireBillingAddress():
		 * https://github.com/mage2pro/core/blob/3.4.10/Payment/Settings.php#L189-L211
		 */
		,'requireBillingAddress' => $s->b("$id/requireBillingAddress")
		,'title' => $this->m()->optionTitle($id)
	]];}
}