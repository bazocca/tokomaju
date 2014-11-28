<?php
class InstantPaymentNotificationsController extends AppController {
	public $layout='frontend';
	var $name = 'InstantPaymentNotifications';	
	public $components = array('Validation');
	public $helpers = array('Form', 'Html', 'Js', 'Time', 'Get','Paypal');
	
	var $testEmail = "andy_basuki_88@yahoo.com";
	private $frontEndFolder = '/FrontEnds/';
	private $backEndFolder = '/BackEnds/';
	/**
	  * beforeFilter makes sure the process is allowed by auth
	  *  since paypal will need direct access to it.
	  */
	function beforeFilter(){
	  parent::beforeFilter();
	  if(isset($this->Auth)){
	  	$this->Auth->allow('process','shoppingcart','deletecart','ajaxcity','ajaxcost');
	  }
	  if(isset($this->Security) && $this->request->action == 'process'){
	    $this->Security->validatePost = false;
	  }
	}
	
	function deletecart($id)
	{
		foreach ($_SESSION['shoppingcart'] as $key => $value) 
		{
			if($value['item_number'] == $id)
			{
				unset($_SESSION['shoppingcart'][$key]);
				break;
			}
		}
		$this->redirect('/shoppingcart/step1');
	}
	
	function ajaxcost()
	{
		$this->layout='ajax';
		$this->autoRender = FALSE;
		unset($_SESSION['shoppingongkir']);
		if(!empty($_POST['from']) && !empty($_POST['to']) && !empty($_POST['courier']))
		{
			$result = $this->InstantPaymentNotification->get_cost($_POST['from'] , $_POST['to'] , $_POST['courier']);
			
			if($result['error'] == 0)
			{
				$kurs = $this->exchange_rate("USD");
				$result['value'] = round($result['value'] / $kurs , 2);
				$_SESSION['shoppingongkir']['value'] = $result['value'];
				$_SESSION['shoppingongkir']['from'] = $result['from'];
				$_SESSION['shoppingongkir']['to'] = $result['to'];
				$_SESSION['shoppingongkir']['courier'] = $_POST['courier'];
			}
		}
		else
		{
			$result = "failed";
		}
		echo json_encode($result);
	}
	
