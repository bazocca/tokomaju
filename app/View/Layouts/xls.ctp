<?php
    header ("Expires: 0");
	header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
	header ("Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0");
	header ("Pragma: no-cache");
	header ("Content-Type: application/vnd.ms-excel; charset=UTF-8");
	header ("Content-Disposition: attachment; filename=\"".$filename.".xls" );
	header ("Content-Description: Generated Report" );
	echo $content_for_layout;
?>