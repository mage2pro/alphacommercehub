<?php
/**
 * 2017-12-14
 * 1) "The module should treat «168950.00» as the expected format for the `Result.Amount` field
 * of the PayPal's `PaymentStatus` responses,
 * because AlphaCommerceHub does not fix this bug during the last 29 hours":
 * https://github.com/mage2pro/alphacommercehub/issues/86
 * 2) The function transforms «168950.00» to «168950».
 * @used-by \Dfe\AlphaCommerceHub\API\Facade\PayPal::status()
 * @used-by \Dfe\AlphaCommerceHub\Test\CaseT\PayPal\PaymentStatus::t01()
 */
function dfe_alphacommercehub_fix_amount_bug(string $a):string {return df_first(explode('.', $a));}