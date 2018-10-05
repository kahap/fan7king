<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

// print_r($_POST);
session_start();

$or = new API("real_cases");

foreach($_POST as $key=>$value){
	$$key = $value;
}
//退回徵信內容且要將狀態改掉的
$status = array('4','7','701');

if(!isset($errMsg)){
	if(in_array($rcStatus,$status)){
		$data = array("aauNoAuthen"=>"","rcIfAuthen"=>"0","aauNoCredit"=>"","rcIfCredit"=>"0","rcStatus"=>"2");
	}else{
		$data = array("aauNoAuthen"=>"","rcIfAuthen"=>"0","aauNoCredit"=>"","rcIfCredit"=>"0","rcStatus"=>"2");
	}
	$or->update($data, $rcNo);
	//人員操作紀錄
	$apiSd = new API("service_record");
	$SdDataInput = array(
						"rcNo"=>$rcNo,
						"aauNoService"=>$_SESSION['adminUserData']['aauNo'],
						"content"=>"從授信退回徵信", 
						"time"=>date('Y-m-d H:i:s',time())); 
	$apiSd->insert($SdDataInput);
	echo "OK";
}else{
	echo $errMsg;
}

?>