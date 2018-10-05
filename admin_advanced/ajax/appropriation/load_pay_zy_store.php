<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

session_start();

date_default_timezone_set('Asia/Taipei');

$rc = new API("real_cases");
$tpi = new API("tpi");
$pr = new API("pay_records");
$pur = new API("pay_upload_records");

if($_FILES["cmcSuccessGet"]['error'] == 4) {
	$errMsg = "請選擇檔案";
}else{
	$tmp = explode(".", $_FILES["cmcSuccessGet"]["name"]);
	$extension = end($tmp);
	if($_FILES["cmcSuccessGet"]['type'] != "text/plain" || $extension != "txt"){
		$errMsg = "必須上傳txt檔";
	}
}

if(!isset($errMsg)){
	$fileContents = file_get_contents($_FILES["cmcSuccessGet"]["tmp_name"]);
	$contentLines = explode(PHP_EOL,$fileContents);
	
	$outputData = array();
	if(DateTime::createFromFormat('Ymd', substr($contentLines[0],0,8)) !== FALSE){
		$prArr = array();
		foreach($contentLines as $keyPerson=>$valPerson){
			$rcRealCaseNo = "20".substr($valPerson,41,10);
			$rc->setWhereArray(array("rcCaseNo"=>$rcRealCaseNo));
			$rcData = $rc->getWithConditions();
			if($rcData[0]["rcApproStatus"] == "4"){
				$rcNo = $rcData[0]["rcNo"];
				
				$actualPayTotal = ltrim(substr($valPerson,16,9),'0');
				$payDate = date("Y-m-d",strtotime(substr($valPerson,8,8)));
				
				//新增到繳款紀錄
				$prDataInput = array(
					"rcNo"=>$rcNo,
					"prActualPay"=>$actualPayTotal,
					"prSource"=>"超商",
					"prFee"=>"0",
					"prExtra"=>"0",
					"prDate"=>$payDate
				);
				$prNo = $pr->insert($prDataInput);
				$prArr[] = $prNo;
				
				
				$tpi->setWhereArray(array("rcNo"=>$rcNo));
				$tpiData = $tpi->getWithConditions();
				
				//大於300塊才進本息表
				if($actualPayTotal > 300){
					//本息表專用CLASS智能計算
					$tpiCalc = new TPI($tpiData);
					$tpiCalc->setFinalTotal($actualPayTotal);
					$tpiCalc->setPayTotal($actualPayTotal);
					$tpiCalc->recalculate($tpi,$payDate);
					$tpiCalc->checkIfFinishPaying($rcNo);
				}
				
				$rcNewData = $rc->getOne($rcNo);
				if($rcNewData[0]["rcType"] > 0){
						$moto = new API("motorbike_cellphone_orders");
						$moto->setWhereArray(array("mcoNo"=>$rcData[0]['rcRelateDataNo']));
						$motoData = $moto->getWithConditions();
				}  
				$tpiPeriodTotal = ($rcNewData['0']['rcType'] == 0) ? $rcNewData[0]["rcPeriodTotal"]/$rcNewData[0]["rcPeriodAmount"]:$motoData['0']['mcoMinMonthlyTotal'];
				$outputData[$keyPerson] = array(
					"rcCaseNo"=>$rcNewData[0]["rcCaseNo"],
					"paid"=>$actualPayTotal,
					"rcPeriodAmount"=>$rcNewData[0]["rcPeriodAmount"],
					"rcPeriodTotalEach"=>$tpiPeriodTotal
				);
			}
		}
		
		if(empty(array_filter($outputData))){
			echo "並無更改任何案件的撥款狀態。";
		}else{

			//檔案上傳至資料夾
			$newFilePath = time().".".$extension;
			if(move_uploaded_file($_FILES["cmcSuccessGet"]["tmp_name"], "../../file/payment/zy_store/".$newFilePath)) {
				$fileName = "file/payment/zy_store/".$newFilePath;
			}else{
				$fileName = "上傳失敗";
			}
			$purDataInput = array(
					"aauNo"=>$_SESSION["adminUserData"]["aauNo"],
					"purType"=>1,
					"purPath"=>$fileName,
					"purOrigName"=>$_FILES["cmcSuccessGet"]["name"],
					"prNo"=>json_encode($prArr,true)
			);
			$pur->insert($purDataInput);
			
			echo json_encode($outputData,JSON_UNESCAPED_UNICODE);
		}
	}else{
		echo "此檔案非彰銀還款檔。";
	}
}else{
	echo $errMsg;
}