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
	protected function config() {return
		$this->option('CC') + $this->option('PP') + $this->option('PO') +
		['common' => ['isTest' => $this->s()->test(), 'titleBackend' => $this->m()->titleB()]]
	;}

	/**
	 * 2017-12-12
	 * @used-by config()
	 * @param string $id
	 * @return array(string => mixed)
	 */
	private function option($id) {$s = $this->s(); return [$id => [
		'enable' => $s->v("$id/enable") && $s->applicableForQuote($id)
		,'title' => $this->m()->optionTitle($id)
	]];}
}