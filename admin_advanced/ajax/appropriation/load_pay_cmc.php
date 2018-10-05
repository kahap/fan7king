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
	if($_FILES["cmcSuccessGet"]['type'] != "text/xml" || $extension != "xml"){
		$errMsg = "必須上傳xml檔";
	}
}

if(!isset($errMsg)){
	$fileContents = file_get_contents($_FILES["cmcSuccessGet"]["tmp_name"]);
	$fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
	$fileContents = trim(str_replace('"', "'", $fileContents));
	$simpleXml = simplexml_load_string($fileContents);
	$jsonStr = json_encode($simpleXml,JSON_UNESCAPED_UNICODE);
	$json = json_decode($jsonStr,true);
	$outputData = array();
	if(isset($json["depositRec"])){
		$prArr = array();
		
		foreach($json["depositRec"] as $keyPerson=>$valPerson){
			if(substr($valPerson["進件號碼"],-4,1) == "0"){
				continue;
			}
			$rcCaseNo = "20".$valPerson["進件號碼"];
			$rc->setWhereArray(array("rcCaseNo"=>$rcCaseNo));
			$rcData = $rc->getWithConditions();
			if($rcData != null){
				if($rcData[0]["rcApproStatus"] == "4"){
					$rcNo = $rcData[0]["rcNo"];
					
					$actualPayTotal = 0;
					$payDate = "";
					
					//客戶當天多次還款  
					$arrKeys = array_keys($valPerson["multiPays"]["eachPay"]);
					if(is_numeric($arrKeys[0])){
						foreach($valPerson["multiPays"]["eachPay"] as $keyPayEachDay=>$valPayEachDay){
							// 新增到繳款紀錄
							$prDataInput = array(
								"rcNo"=>$rcNo,
								"prActualPay"=>$valPayEachDay["實還金額"],
								"prSource"=>$valPayEachDay["款項來源"],
								"prFee"=>$valPayEachDay["郵局手續費"],
								"prExtra"=>$valPayEachDay["overChargeInfo"]["overChargeDetail"]["本次溢收"],
								"prDate"=>$valPayEachDay["實還日期"]
							);
							$prNo = $pr->insert($prDataInput);
							$prArr[] = $prNo;
							
							// 對本息表做加總
							$actualPayTotal += $valPayEachDay["實還金額"];
							$payDate = date("Y-m-d",strtotime($valPayEachDay["實還日期"]));
						}
					}else{
						$multikey = array_keys($valPerson["multiPays"]["eachPay"]["multiWriteOffs"]["writeOffDetail"]);
						
						if(is_numeric($multikey[0])){
							//新增到繳款紀錄
								$prDataInput = array(
									"rcNo"=>$rcNo,
									"prActualPay"=>$valPerson["multiPays"]["eachPay"]["實還金額"],
									"prSource"=>$valPerson["multiPays"]["eachPay"]["款項來源"],
									"prFee"=>$valPerson["multiPays"]["eachPay"]["郵局手續費"],
									"prExtra"=>$valPerson["multiPays"]["eachPay"]["overChargeInfo"]["overChargeDetail"]["本次溢收"],
									"prDate"=>$valPerson["multiPays"]["eachPay"]["實還日期"]
								);
								$prNo = $pr->insert($prDataInput);
								$prArr[] = $prNo;
							foreach($valPerson["multiPays"]["eachPay"]["multiWriteOffs"]["writeOffDetail"] as $k => $v){
								//對本息表做加總
								$actualPayTotal += $v["銷帳金額"];
								$payDate = date("Y-m-d",strtotime($valPerson["multiPays"]["eachPay"]["實還日期"]));
							}
						}else{
							//新增到繳款紀錄
							$prDataInput = array(
								"rcNo"=>$rcNo,
								"prActualPay"=>$valPerson["multiPays"]["eachPay"]["實還金額"],
								"prSource"=>$valPerson["multiPays"]["eachPay"]["款項來源"],
								"prFee"=>$valPerson["multiPays"]["eachPay"]["郵局手續費"],
								"prExtra"=>$valPerson["multiPays"]["eachPay"]["overChargeInfo"]["overChargeDetail"]["本次溢收"],
								"prDate"=>$valPerson["multiPays"]["eachPay"]["實還日期"]
							);
							$prNo = $pr->insert($prDataInput);
							$prArr[] = $prNo;
							
							//對本息表做加總
							$actualPayTotal += $valPerson["multiPays"]["eachPay"]["實還金額"];
							$payDate = date("Y-m-d",strtotime($valPerson["multiPays"]["eachPay"]["實還日期"]));
						}
					}
					
					$tpi->setWhereArray(array("rcNo"=>$rcNo));
					$tpiData = $tpi->getWithConditions();
					
					//本息表專用CLASS智能計算
					$tpiCalc = new TPI($tpiData);
					$tpiCalc->setFinalTotal($actualPayTotal);
					$tpiCalc->setPayTotal($actualPayTotal);
					$tpiCalc->recalculate($tpi,$payDate);
					$tpiCalc->checkIfFinishPaying($rcNo);
					
					
					
					
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
		}
		
		if(empty(array_filter($outputData))){
			echo "並無更改任何案件的撥款狀態。";
		}else{
			//檔案上傳至資料夾
			$newFilePath = time().".".$extension;
			if(move_uploaded_file($_FILES["cmcSuccessGet"]["tmp_name"], "../../file/payment/cmc/".$newFilePath)) {
				$fileName = "file/payment/cmc/".$newFilePath;
			}else{
				$fileName = "上傳失敗";
			}
			$purDataInput = array(
					"aauNo"=>$_SESSION["adminUserData"]["aauNo"],
					"purType"=>0,
					"purPath"=>$fileName,
					"purOrigName"=>$_FILES["cmcSuccessGet"]["name"],
					"prNo"=>json_encode($prArr,true)
			);
			$pur->insert($purDataInput);
			echo json_encode($outputData,JSON_UNESCAPED_UNICODE);
		}
	}else{
		echo "此檔案非CMC還款檔。";
	}
}else{
	echo $errMsg;
}