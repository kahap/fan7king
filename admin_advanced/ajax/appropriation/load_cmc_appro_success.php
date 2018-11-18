<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

date_default_timezone_set('Asia/Taipei');

$rc = new API("real_cases");
$tpi = new API("tpi");
$bar = new API("barcode");

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
	$json = json_decode($jsonStr);
	$outputData = array();
	$outterElem = get_object_vars($json);
	if(count($outterElem["loanRec"]) == '1'){
		foreach($json as $key=>$value){
				$content = get_object_vars($value);
				$rcCaseNo = "20".$content["CaseNo"];
				$rc->setWhereArray(array("rcCaseNo"=>$rcCaseNo));
				$rcData = $rc->getWithConditions();
				//更新虛擬帳號、放款帳號
				$rc->update(array("rcReleaseAccount"=>$content["ContractNo"],"rcVirtualAccount"=>$content["銀行帳號_1"]), $rcData[0]["rcNo"]);
				
				if($rcData[0]["rcType"] > 0){
					$moto = new API("motorbike_cellphone_orders");
					$moto->setWhereArray(array("mcoNo"=>$rcData[0]['rcRelateDataNo']));
					$motoData = $moto->getWithConditions();
				}
				if($rcData[0]["rcApproStatus"] == "3"){
					$rcNo = $rcData[0]["rcNo"];
					
					//產TPI本息表
					$curDate = $rcData[0]["rcFirstPayDate"];
					$curDateArr = explode("-",$rcData[0]["rcFirstPayDate"]);
					$tpiPeriodTotal = ($rcData['0']['rcType'] == 0) ? $rcData[0]["rcPeriodTotal"]/$rcData[0]["rcPeriodAmount"]:$motoData['0']['mcoMinMonthlyTotal'];
					for($i=0; $i<$rcData[0]["rcPeriodAmount"]; $i++){
						$tpiDataInput = array(
								"rcNo"=>$rcNo,
								"tpiPeriod"=>$i+1,
								"tpiSupposeDate"=>$curDate,
								"tpiActualDate"=>"",
								"tpiPeriodTotal"=>$tpiPeriodTotal,
								"tpiPenalty"=>0,
								"tpiPaidTotal"=>"",
								"tpiOverdueDays"=>0
						);
						$tpiNewNo = $tpi->insert($tpiDataInput);
						
						//產條碼
						$contentPeriodInfo = get_object_vars($content["期付款1"]);
						$firstBarLastThree = substr($contentPeriodInfo["超商條碼_1"], -3);
						$url = 'http://'.DOMAIN.'/admin_advanced/ajax/appropriation/generate_barcode.php';
						$myvars = 'bank=CMC&MonthlyPayment='.$tpiPeriodTotal.'&ExpectedRepaymentDate='.str_replace("-", "", $curDate).'&shopCode2='.$contentPeriodInfo["超商條碼_2"].'&shopCode1Later3='.$firstBarLastThree;
						
						$ch = curl_init( $url );
						curl_setopt( $ch, CURLOPT_POST, 1);
						curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
						curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
						curl_setopt( $ch, CURLOPT_HEADER, 0);
						curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
						
						$response = curl_exec( $ch );
						
						$barcodeJson = json_decode($response);
						foreach($barcodeJson as $keyBar=>$valueBar){
							$barInputData = array(
									"tpiNo"=>$tpiNewNo,
									"barIndex"=>substr($keyBar,-1),
									"barBarcode"=>$valueBar
							);
							$bar->insert($barInputData);
						}
						
						//更新日期
						if($curDateArr[2] == "31"){
							$curDate = getNextMonthDate($curDate,true);
						}else if($curDateArr[2] == "30" || $curDateArr[2] == "29"){
							$curDate = getNextMonthDate($curDate,false,$curDateArr[2]);
						}else{
							$curDate = getNextMonthDate($curDate);
						}
					}
					//更改撥款狀態
					$rc->update(array("rcFinishStatus"=>5,"rcApproStatus"=>4), $rcNo);
					$rcNewData = $rc->getOne($rcNo);
					$outputData[$keyPerson] = array(
						"rcCaseNo"=>$rcNewData[0]["rcCaseNo"],
						"rcApproStatus"=>$rc->approStatusArr[$rcNewData[0]["rcApproStatus"]],
						"rcPeriodAmount"=>$rcNewData[0]["rcPeriodAmount"],
						"rcPeriodTotalEach"=>$tpiPeriodTotal
					);
					//若為NoWait改到已完成
					if($rcData[0]["rcType"] == "0"){
						$or = new API("orders");
						$or->update(array("orStatus"=>"10","orReportPeriod10Date"=>date('Y-m-d H:i:s',time())),$rcData[0]["rcRelateDataNo"]);
					} 
				}
				if(empty(array_filter($outputData))){
					echo "並無更改任何案件的撥款狀態。";
				}else{
					echo json_encode($outputData,JSON_UNESCAPED_UNICODE);
				}
		}
	}else{
		if(isset($outterElem["loanRec"])){
			foreach($json as $key=>$value){
				foreach($value as $keyPerson=>$valPerson){
					$content = get_object_vars($valPerson);
					$rcCaseNo = "20".$content["CaseNo"];
					$rc->setWhereArray(array("rcCaseNo"=>$rcCaseNo));
					$rcData = $rc->getWithConditions();
					//更新虛擬帳號、放款帳號
					$rc->update(array("rcReleaseAccount"=>$content["ContractNo"],"rcVirtualAccount"=>$content["銀行帳號_1"]), $rcData[0]["rcNo"]);
					
					if($rcData[0]["rcType"] > 0){
						$moto = new API("motorbike_cellphone_orders");
						$moto->setWhereArray(array("mcoNo"=>$rcData[0]['rcRelateDataNo']));
						$motoData = $moto->getWithConditions();
					}
					
					if($rcData[0]["rcApproStatus"] == "3"){
						$rcNo = $rcData[0]["rcNo"];
						
						//產TPI本息表
						$curDate = $rcData[0]["rcFirstPayDate"];
						$curDateArr = explode("-",$rcData[0]["rcFirstPayDate"]);
						$tpiPeriodTotal = ($rcData['0']['rcType'] == 0) ? $rcData[0]["rcPeriodTotal"]/$rcData[0]["rcPeriodAmount"]:$motoData['0']['mcoMinMonthlyTotal'];
						for($i=0; $i<$rcData[0]["rcPeriodAmount"]; $i++){
							$tpiDataInput = array(
									"rcNo"=>$rcNo,
									"tpiPeriod"=>$i+1,
									"tpiSupposeDate"=>$curDate,
									"tpiActualDate"=>"",
									"tpiPeriodTotal"=>$tpiPeriodTotal,
									"tpiPenalty"=>0,
									"tpiPaidTotal"=>"",
									"tpiOverdueDays"=>0
							);
							$tpiNewNo = $tpi->insert($tpiDataInput);
							
							//產條碼
							$contentPeriodInfo = get_object_vars($content["期付款1"]);
							$firstBarLastThree = substr($contentPeriodInfo["超商條碼_1"], -3);
							$url = 'http://'.DOMAIN.'/admin_advanced/ajax/appropriation/generate_barcode.php';
							$myvars = 'bank=CMC&MonthlyPayment='.$tpiPeriodTotal.'&ExpectedRepaymentDate='.str_replace("-", "", $curDate).'&shopCode2='.$contentPeriodInfo["超商條碼_2"].'&shopCode1Later3='.$firstBarLastThree;
							
							$ch = curl_init( $url );
							curl_setopt( $ch, CURLOPT_POST, 1);
							curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
							curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
							curl_setopt( $ch, CURLOPT_HEADER, 0);
							curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
							
							$response = curl_exec( $ch );
							
							$barcodeJson = json_decode($response);
							foreach($barcodeJson as $keyBar=>$valueBar){
								$barInputData = array(
										"tpiNo"=>$tpiNewNo,
										"barIndex"=>substr($keyBar,-1),
										"barBarcode"=>$valueBar
								);
								$bar->insert($barInputData);
							}
							
							//更新日期
							if($curDateArr[2] == "31"){
								$curDate = getNextMonthDate($curDate,true);
							}else if($curDateArr[2] == "30" || $curDateArr[2] == "29"){
								$curDate = getNextMonthDate($curDate,false,$curDateArr[2]);
							}else{
								$curDate = getNextMonthDate($curDate);
							}
						}
						//更改撥款狀態
						$rc->update(array("rcFinishStatus"=>5,"rcApproStatus"=>4), $rcNo);
						$rcNewData = $rc->getOne($rcNo);
						$outputData[$keyPerson] = array(
							"rcCaseNo"=>$rcNewData[0]["rcCaseNo"],
							"rcApproStatus"=>$rc->approStatusArr[$rcNewData[0]["rcApproStatus"]],
							"rcPeriodAmount"=>$rcNewData[0]["rcPeriodAmount"],
							"rcPeriodTotalEach"=>$tpiPeriodTotal
						);
						//若為NoWait改到已完成
						if($rcData[0]["rcType"] == "0"){
							$or = new API("orders");
							$or->update(array("orStatus"=>"10","orReportPeriod10Date"=>date('Y-m-d H:i:s',time())),$rcData[0]["rcRelateDataNo"]);
						} 
					}
				}
			}
			if(empty(array_filter($outputData))){
				echo "並無更改任何案件的撥款狀態。";
			}else{
				echo json_encode($outputData,JSON_UNESCAPED_UNICODE);
			}
		}else{
			echo "此檔案非CMC撥款成功檔。";
		}
	}
}else{
	echo $errMsg;
}