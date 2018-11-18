<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

session_start();

date_default_timezone_set('Asia/Taipei');

$or = new API("real_cases");
$tb = new API("transfer_bank");
$mem = new API("member");
$bar = new API("barcode");
$tpi = new API("tpi");
$sup = new API("supplier");

$orColumns = array("tbNo","rcApproDate","rcPredictGetDate","rcFirstPayDate","rcBankTransferAmount");
$rcDataInput = array();

foreach($_POST as $key=>$value){
	$$key = $value;
	if(in_array($key,$orColumns)){
		$rcDataInput[$key] = $value;
	}
}

$approDate = strtotime($rcApproDate);
$getDate = strtotime($rcPredictGetDate);
$firstPay = strtotime($rcFirstPayDate);

if($getDate == ""){
	$errMsg[] = "預計撥款日期不可為空白。";
}else{
	if($getDate < $approDate){
		$errMsg[] = "預計撥款日期不可小於買帳日期。";
	}
}
if($firstPay == ""){
	$errMsg[] = "第一次繳款日不可為空白。";
}else{
	if($firstPay < $approDate){
		$errMsg[] = "第一次繳款日不可小於買帳日期。";
	}
}

if(!isset($errMsg)){
	$or->update($rcDataInput,$rcNo);

	if($process == "true"){
		if($tbNo != "" && $tbNo != "請選擇"){
			$rcData = $or->getOne($rcNo);
			
			//更改撥款狀態
			$or->update(array("rcApproStatus"=>2,"aauNoAppro"=>$_SESSION["adminUserData"]["aauNo"]), $rcNo);
			
			//人員操作紀錄
			$apiSd = new API("service_record");
			$SdDataInput = array(
								"rcNo"=>$rcNo,
								"aauNoService"=>$_SESSION['adminUserData']['aauNo'],
								"content"=>"預計撥款日儲存", 
								"time"=>date('Y-m-d H:i:s',time())); 
			$apiSd->insert($SdDataInput);
			echo "OK";
		}else{
			$errMsg[] = "撥款前請先選擇撥款銀行。";
			echo json_encode($errMsg,JSON_UNESCAPED_UNICODE);
		}
	}else{
		echo "OK";
	}
}else{
	echo json_encode($errMsg,JSON_UNESCAPED_UNICODE);
}



?>