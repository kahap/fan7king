<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

date_default_timezone_set('Asia/Taipei');

$sup = new API("supplier");
// print_r($_POST);

foreach($_POST as $key=>$value){
	$$key = $value;
}

//可修改欄位
$columns = array(
		"supName","supContactName","supPhone","supCell","supLogId","supPwd","supKey" ,"supDisplayName","supPeriod",
		"supFax","supAddr","supBillAddr","supEmail","supSerialNo","supContactIdNum","supPostCode",
		"supSignDate","supTransferAccName","supTransferAccIdNum","supTransferBank","supTransferBankCode",
		"supTransferSubBank","supTransferSubBankCode","supTransferAcc","supStampImg","aauNo","editpeople"
);
$dataInput = array();
//圖片上傳
if(isset($supNo)){
	$supData = $sup->getOne($supNo);
	$err = uploadImg("edit", $supData[0]["supStampImg"], true, "supplier-stamp", 'supStampImg');
	if($err != ""){
		$errMsg[] = $err;
	}
	if(trim($_POST["supStampImg"]) == ""){
		$_POST["supStampImg"] = $supData[0]["supStampImg"];
	}
}else{
	$err = uploadImg("insert", "", true, "supplier-stamp", 'supStampImg');
	if($err != ""){
		$errMsg[] = $err;
	}
}

//塞進暫列
foreach($_POST as $key=>$value){
	$$key = $value;
	if(in_array($key,$columns)){
		$dataInput[$key] = $value;
	}
}


if(!isset($errMsg)){
	if(isset($supNo)){
		$sup->update($dataInput,$supNo);
	}else{
		$dataInput['editTime'] = date("Y-m-d H:i:s",time());
		$sup->insert($dataInput);
	}
	echo "OK";
}else{
	echo json_encode($errMsg,JSON_UNESCAPED_UNICODE);
}



?>