<?php

	header (" content-type: text/HTML; charset=utf-8 ");
	require_once('../../model/require_login.php');
	
	$ad = new Advertise();
	
	$result = "";
	
	foreach($_POST as $key=>$value){
		$$key = $value;
	}
	
	$ad->updateIfShow($adIfShow, $adNo);
	
	$result="更新成功！";
	
	echo $result;

?>