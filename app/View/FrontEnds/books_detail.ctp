<?php 
	$this->Get->create($data);
	echo "Exchange Rate examples: ";
	echo $this->Paypal->exchange_rate('USD');
	echo "<br/>";
	echo "<br/>";
	echo $this->Paypal->initialize('paynow' , $data);
	echo "<br/>";
	echo "<br/>";
	echo $this->Paypal->initialize('addtocart' , $data);
	echo "<br/>";
	echo "<br/>";
	echo $this->Get->staggingEdit(); 
	dpr($data);
?>