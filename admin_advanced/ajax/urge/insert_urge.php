<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

session_start();
date_default_timezone_set('Asia/Taipei');

$ur = new API("urge_records");

foreach($_POST as $key=>$value){
	$$key = $value;
}

if(!isset($errMsg)){
	$ur->insert($_POST);
	echo "OK";
}else{
	echo $errMsg;
}

?>