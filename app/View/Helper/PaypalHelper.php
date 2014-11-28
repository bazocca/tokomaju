<?php
define("DEFAULT_EWP_CERT_PATH", WWW_ROOT."certificates/public.pem");
define("DEFAULT_EWP_PRIVATE_KEY_PATH", WWW_ROOT."certificates/private.pem");
define("DEFAULT_EWP_PRIVATE_KEY_PWD", "admin");
define("PAYPAL_CERT_PATH", WWW_ROOT."certificates/paypal.txt");

/** Paypal Helper part of the PayPal IPN plugin.
  *
  * @author Nick Baker
  * @link http://www.webtechnick.com
  * @license MIT
  */
class PaypalHelper extends AppHelper {
  var $helpers = array('Form', 'Html' , 'Get');
  
  /**
    *  Setup the config based on the PaypalIpnConfig in /config/PaypalIpnConfig.php
    */
  public function __construct(View $View, $settings = array())
  {
    App::uses('PaypalIpnConfig' , 'Config');
    $this->config = new PaypalIpnConfig();
    parent::__construct($View);  
  }
  
  function __jsGenerator($name)
  {
	return '
<script type="text/javascript">
	$(document).ready(function(){
		$("input#paypal_'.$name.'").change(function(){
			$("input[type=hidden][name='.$name.']").val($(this).val());
		});
	});
</script>
	';
  }
  
