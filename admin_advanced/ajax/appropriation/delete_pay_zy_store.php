<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

session_start();

date_default_timezone_set('Asia/Taipei');

$rc = new API("real_cases");
$tpi = new API("tpi");
$pr = new API("pay_records");
$pur = new API("pay_upload_records");

$purData = $pur->getOne($_POST["no"]);
$prNoArr = json_decode($purData[0]["prNo"]);

//刪除繳款紀錄
// foreach($prNoArr as $value){
// 	$pr->delete(array("prNo"=>$value));
// }
//刪除上傳紀錄
$pur->delete(array("purNo"=>$_POST["no"]));

if(!isset($errMsg)){
	$fileContents = file_get_contents("../../".$purData[0]["purPath"]);
	$contentLines = explode(PHP_EOL,$fileContents);
	
	$outputData = array();
	if(DateTime::createFromFormat('Ymd', substr($contentLines[0],0,8)) !== FALSE){
		foreach($contentLines as $keyPerson=>$valPerson){
			$rcCaseNo = "20".substr($valPerson,41,10);
			$rc->setWhereArray(array("rcCaseNo"=>$rcCaseNo));
			$rcData = $rc->getWithConditions();
			$rcNo = $rcData[0]["rcNo"];
			
			$actualPayTotal = ltrim(substr($valPerson,16,9),'0');
			$payDate = date("Y-m-d",strtotime(substr($valPerson,8,8)));
			
			//新增到繳款紀錄
			$prDataInput = array(
					"rcNo"=>$rcNo,
					"prActualPay"=>-$actualPayTotal,
					"prSource"=>"刪除還款檔",
					"prFee"=>0,
					"prExtra"=>0,
					"prDate"=>date("Y-m-d",time())
			);
			$prNo = $pr->insert($prDataInput);
			
			$tpi->setWhereArray(array("rcNo"=>$rcNo));
			$tpiData = $tpi->getWithConditions();
			
			//本息表專用CLASS智能計算
			$tpiCalc = new TPI($tpiData);
			$tpiCalc->setDeleteFinalTotal($actualPayTotal);
			$tpiCalc->recalculate($tpi,$payDate);
			
			$rcNewData = $rc->getOne($rcNo);
			$outputData[$keyPerson] = array(
				"rcCaseNo"=>$rcNewData[0]["rcCaseNo"],
				"rcApproStatus"=>$_SESSION["adminUserData"]["aauName"],
				"time"=>date("Y-m-d H:i:s",time())
			);
		}
		if(empty(array_filter($outputData))){
			echo "並無更改任何案件的撥款狀態。";
		}else{
			echo json_encode($outputData,JSON_UNESCAPED_UNICODE);
		}
	}else{
		echo "此檔案非彰銀(超商)還款檔。";
	}
}else{
	echo $errMsg;
}