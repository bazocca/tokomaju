<?php
	$this->Get->create($data);
	extract($data , EXTR_SKIP);
?>
<div>
	<?php
		$pass['open_tag'] = '<li>';
		$pass['close_tag'] = '</li>';
		$pass['language'] = 'en';
		dpr($this->Get->navigation($pass));
		
		/*
		?>
		<form target="_blank" action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="HXQ7EAJX9Y9RS">
			<table>
			<tr><td><input type="hidden" name="on0" value="Sizes">Sizes</td></tr><tr><td><select name="os0">
				<option value="30-34">30-34 $18.00 USD</option>
				<option value="35-39">35-39 $20.00 USD</option>
				<option value="40-44">40-44 $22.00 USD</option>
				<option value="47-50">47-50 $26.00 USD</option>
			</select> </td></tr>
			<tr><td><input type="hidden" name="on1" value="Colors">Colors</td></tr><tr><td><select name="os1">
				<option value="Red">Red </option>
				<option value="Black">Black </option>
				<option value="Blue">Blue </option>
			</select> </td></tr>
			<tr><td><input type="hidden" name="on2" value="Special Request">Special Request</td></tr><tr><td><input type="text" name="os2" maxlength="200"></td></tr>
			</table>
			<input type="hidden" name="currency_code" value="USD">
			<input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>
		<?php
		*/
		
		// testing for using Pay Now button Helper !!
		$paypal_param = array();
		$paypal_param['item_name'] = 'ELLE shoes';
		$paypal_param['amount'] = '12.25';
		$paypal_param['test'] = true;
		$paypal_param['type'] = 'paynow';
		$paypal_param['quantity'] = '3';
		//$paypal_param[''] = '';
		
		echo $this->Paypal->button($paypal_param);			
	?>

	<a class="btn btn-primary" href="<?php echo $imagePath; ?>recaptcha">Go to reCAPTCHA</a>
</div>