  function __fungsiCurl($url)
  {
     $data = curl_init();
     curl_setopt($data, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($data, CURLOPT_URL, $url);
	 curl_setopt($data, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
     $hasil = curl_exec($data);
     curl_close($data);
     return $hasil;
  }
  
  function exchange_rate($currency = NULL)
  {
  	if(is_null($currency))
	{
		$currency = "USD";
	}
	else
	{
		$currency = strtoupper($currency);
	}
	
	$data = $this->__fungsiCurl('http://www.bi.go.id/web/id/Moneter/Kurs+Bank+Indonesia/Kurs+Transaksi/');
	$pecah1 = explode("<font size=-2 face='verdana,arial' color='#000000'>".$currency."  </font></td>", $data);	
	$pecah2 = explode("<td valign=middle align=center height=20><font size=-2 face='verdana,arial' color=#000000><a href='#' onclick=", $pecah1[1]);
	$pecah3 = explode('</font></td>', $pecah2[0]);
	$pecah4 = explode('>', $pecah3[1]);
	$kurs = str_replace(',', '', $pecah4[count($pecah4)-1]);
	
	$this->Setting = ClassRegistry::init('Setting');	
	if($currency == "USD")
	{
		if(!empty($kurs) && is_numeric($kurs))
		{
			$temp = $this->Setting->findByKey('usd_sell');
			
			$this->Setting->id = $temp['Setting']['id'];
			$this->Setting->saveField("value" , $kurs);
		}
		else 
		{
			$temp = $this->Setting->findByKey('usd_sell');
			$kurs = $temp['Setting']['value'];
		}
	}
	
	return $kurs;
  }
  
function initialize($type=NULL , $data = array())
{
  $content = "";
	$options = array();
  if($type == 'cart')
	{
		// SPECIAL CASE PAYMENT TYPE !!
		$options['type'] = $type;
		$options['test'] = true;
		$options['items'] = array();
		$item = array();
		
		foreach ($data['shoppingcart'] as $key => $value) 
		{	
			$item['item_number'] = $value['detail']['Entry']['id'];
			$item['item_name'] = $value['detail']['Entry']['title'];
			$item['amount'] = number_format($value['detail']['EntryMeta']['price'] , 2);
			$item['quantity'] = $value['quantity'];
			array_push($options['items'] , $item);
		}
		if(!empty($data['shoppingongkir']['value']))
		{
			$options['handling_cart'] = number_format($data['shoppingongkir']['value'] , 2);
		}
		
		$options['custom'] = $_SESSION['shoppingongkir']['to'];
		$content = $this->button($options);
	}
	else
	{
		if(!empty($data))
		{
			$this->Get->create($data);
		}
		else
		{
			$data = $this->Get->getData();
			if(empty($data))
			{
				return false;
			}
		}
		
		foreach ($data['myEntry']['EntryMeta'] as $key => $value) 
		{
			$data['myEntry']['EntryMeta'][(substr($value['key'], 0,5)=='form-'?substr($value['key'], 5):$value['key'])] = $value['value']; 
		}
		
		// CHECK PRODUCT QUANTITY FIRST !!
		if(empty($data['myEntry']['EntryMeta']['quantity']))
		{
			$content = "<p class='help-block'>&lt; This product is already sold out &gt;</p>";
			return $content;
		}
		
		if($type == 'addtocart')
		{
			$targetAction = explode("/", $_SERVER['REQUEST_URI']);
			unset($targetAction[count($targetAction)-1]);
			$targetAction = implode("/", $targetAction)."/";
			
			$content = "
<form action='".$targetAction."' method='POST'>
	<input type='hidden' name='type' value='addtocart'>
	<input type='hidden' name='item_number' value='{$data['myEntry']['Entry']['id']}'>
	<select name='quantity' style='width:".calcNumberWidth($data['myEntry']['EntryMeta']['quantity'])."px;'>
		";
		
			for($i=1 ; $i <= $data['myEntry']['EntryMeta']['quantity'] ; ++$i) 
			{
				$content .= "<option value='$i'>$i</option>";
			}
			
			$content .= "
	</select>
	<input type='image' src='https://www.sandbox.paypal.com/en_US/i/btn/btn_cart_LG.gif' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!' title='PayPal - The safer, easier way to pay online!'>	
</form>		
			";
		}
		else
		{
			$options['type'] = 'paynow';
			$options['item_number'] = $data['myEntry']['Entry']['id'];
			$options['item_name'] = $data['myEntry']['Entry']['title'];
			$options['amount'] = $data['myEntry']['EntryMeta']['price'];
			$options['quantity'] = $data['myEntry']['EntryMeta']['quantity'];
			$options['test'] = true;
			$content = $this->button($options);
		}
	}
	return $content;
}
  
  /**
    *  function button will create a complete form button to Pay Now, Donate, Add to Cart, or Subscribe using the paypal service.
    *  Configuration for the button is in /config/paypal_ip_config.php
    *  
    *  for this to work the option 'item_name' and 'amount' must be set in the array options or default config options.
    *
    *  Example: 
    *     $this->Paypal->button('Pay Now', array('amount' => '12.00', 'item_name' => 'test item'));
    *     $this->Paypal->button('Subscribe', array('type' => 'subscribe', 'amount' => '60.00', 'term' => 'month', 'period' => '2'));
    *     $this->Paypal->button('Donate', array('type' => 'donate', 'amount' => '60.00'));
    *     $this->Paypal->button('Add To Cart', array('type' => 'addtocart', 'amount' => '15.00'));
    *     $this->Paypal->button('Unsubscribe', array('type' => 'unsubscribe'));
    *     $this->Paypal->button('Checkout', array(
    *      'type' => 'cart',
    *      'items' => array(
    *         array('item_name' => 'Item 1', 'amount' => '120', 'quantity' => 2, 'item_number' => '1234'),
    *         array('item_name' => 'Item 2', 'amount' => '50'),
    *         array('item_name' => 'Item 3', 'amount' => '80', 'quantity' => 3),
    *       )
    *     ));
    *  Test Example:
    *     $this->Paypal->button('Pay Now', array('test' => true, 'amount' => '12.00', 'item_name' => 'test item'));
    *
    * @access public
    * @param String $title takes the title of the paypal button (default "Pay Now" or "Subscribe" depending on option['type'])
    * @param Array $options takes an options array defaults to (configuration in /config/PaypalIpnConfig.php)
    * 
    *   helper_options:  
    *      test: true|false switches default settings in /config/PaypalIpnConfig.php between settings and testSettings
    *      type: 'paynow', 'addtocart', 'donate', 'unsubscribe', 'cart', or 'subscribe' (default 'paynow')
    *    
    *    You may pass in api name value pairs to be passed directly to the paypal form link.  Refer to paypal.com for a complete list.
    *    some paypal API examples: 
    *      amount: float value
    *      notify_url: string url
    *      item_name: string name of product.
    *      etc...
    */
  function button($options = array()){
    $defaults = (isset($options['test']) && $options['test'] === TRUE) ? $this->config->testSettings : $this->config->settings; 
    $options = array_merge($defaults, $options);
    $options['type'] = (isset($options['type'])) ? $options['type'] : "paynow";
    $default_title = "";
	$button_url = 'https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif';
	
    switch($options['type']){
      case 'subscribe': //Subscribe
        $options['cmd'] = '_xclick-subscriptions';
        $default_title = 'Subscribe';
        $options['no_note'] = 1;
        $options['no_shipping'] = 1;
        $options['src'] = 1;
        $options['sra'] = 1;
        $options = $this->__subscriptionOptions($options);
        break;
      case 'addtocart': //Add To Cart
        $options['cmd'] = '_cart';
        $options['add'] = '1';
        $default_title = 'Add To Cart';
        break;
      case 'donate': //Doante
        $options['cmd'] = '_donations';
        $default_title = 'Donate';
        break;
      case 'unsubscribe': //Unsubscribe
        $options['cmd'] = '_subscr-find';
        $options['alias'] = $options['business'];
        $default_title = 'Unsubscribe';
        break;
      case 'cart': //upload cart
        $options['cmd'] = '_cart';
        $options['upload'] = 1;
		$options['no_shipping'] = 2;
        $default_title = 'Checkout';
        $options = $this->__uploadCartOptions($options);
		$button_url = $this->get_host_name()."img/paypal_checkout.png";
        break;
      default: //Pay Now
        $options['cmd'] = '_xclick';
        $default_title = 'Pay Now';
        break;
    }	
    
	App::import('Vendor', 'ewpservices');
	$this->EWPServices = new EWPServices();
	
	$result = $this->EWPServices->encryptButton($options , DEFAULT_EWP_CERT_PATH , DEFAULT_EWP_PRIVATE_KEY_PATH , DEFAULT_EWP_PRIVATE_KEY_PWD , PAYPAL_CERT_PATH , $options['server'] , $button_url);
	
	if($result['status'])
	{
		return $result['encryptedButton']; 
	}
	else 
	{
		return $result['error_msg'];
	}
  }
  
  /**
   *  __hiddenNameValue constructs the name value pair in a hidden input html tag
   * @access private
   * @param String name is the name of the hidden html element.
   * @param String value is the value of the hidden html element.
   * @access private
   * @return Html form button and close form
   */
  function __hiddenNameValue($name, $value)
  {
  	$result = "";
  	if($name == 'quantity')
	{
		$result = "<select style='width:".calcNumberWidth($value)."px;' name='$name'>";
		for($i=1 ; $i<=$value ; ++$i)
		{
			$result .= "<option value='$i'>$i</option>";
		}
		$result .= "</select>";
	}
	else
	{
		$result = "<input type='hidden' name='$name' value='$value' />";
	}
    return $result;
  }
  
  /**
   *  __submitButton constructs the submit button from the provided text
   * @param String text | text is the label of the submit button.  Can use plain text or image url.
   * @access private
   * @return Html form button and close form
   */
  function __submitButton($text, $type)
  { 
    $result = "</div>";
	
    if($type == "paynow")
	{
		$result .= '<input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" title="PayPal - The safer, easier way to pay online!"><img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">';
	}
	else
	{
		$result .= $this->Form->end(array('label' => $text));
	}
    return $result;
  }
  
  /**
    * __subscriptionOptions conversts human readable subscription terms 
    * into paypal terms if need be
    *  @access private
    *  @param array options | human readable options into paypal API options
    *     INT period //paypal api period of term, 2, 3, 1
    *     String term //paypal API term //month, year, day, week
    *     Float amount //paypal API amount to charge for perioud of term.
    *  @return array options 
    */
  function __subscriptionOptions($options = array()){
    //Period... every 1, 2, 3, etc.. Term
    if(isset($options['period'])){
      $options['p3'] = $options['period'];
      unset($options['period']);
    }
    //Mount billed
    if(isset($options['amount'])){
      $options['a3'] = $options['amount'];
      unset($options['amount']);
    }
    //Terms, Month(s), Day(s), Week(s), Year(s)
    if(isset($options['term'])){
      switch($options['term']){
        case 'month': $options['t3'] = 'M'; break;
        case 'year': $options['t3'] = 'Y'; break;
        case 'day': $options['t3'] = 'D'; break;
        case 'week': $options['t3'] = 'W'; break;
        default: $options['t3'] = $options['term'];
      }
      unset($options['term']);
    }
    
    return $options;
  }
  
  /**
    * __uploadCartOptions converts an array of items into paypal friendly name/value pairs
    * @access private
    * @param array of options that will be returned with proper paypal friendly name/value pairs for items
    * @return array options
    */
    function __uploadCartOptions($options = array()){
      if(isset($options['items']) && is_array($options['items'])){
        $count = 1;
        foreach($options['items'] as $item){
          foreach($item as $key => $value){
            $options[$key.'_'.$count] = $value;
          }
          $count++;
        }
        unset($options['items']);
      }
      return $options;
    }
}
?>