	function ajaxcity()
	{
		$this->layout='ajax';
		$this->autoRender = FALSE;
		$return_arr = array();
		
		//tangkap variable type untuk mengetahui apakan kota asal (origin) atau kota tujuan (destination)
		$type = $_GET['type'];
		
		//panggil fungsi get_city() untuk mendapatkankan nama-nama kota.
		 $cities = $this->InstantPaymentNotification->get_city(trim($_GET['term']),$type) ;
		 
		 if($cities['error'] == 0)
		 {
		 	 $i=0;
			 foreach ($cities['value']->item as $value) 
			 {
				 $row_array['id_kota'] = $i;
				 $row_array['nama_kota'] = strval($value);
				
				 array_push($return_arr,$row_array);
				 $i++;
			 }
			 
			 echo json_encode($return_arr);
		 }
		 else
		 {
		 	 echo json_encode($cities);
		 } 
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
	
	function shoppingcart()
	{
		$this->setTitle('Shopping Cart Detail');
		$myRenderFile = "shoppingcartstep1";
		$data = array();
		
		if(empty($_SESSION['shoppingcart']))
		{
			$data['error'] = "There's no product in your shopping cart right now.";
		}
		else
		{
			$data['step'] = $this->request->params['step'];
			if($data['step'] == "step1")
			{
				// shopping cart detail !!
				$data['shoppingcart'] = $_SESSION['shoppingcart'];
				foreach ($data['shoppingcart'] as $key => $value) 
				{
					$myEntry = $this->Entry->meta_details(NULL , NULL , NULL , $value['item_number']);
					
					if(empty($myEntry['EntryMeta']['quantity']))
					{
						unset($_SESSION['shoppingcart'][$key]);
					}
					else
					{
						$data['shoppingcart'][$key]['detail'] = $myEntry;
					}
				}
				
				// JNE detail if existed !!
				$data['shoppingongkir'] = $_SESSION['shoppingongkir'];
			}
			else if($data['step'] == "step2" && isset($_POST['tostep2']))
			{
				// for confirmation before goin' to paypal !!
				// shopping cart detail !!
				$tempCart = $_POST['data'];
				$beforeSession = array();
				$totalweight = 0;
				
				foreach ($tempCart['item_number'] as $key => $value) 
				{
					$beforeSession[$key]['item_number'] = $value;
					$beforeSession[$key]['quantity'] = $tempCart['quantity'][$key];
					
					$myEntry = $this->Entry->meta_details(NULL , NULL , NULL , $value);
					
					if(empty($myEntry['EntryMeta']['quantity']) || $myEntry['EntryMeta']['quantity'] < $tempCart['quantity'][$key])
					{
						throw new NotFoundException('Error 404 - Not Found'); 
						return;
					}
					
					$weight = (empty($myEntry['EntryMeta']['weight'])?0:$myEntry['EntryMeta']['weight']);
					$totalweight += $tempCart['quantity'][$key] * 1.00 * $weight;
					
					$data['shoppingcart'][$key]['detail'] = $myEntry;
					$data['shoppingcart'][$key]['quantity'] = $tempCart['quantity'][$key];
				}
				
				// RENEW THE SHOPPING CART SESSION !!
				$_SESSION['shoppingcart'] = $beforeSession;
				
				// JNE detail if existed !!
				if(isset($_SESSION['shoppingongkir']))
				{
					$data['shoppingongkir'] = $_SESSION['shoppingongkir'];
					$data['shoppingongkir']['value'] = round($data['shoppingongkir']['value'] * $totalweight , 2);
				}
				
				$myRenderFile = "shoppingcartstep2";
			}
			else
			{
				throw new NotFoundException('Error 404 - Not Found'); 
				return;
			}
		}
		$this->set('data' , $data);
		$this->render($this->frontEndFolder.$myRenderFile);
	}
	
	/**
	  * Paypal IPN processing action..
	  * This action is the intake for a paypal_ipn callback performed by paypal itself.
	  * This action will take the paypal callback, verify it (so trickery) and save the transaction into your database for later review
	  *
	  * @access public
	  * @author Nick Baker
	  */
	function process()
	{
	  if($this->InstantPaymentNotification->isValid($_POST))
	  {
	      $notification = $this->InstantPaymentNotification->buildAssociationsFromIPN($_POST);
		  $this->log($notification,'paypal_ipn');
		  $this->__processTransaction($notification);
	  }
	  $this->redirect('/');
	}
	
	/**
    * __processTransaction is a private callback function used to log a verified transaction
    * @access private
    */
	private function __processTransaction($data)
	{
		$ipn = $data['InstantPaymentNotification'];
		$items = $data['PaypalItem'];
		if($ipn['payment_status'] == 'Completed') //Yay!  We have monies!
		{	
			// Check the amount of payment is correct or not !!
			$state = "success";
			foreach ($items as $key => $value) 
			{
				$src = $this->Entry->meta_details(NULL , NULL , NULL , $value['item_number']);
				$totalCost = number_format($src['EntryMeta']['price'] * 1.00 * $value['quantity'] , 2);
				if($totalCost > $value['mc_gross'])
				{
					$state = "invalid-price";
				}
				else if($src['EntryMeta']['quantity'] < $value['quantity'])
				{
					$state = "invalid-quantity";
				}
				else
				{
					$this->__updateDbTransaction($value);
				}
				
				if($state != "success")
				{
					break;
				}
			}
			
			// CHECK THE VALIDITY OF SHIPPING DETAIL !!
			if($state == "success" && $ipn['mc_handling'] > 0)
			{
				$destination = $ipn['custom'];
				
				if(!empty($destination))
				{
					$data['InstantPaymentNotification']['address_city'] = $destination;
					$data['InstantPaymentNotification']['address_country'] = "Indonesia";
					$data['InstantPaymentNotification']['address_country_code'] = "ID";
					
					$ipn = $data['InstantPaymentNotification'];
				}
				else
				{
					$state = "invalid-city";
				}
			}
			$this->__sendEmail($state, $data); 
		}
		else 
		{
			//Oh no, better look at this transaction to determine what to do; like email a decline letter.
		}
	}
	
	private function __updateDbTransaction($item) // temporarily, we are just subtracting quantity of the products !!
	{	
		$temp = $this->EntryMeta->find('first' , array(
			'conditions' => array(
				'EntryMeta.entry_id' => $item['item_number'],
				'EntryMeta.key' => 'form-quantity'
			)
		));
		
		$this->EntryMeta->id = $temp['EntryMeta']['id'];
		$this->EntryMeta->saveField('value' , $temp['EntryMeta']['value'] - $item['quantity']);
	}
	
	private function __calc_each_item_cost($items = array() , $currency)
	{
		$itemtotal = 0;
		$message = "";
		foreach ($items as $key => $value) 
		{
			$itemtotal += $value['mc_gross'];
			$message .= '
Product # '.($key+1).' :
Item Name: '.$value['item_name'].'<br/>
Quantity: '.$value['quantity'].'<br/>
Price: $'.$value['mc_gross'].' '.$currency.'<br/>
<br/>
';
		}
		
		$result = array();
		$result['itemtotal'] = $itemtotal;
		$result['message'] = $message;
		return $result;
	}

	private function __sendEmail($state , $data)
	{	
		$ipn = $data['InstantPaymentNotification'];
		$items = $data['PaypalItem'];
		$email = array();
		
		$email['data']['mySetting'] = $this->mySetting;
		$email['id'] = $ipn['txn_id'];
		$email['from'] = $ipn['business'];
		
		$total_item = $this->__calc_each_item_cost($items , $ipn['mc_currency']);
		if($state == 'invalid-price')
		{
			// ---------------------------- Send Email to the buyer for invalid payment price ------------------------------------- //
			//$email['to'] = $ipn['payer_email'];
			$email['to'] = $this->testEmail;
			$email['subject'] = "&lt; Invalid PayPal payment transaction &gt;";
			$email['message'] = '
Hello '.$ipn['first_name'].' '.$ipn['last_name'].' !<br/>
We are from '.$this->mySetting['title'].' team would like to confirm your payment:<br/>
';
			$email['message'] .= $total_item['message'].'
Item Total: $'.$total_item['itemtotal'].' '.$ipn['mc_currency'].'<br/>
Shipping & Handling: '.($ipn['mc_handling'] > 0?'$'.$ipn['mc_handling'].' '.$ipn['mc_currency']:'(Please contact us for more detail about shipping payment)').'<br/>
<br/>
Grand Total Payment: $'.$ipn['mc_gross'].' '.$ipn['mc_currency'].'<br/>
Status: Failed.<br/>
<br/> 
According to our data, this transaction is failed because of mismatch total price of our products.<br/>
Please contact us for more detail about this problem.<br/>
We are sorry for any inconvenience.<br/>
';
			$this->InstantPaymentNotification->email($email);
		}
		else if($state == 'invalid-quantity')
		{
			// --------------------- Send Email to the buyer for invalid quantity products ---------------------------------------- //
			//$email['to'] = $ipn['payer_email'];
			$email['to'] = $this->testEmail;
			$email['subject'] = "&lt; Invalid PayPal payment transaction &gt;";
			$email['message'] = '
Hello '.$ipn['first_name'].' '.$ipn['last_name'].' !<br/>
We are from '.$this->mySetting['title'].' team would like to confirm your payment:<br/>
';
			$email['message'] .= $total_item['message'].'
Item Total: $'.$total_item['itemtotal'].' '.$ipn['mc_currency'].'<br/>
Shipping & Handling: '.($ipn['mc_handling'] > 0?'$'.$ipn['mc_handling'].' '.$ipn['mc_currency']:'(Please contact us for more detail about shipping payment)').'<br/>
<br/>
Grand Total Payment: $'.$ipn['mc_gross'].' '.$ipn['mc_currency'].'<br/>
Status: Failed.<br/>
<br/> 
According to our stock quantity products, one or more of your request quantity is exceeding our available stock products.<br/>
Please contact us for more detail about this problem.<br/>
We are sorry for any inconvenience.<br/>
';
			$this->InstantPaymentNotification->email($email);
		}
		else if($state == 'invalid-city')
		{
			// --------------------- Send Email to the buyer for invalid city address ---------------------------------------- //
			//$email['to'] = $ipn['payer_email'];
			$email['to'] = $this->testEmail;
			$email['subject'] = "&lt; Address confirmation for PayPal payment transaction &gt;";
			$email['message'] = '
Hello '.$ipn['first_name'].' '.$ipn['last_name'].' !<br/>
We are from '.$this->mySetting['title'].' team would like to confirm your payment:<br/>
';
			$email['message'] .= $total_item['message'].'
Item Total: $'.$total_item['itemtotal'].' '.$ipn['mc_currency'].'<br/>
Shipping & Handling: '.'$'.$ipn['mc_handling'].' '.$ipn['mc_currency'].'<br/>
<br/>
Grand Total Payment: $'.$ipn['mc_gross'].' '.$ipn['mc_currency'].'<br/>
Status: Address Confirmation.<br/>
<br/> 
Your transaction payment is <b>Completed</b>, but please make confirmation first of your address shipping place to us before we shipping your products.<br/>
We are sorry for any inconvenience.<br/>
';
			$this->InstantPaymentNotification->email($email);
		}
		else // success ...
		{
			// ----------------------------------- Send Email to the buyer for confirmation ---------------------------------------- //
			//$email['to'] = $ipn['payer_email'];
			$email['to'] = $this->testEmail;
			$email['message'] = '
Hello '.$ipn['first_name'].' '.$ipn['last_name'].' !<br/>
We are from '.$this->mySetting['title'].' team would like to confirm your payment:<br/>
';		
			$email['message'] .= $total_item['message'].'
Item Total: $'.$total_item['itemtotal'].' '.$ipn['mc_currency'].'<br/>
Shipping & Handling: '.($ipn['mc_handling'] > 0?'$'.$ipn['mc_handling'].' '.$ipn['mc_currency']:'(Please contact us for more detail about shipping payment)').'<br/>
<br/>
Grand Total Payment: $'.$ipn['mc_gross'].' '.$ipn['mc_currency'].'<br/>
Status: '.$ipn['payment_status'].'<br/>
<br/> 
We are going to ship our products to you as soon as possible.<br/>
Please contact us for more detail of your purchase.<br/>
Thank you for shopping with us.<br/>
';
			$this->InstantPaymentNotification->email($email);
			
			// ---------------------------------- Send Email to the seller for payment confirmation --------------------------------- //
			//$email['to'] = $ipn['business'];
			$email['to'] = $this->testEmail;
			$email['subject'] = "&lt; PayPal payment notification &gt;";
			$email['message'] = '
We are from '.$this->mySetting['title'].' team would like to confirm that just now, customer:<br/>
Name: '.$ipn['first_name'].' '.$ipn['last_name'].'<br/>
Address: '.$ipn['address_street'].'<br/>
City: '.$ipn['address_city'].'<br/>
Country: '.$ipn['address_country'].' ('.$ipn['address_country_code'].')<br/>
ZIP code: '.$ipn['address_zip'].'<br/>
<br/>
had successfully made a payment of purchasing your products as follow:<br/>
';
			$email['message'] .= $total_item['message'].'
Item Total: $'.$total_item['itemtotal'].' '.$ipn['mc_currency'].'<br/>
Shipping & Handling: '.($ipn['mc_handling'] > 0?'$'.$ipn['mc_handling'].' '.$ipn['mc_currency']:'(Please contact us for more detail about shipping payment)').'<br/>
<br/>
Total Amount: $'.$ipn['mc_gross'].' '.$ipn['mc_currency'].'<br/>
<b><i>PayPal</i></b> Transaction Fee: -$'.$ipn['mc_fee'].' '.$ipn['mc_currency'].'<br/>
Net Amount: $'.($ipn['mc_gross'] - $ipn['mc_fee']).' '.$ipn['mc_currency'].'<br/>
<br/>
Payment Type: '.$ipn['payment_type'].'<br/>
Status: '.$ipn['payment_status'].'<br/>
<br/> 
on '.$ipn['payment_date'].'.<br/>			
For more detail, please see this transaction record in your <a href="//www.paypal.com"><b><i>PayPal</i></b> Account</a>.<br/>
';
			if(!empty($ipn['memo']))
			{
				$email['message'] .= 'Ohh and one more, your customer leave a memo for your customer support:<br/>'.$ipn['memo'].'<br/>';
			}

			$this->InstantPaymentNotification->email($email);
		}
		
	}
}
?>