<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

// print_r($_POST);
session_start();

$or = new API("real_cases");

foreach($_POST as $key=>$value){
	$$key = $value;
}

if($_SESSION["adminUserData"]["aauNo"] == ""){
	$errMsg[] = "請重新登入，才能處理案件";
}

if(!isset($errMsg)){
	$or->update(array("aauNoCredit"=>"","rcIfCredit"=>0), $rcNo);
	echo "OK";
	//人員操作紀錄
	$apiSd = new API("service_record");
	$SdDataInput = array(
						"rcNo"=>$rcNo,
						"aauNoService"=>$_SESSION['adminUserData']['aauNo'],
						"content"=>"退回徵信派件", 
						"time"=>date('Y-m-d H:i:s',time()));
}else{
	echo $errMsg;
}

?>