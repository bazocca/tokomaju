<?php

/*
##############################
Cron Job command : 
##############################

--> with cake shell
/home/creaidco/public_html/app/vendors/shells/cakeshell order -cli /usr/bin -console /home/creaidco/public_html/cake/console -app /home/creaidco/public_html/app >> /home/creaidco/public_html/app/tmp/logs/cron.log

--> direct URL
curl http://www.yourdomain.com/controller/action > /dev/null 2>&1
*/

class OrderShell extends Shell {
	var $uses = array('Setting');
    function main() // main needs to define
    {
    	$sql = $this->Setting->findByKey('custom-pagination');

    	$this->Setting->id = $sql['Setting']['id'];

    	$this->Setting->saveField('value' , $sql['Setting']['value'] + 1);
    }
}
?>