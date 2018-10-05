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
	$fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
	$fileContents = trim(str_replace('"', "'", $fileContents));
	$simpleXml = simplexml_load_string($fileContents);
	$jsonStr = json_encode($simpleXml,JSON_UNESCAPED_UNICODE);
	$json = json_decode($jsonStr,true);
	
	$outputData = array();
	if(isset($json["depositRec"])){
		
		foreach($json["depositRec"] as $keyPerson=>$valPerson){
			$rcRealCaseNo = "20".$valPerson["進件號碼"];
			$rc->setWhereArray(array("rcCaseNo"=>$rcRealCaseNo));
			$rcData = $rc->getWithConditions();
			$rcNo = $rcData[0]["rcNo"];
			
			$actualPayTotal = 0;
			$payDate = "";
			
			//客戶當天多次還款
			$arrKeys = array_keys($valPerson["multiPays"]["eachPay"]);
			if(is_numeric($arrKeys[0])){
				foreach($valPerson["multiPays"]["eachPay"] as $keyPayEachDay=>$valPayEachDay){
					//新增到繳款紀錄
					$prDataInput = array(
						"rcNo"=>$rcNo,
						"prActualPay"=>-$valPayEachDay["實還金額"],
						"prSource"=>"刪除還款檔",
						"prFee"=>0,
						"prExtra"=>0,
						"prDate"=>date("Y-m-d",time())
					);
					$prNo = $pr->insert($prDataInput);
					
					//對本息表做加總
					$actualPayTotal += $valPayEachDay["實還金額"];
					$payDate = date("Y-m-d",strtotime($valPayEachDay["實還日期"]));
				}
			}else{
				//新增到繳款紀錄
				$prDataInput = array(
					"rcNo"=>$rcNo,
					"prActualPay"=>-$valPerson["multiPays"]["eachPay"]["實還金額"],
					"prSource"=>"刪除還款檔",
					"prFee"=>0,
					"prExtra"=>0,
					"prDate"=>date("Y-m-d",time())
				);
				$prNo = $pr->insert($prDataInput);
				
				//對本息表做加總
				$actualPayTotal += $valPerson["multiPays"]["eachPay"]["實還金額"];
				$payDate = date("Y-m-d",strtotime($valPerson["multiPays"]["eachPay"]["實還日期"]));
			}
			
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
		echo "此檔案非CMC還款檔。";
	}
}else{
	echo $errMsg;
}