<?php
/************
  * Use these settings to set defaults for the Paypal Helper class.
  * The PaypalHelper class will help you create paynow, subscribe, donate, or addtocart buttons for you.
  * 
  * All these options can be set on the fly as well within the helper
  */
  
class PaypalIpnConfig {

  /************
    * Each settings key coresponds to the Paypal API.  Review www.paypal.com for more. 
    */
  var $settings = array(
    'charset' => 'UTF-8',
    'business' => 'andybasuki88@gmail.com', //'live_email@paypal.com', //Your Paypal email account
    'server' => 'https://www.paypal.com', //Main paypal server.
    'notify_url' => 'http://creazi.co.id/trial/paypal_ipn/process', //'http://www.yoursite.com/paypal_ipn/process', //Notify_url... set this to the process path of your paypal_ipn::instant_payment_notification::process action
    'currency_code' => 'USD', //Currency
    'lc' => 'US', //Locality
    'item_name' => 'Paypal_IPN', //Default item name.
    'amount' => '15.00', //Default item amount.
    'return' => 'http://creazi.co.id/trial',
    'cert_id' => 'L6RAJVAJYSZ2U',
//    'cancel_return' => '',
  );
  
  /***********
    * Test settings to test with using a sandbox paypal account.
    */
  var $testSettings = array(
  	'charset' => 'UTF-8',
    'business' => 'seller_1359517685_biz@yahoo.com', //'sandbox_email@paypal.com',
    'server' => 'https://www.sandbox.paypal.com',
    'notify_url' => 'http://creazi.co.id/trial/paypal_ipn/process', //'http://www.yoursite.com/paypal_ipn/process',
    'currency_code' => 'USD',
    'lc' => 'US',
    'item_name' => 'Paypal_IPN',
    'amount' => '15.00',
    'return' => 'http://creazi.co.id/trial',
    'cert_id' => 'L6RAJVAJYSZ2U',
//    'cancel_return' => '',
  );

}
?>
