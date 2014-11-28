<?php
class InstantPaymentNotification extends AppModel {
	var $useTable = false;
	
    /**
     * name is the name of the model
     * 
     * @var $name string
     * @access public
     */
    var $name = 'InstantPaymentNotification';
	
    /**
      * the PaypalSource
      */
    var $paypal = null;
    
    /**
      * verifies POST data given by the paypal instant payment notification
      * @param array $data Most likely directly $_POST given by the controller.
      * @return boolean true | false depending on if data received is actually valid from paypal and not from some script monkey
      */
    function isValid($data){
      if(!empty($data)){
        App::uses('PaypalIpnSource' , 'Model/Datasource');
        $this->paypal = new PaypalIpnSource();
        return $this->paypal->isValid($data);
      }
      return false;
    }
    
    /**
      * Utility method to send basic emails based on a paypal IPN transaction.
      * This method is very basic, if you need something more complicated I suggest
      * creating your own method in the afterPaypalNotification function you build
      * in the app_controller.php
      *
      * Example Usage: (InstantPaymentNotification = IPN)
      *   IPN->id = '4aeca923-4f4c-49ec-a3af-73d3405bef47';
      *   IPN->email('Thank you for your transaction!');
      *
      *   IPN->email(array(
      *     'id' => '4aeca923-4f4c-49ec-a3af-73d3405bef47',
      *     'subject' => 'Donation Complete!',
      *     'message' => 'Thank you for your donation!',
      *     'sendAs' => 'text'
      *   ));
      *
      *  Hint: use this in your afterPaypalNotification callback in your app_controller.php
      *   function afterPaypalNotification($txnId){
      *     ClassRegistry::init('PaypalIpn.InstantPaymentNotification')->email(array(
      *       'id' => $txnId,
      *       'subject' => 'Thanks!',
      *       'message' => 'Thank you for the transaction!'
      *     ));
      *   }
      *
      * Options:
      *   id: id of instant payment notification to base email off of
      *   subject: subject of email (default: Thank you for your paypal transaction)
      *   sendAs: html | text (default: html)
      *   to: email address to send email to (default: ipn payer_email)
      *   from: from email address (default: ipn business)
      *   cc: array of email addresses to carbon copy to (default: array())
      *   bcc: array of email addresses to blind carbon copy to (default: array())
      *   layout: layout of email to send (default: default)
      *   template: template of email to send (default: default)
      *   log: boolean true | false if you'd like to log the email being sent. (default: true)
      *   message: actual body of message to be sent (default: null)
      *
      * @param array $options of the ipn to send
      *   
      */
    function email($options = array()){
      if(!is_array($options)){
        $message = $options;
        $options = array();
        $options['message'] = $message;
      }
      
      $defaults = array(
        'subject' => '&lt; Thank you for your PayPal transaction &gt;',
        'sendAs' => 'html',
//        'to' => $this->data['InstantPaymentNotification']['payer_email'],
  //      'from' => $this->data['InstantPaymentNotification']['business'],
        'cc' => array(),
        'bcc' => array(),
        'layout' => 'default',
        'template' => 'default',
        'log' => true,
        'message' => null 
      );
      $options = array_merge($defaults, $options);
      
      //debug($options);
      if($options['log']){
        $this->log("Emailing: {$options['to']} through the PayPal IPN Plugin. ",'email');
      }

      App::uses('Controller','Controller');
      $Controller = new Controller();
      $Controller->set('ipnSetting', $options['data']['mySetting']);

      App::uses('CakeEmail', 'Network/Email');
      $Email = new CakeEmail();
      try{
        $Email->from($options['from'])
            ->to($options['to'])
            ->bcc($options['bcc'])
            ->cc($options['cc'])
            ->subject($options['subject'])
            ->emailFormat($options['sendAs'])
            ->template($options['template'],$options['layout'])
            ->send($options['message']);
      } catch(Exception $e){
        // Failure, with exception
      }
    }
    
