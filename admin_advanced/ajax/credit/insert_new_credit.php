<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');
session_start();

$apiOr = new API("real_cases");

$inputData = array();
$apiSd = new API("service_record");

if(isset($_POST["rcNo"])){
	foreach($_POST["rcNo"] as $noEach){
		$apiOr->update(array("aauNoCredit"=>$_POST["aauNo"]), $noEach);
		$SdDataInput = array(
					"rcNo"=>$noEach,
					"aauNoService"=>$_SESSION['adminUserData']['aauNo'],
					"content"=>"選擇派件", 
					"time"=>date('Y-m-d H:i:s',time())); 
		$apiSd->insert($SdDataInput); 
	}
}else{
	$errMsg = "請選擇欲派件之案件。";
}

	
	


if(!isset($errMsg)){
	echo "OK";
}else{
	echo $errMsg;
}

?>