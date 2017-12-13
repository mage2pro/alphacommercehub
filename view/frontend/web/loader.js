// 2017-12-12
// It is implemented by analogy with Dfe_Moip/loader:
// https://github.com/mage2pro/moip/blob/1.3.1/view/frontend/web/loader.js#L1-L45
define([
	'df-lodash', 'Magento_Checkout/js/model/payment/renderer-list', 'uiComponent'
], function(_, rendererList, Component) {'use strict';
/** @type {String} */ var mCode = 'dfe_alpha_commerce_hub';
var p = window.checkoutConfig.payment;
/** @type {Object} */ var config = p[mCode];
if (config) {
	_.each(['CC', 'PP', 'PO'], function(suffix) {
		if (config[suffix].enable) {
			/** @type {String} */ var rType = mCode + '_' + suffix;
			p[rType] = _.assign({}, config['common'], config[suffix]);
			// 2017-07-24
			// `rendererList` is an «observable array»: http://knockoutjs.com/documentation/observableArrays.html
			// https://github.com/magento/magento2/blob/2.2.0-RC1.5/app/code/Magento/Checkout/view/frontend/web/js/model/payment/renderer-list.js#L11
			rendererList.push({component: 'Dfe_AlphaCommerceHub/' + suffix, type: rType,
				/**
				 * 2017-07-24
				 * @used-by Magento_Checkout/js/view/payment/list::createRenderer():
				 *	if (
				 *		renderer.hasOwnProperty('typeComparatorCallback')
				 * 		&& typeof renderer.typeComparatorCallback == 'function'
				 *	) {
				 *		isRendererForMethod = renderer.typeComparatorCallback(
				 *			renderer.type, paymentMethodData.method
				 *		);
				 *	}
				 *	else {
				 *		isRendererForMethod = renderer.type === paymentMethodData.method;
				 *	}
				 * https://github.com/magento/magento2/blob/2.2.0-RC1.5/app/code/Magento/Checkout/view/frontend/web/js/view/payment/list.js#L134-L140
				 * An example of `typeComparatorCallback` from a M2 built-on module:
				 * https://github.com/magento/magento2/blob/2.2.0-RC1.5/app/code/Magento/Vault/view/frontend/web/js/view/payment/vault.js#L35-L44
				 * @param {String} rType
				 * @param {String} mCodeL
				 * @returns {Boolean}
				 */
				typeComparatorCallback: function(rType, mCodeL) {return mCode === mCodeL;}
			});
		}
	});
}
/** 2017-09-06 @uses Class::extend() https://github.com/magento/magento2/blob/2.2.0-rc2.3/app/code/Magento/Ui/view/base/web/js/lib/core/class.js#L106-L140 */
return Component.extend();
});