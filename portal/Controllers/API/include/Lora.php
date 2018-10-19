<?php
	include('sanlien/cls/cfg.inc.php');
	include('sanlien/cls/Device.cls.php');	
	include('sanlien/cls/WADB.cls.php');


	foreach($_POST as $key => $value){
		$name[]= $key;
		$val[] = $value;
	}
	$name = implode(',',$name);
	$val = implode(',',$val);
	
	
?>