    /**
      * builds the associative array for paypalitems only if it was a cart upload
      *
      * @param raw post data sent back from paypal
      * @return array of cakePHP friendly association array.
      */
    function buildAssociationsFromIPN($post){
      $retval = array();
      $retval['InstantPaymentNotification'] = $post;
      if(isset($post['num_cart_items']) && $post['num_cart_items'] > 0){
        $retval['PaypalItem'] = array();
        for($i=1;$i<=$post['num_cart_items'];$i++){
        	$key = $i - 1;
          $retval['PaypalItem'][$key]['item_name'] = $post["item_name$i"];
          $retval['PaypalItem'][$key]['item_number'] = $post["item_number$i"];
          $retval['PaypalItem'][$key]['item_number'] = $post["item_number$i"];
          $retval['PaypalItem'][$key]['quantity'] = $post["quantity$i"];
          $retval['PaypalItem'][$key]['mc_shipping'] = $post["mc_shipping$i"];
          $retval['PaypalItem'][$key]['mc_handling'] = $post["mc_handling$i"];
          $retval['PaypalItem'][$key]['mc_gross'] = $post["mc_gross_$i"];
          $retval['PaypalItem'][$key]['tax'] = $post["tax$i"];
        }
      }
      return $retval;
    }
    
	/**
	* cari kota berdasarkan API JNE 
	* @param string $query contains substring of searched city
	* @param string $type contains mengetahui apakah kota asal (origin) atau kota tujuan (destination)
	* @param string $courier[optional] contains which courier being used to send the package
	* @return string $result the founded city
	* @public
	**/
	function get_city($query,$type,$courier = NULL)
	{
		 App::import('Vendor', 'restongkir');	
		 $rest = new REST_Ongkir(array(
		 'server' => 'http://api.ongkir.info/'
		 ));
		
		if(empty($courier))
		{
			$courier = 'jne';
		}
		 //ganti API-Key dibawah ini sesuai dengan API Key yang anda peroleh setalah mendaftar di ongkir.info
		 $result = $rest->post('city/list', array(
		 'query' => $query,
		 'type' => $type,
		 'courier' => $courier,
		 'API-Key' => '6c941b6d5e5dbac62e5c0e7541f82782' ), 'JSON');
		 
		 $return_arr = array();
		 $return_arr['error'] = 0;
		 try
		 {
			 $status = $result['status'];
			
			 // Handling the data
			 if ($status->code == 0)
			 {
			 	$return_arr['value'] = $result['cities'];
			 }
			 else
			 {
			 	$return_arr['error'] = 1;
				$return_arr['value'] = 'Tidak ditemukan kota yang diawali "'.$query.'"';
			 }
		
		 }
		 catch (Exception $e)
		 {
		 	 $return_arr['error'] = 1;
			 $return_arr['value'] = 'Processing error.';
		 }
		 return $return_arr;
	}
	
	/**
	* cari harga pengiriman berdasarkan API JNE 
	* @param string $from contains source city
	* @param string $to contains destination city
	* @param integer $weight contains weight of the product that will be sent
	* @param string $courier[optional] contains which courier being used to send the package 
	* @return void
	* @public
	**/
	function get_cost($from, $to , $courier = NULL)
	{
	 	 App::import('Vendor', 'restongkir');	
		 $rest = new REST_Ongkir(array(
		 'server' => 'http://api.ongkir.info/'
		 ));
		 
		 if(empty($courier))
		 {
			$courier = 'jne';
		 }
		//ganti API-Key dibawah ini sesuai dengan API Key yang anda peroleh setalah mendaftar di ongkir.info
		 $result = $rest->post('cost/find', array(
		 'from' => $from,
		 'to' => $to,
		 'weight' => '1000', // cek harga per kg nya aja !!!
		 'courier' => $courier,
		'API-Key' =>'6c941b6d5e5dbac62e5c0e7541f82782'
		 ));
		 
		 $return_arr = array();
		 $return_arr['error'] = 0;
		 $return_arr['value'] = 0;
		 try
		 {
			 $status = $result['status'];
			 $prices = $result['price'];
			 
			 // Handling the data
			 if ($status->code == 0)
			 { 
				 foreach ($prices->item as $item)
				 {
				 	 if(strpos($item->service, "REG") !== FALSE)
					 {
					 	 $return_arr['value'] = $item->value;
						 break;
					 } 
				 }
			 }
			 
			 if($return_arr['value'] == 0)
			 {
			 	 $return_arr['error'] = 1;
				 $return_arr['value'] = 'Tidak ditemukan jalur pengiriman dari "'.$from.'" ke "'.$to.'"';
			 }
			 else
			 {
			 	 $return_arr['from'] = strtoupper($from);
				 $return_arr['to'] = strtoupper($to);
			 }	
		 }
		 catch (Exception $e)
		 {
		 	$return_arr['error'] = 1;
			$return_arr['value'] = 'Processing error.';
		 }
		 
		 return $return_arr;
	}
}
?>