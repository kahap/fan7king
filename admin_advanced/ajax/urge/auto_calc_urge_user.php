<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

session_start();

$rc = new API("real_cases");
$tpi = new API("tpi");
$uar = new API("urge_auto_request");
$up = new API("urge_period");
$uu = new API("urge_user");
$aau = new API("admin_advanced_user");
$urr = new API("urge_request_records");
$apiMem = new API("member");

$upData = $up->getAll();
$uarData = $uar->getAll();

/*已改寫另一支程式檢查是否已經還款
//先找出正在"催收中"的案件，檢查是否已繳回，繳回即更改狀態
$urr->setWhereArray(array("urrStatus"=>"0"));
$urrCheckData = $urr->getWithConditions();

if($urrCheckData != null){
	foreach($urrCheckData as $key=>$value){
		$tpi->setWhereArray(array("tpiPeriod"=>$value["urrCurPeriod"],"rcNo"=>$value["rcNo"]));
		$tpiCheckData = $tpi->getWithConditions();
		if($tpiCheckData[0]["tpiActualDate"] != ""){
			$urr->update(array("urrStatus"=>2), $value["urrNo"]);
		}
	}
}
*/
//找出所有逾期的案件
$minDays = array();
$transferCases = array();
$allOverdueData = $tpi->getAllOverdueForUrge($upData['0']['upMin']);
if($allOverdueData != null){
	foreach($allOverdueData as $key=>$value){
		//如果在逾期轉交當天的話
		foreach($upData as $k => $v){
			if($value["tpiOverdueDays"] >= $v['upMin'] and $value["tpiOverdueDays"] <= $v['upMax']){
				$transferCases[$v['upName']][] = array("rcNo"=>$value["rcNo"],"upName"=>$v['upName'],"shouldPay"=>$value["tpiPeriodTotal"]+$value["tpiPenalty"],"period"=>$value["tpiPeriod"],"overday"=>$value["tpiOverdueDays"]);
			}
		}
	}
}
//print_r($transferCases);



//找出M0現有人數
$uu->setWhereArray(array("upNo"=>"1"));
$m0Data = $uu->getWithConditions();
$m0Num = count($m0Data)-1;
//找出M1現有人數
$uu->setWhereArray(array("upNo"=>"2"));
$m1Data = $uu->getWithConditions();
$m1Num = count($m1Data)-1;
//找出M2現有人數
$uu->setWhereArray(array("upNo"=>"3"));
$m2Data = $uu->getWithConditions();
$m2Num = count($m2Data)-1;
//找出M3現有人數
$uu->setWhereArray(array("upNo"=>"4"));
$m3Data = $uu->getWithConditions();
$m3Num = count($m3Data)-1;
//找出M4現有人數
$uu->setWhereArray(array("upNo"=>"5"));
$m4Data = $uu->getWithConditions();
$m4Num = count($m4Data)-1;
//找出M5現有人數
$uu->setWhereArray(array("upNo"=>"6"));
$m5Data = $uu->getWithConditions();
$m5Num = count($m5Data)-1;
//找出M6現有人數
$uu->setWhereArray(array("upNo"=>"7"));
$m6Data = $uu->getWithConditions();
$m6Num = count($m6Data)-1;
//找出M6+現有人數
$uu->setWhereArray(array("upNo"=>"8"));
$m6PlusData = $uu->getWithConditions();
$m6PlusNum = count($m6PlusData)-1;

print_r($m3Data);
$result = array("M0"=>$m0Data,"M1"=>$m1Data,"M2"=>$m2Data,"M3"=>$m3Data,"M4"=>$m4Data,"M5"=>$m5Data,"M6"=>$m6Data,"M7"=>$m7Data,"M6+"=>$m6PlusData);
//開始派件
$curM0Index = $uarData[0]["uarM0Index"];
$curM1Index = $uarData[0]["uarM1Index"];
$curM2Index = $uarData[0]["uarM2Index"];
$curM3Index = $uarData[0]["uarM3Index"];
$curM4Index = $uarData[0]["uarM4Index"];
$curM5Index = $uarData[0]["uarM5Index"];
$curM6Index = $uarData[0]["uarM6Index"];
$curM6PlusIndex = $uarData[0]["uarM6PlusIndex"];

foreach($transferCases as $key=> $value){
	$total = "m".(($key == "M6+") ? "6Plus":substr($key,1,1))."Num";
	$str = "uarM".(($key == "M6+") ? "6Plus":substr($key,1,1))."Index";
    $currPerson = $uarData[0][$str];
	foreach($value as $k => $v){
	//派件給人	
	$urr->setWhereArray(array("rcNo"=>$v['rcNo'],"urrCurPeriod"=>$v["period"],"urrCurMValue"=>$key));
	$urrdata = $urr->getWithConditions();
	//判斷是否有建立過資料
	if($urrdata == ""){
			if($currPerson >= ($$total)){
				$currPerson = 0;
			}else{
				$currPerson++;
			}
			//增加派件紀錄
			$curUuData = $result[$key][$currPerson]['aauNo'];
			if($key != "M0" and $key != "M1"){
				$urr->setWhereArray(array("rcNo"=>$v["rcNo"],"urrCurPeriod"=>$v["period"],"urrCurMValue"=>"M1"));
				$urrdata1 = $urr->getWithConditions();
				if($urrdata1['0']['uuNo'] != ""){
					$curUuData = $urrdata1['0']['uuNo'];
				}else{
					$urr->setWhereArray(array("rcNo"=>$v["rcNo"]));
					$urrdata1 = $urr->getWithConditions();
					$curUuData = $urrdata1['0']['uuNo'];
				}

			}
			
			if($v['overday'] >='35' and $v['overday'] <= '95'){
				$sql1  = "SELECT * FROM `urge_request_records` where rcNo = '".$v["rcNo"]."' order by urrNo desc limit 1";
				$preData = $apiMem->customSql($sql1);
				if($preData['0']['PayDate'] != ''){
					$curUuData = $preData['0']['uuNo'];
				}
			}
			if($v['overday'] >='185'){
				$sql1  = "SELECT * FROM `urge_request_records` where rcNo = '".$v["rcNo"]."' order by urrNo desc limit 1";
				$preData = $apiMem->customSql($sql1);
				if($preData['0']['PayDate'] != ''){
					$curUuData = $preData['0']['uuNo'];
				}
			}
			$rc->update(array("aauNoUrge"=>$result[$key][$currPerson]['aauNo']),$v["rcNo"]);
			$urrDataInput = array(
				"uuNo"=>$curUuData,
				"rcNo"=>$v["rcNo"],
				"aauNoRequest"=>"",
				"urrCurMValue"=>$v["upName"],
				"urrCurPeriod"=>$v["period"],
				"urrOverdueAomunt"=>$v["shouldPay"],
				"urrStatus"=>0,
				"uuNoTransferTo"=>""
			);
			$urr->insert($urrDataInput);
			if($key != "M0"){
				$number = "M".substr($v["upName"],1,1)-1;
				$system  = "delete  FROM `urge_request_records` where rcNo = '".$v["rcNo"]."' && urrCurMValue = '".$number."'";
				$system_data = $apiMem->customSql($system);
			}

			//並重算INDEX
			switch($key){
				case "M0":
				case "M1": 
				case "M2":
				case "M3":
				case "M4":
				case "M5":
				case "M6":
					$uar->update(array("uar".$key."Index"=>$currPerson),1);
					break;
				case "M6+":
					$uar->update(array("uarM6PlusIndex"=>$currPerson),1);
					break;
			}
			
	}
  }
}
/*
if(!empty($transferCases)){
	foreach($transferCases as $key=>$value){
		foreach($value as $k => $v){
			switch($key){
				case "M0":
					sendRequestToPerson($curM0Index,$m0Num,$m0Data,$v,$rc,$urr,$uar);
					break;
				case "M1":
					sendRequestToPerson($curM1Index,$m1Num,$m1Data,$v,$rc,$urr,$uar);
					break;
				case "M2":
					//$urrOldData = checkOldRecord($value,"M1",$urr);
					sendRequestToPerson($curM1Index,$m2Num,$m2Data,$v,$rc,$urr,$uar);
					break;;
				case "M3":
					//$urrOldData = checkOldRecord($value,"M2",$urr);
					sendRequestToPerson($curM3Index,$m3Num,$m3Data,$v,$rc,$urr,$uar);
					break;;
				case "M4":
					sendRequestToPerson($curM4Index,$m4Num,$m4Data,$v,$rc,$urr,$uar);
					break;;
				case "M5":
					//$urrOldData = checkOldRecord($value,"M4",$urr);
					sendRequestToPerson($curM5Index,$m5Num,$m5Data,$v,$rc,$urr,$uar);
					break;;
				case "M6":
					//$urrOldData = checkOldRecord($value,"M5",$urr);
					sendRequestToPerson($curM6Index,$m6Num,$m6Data,$v,$rc,$urr,$uar);
					break;;
				case "M6+":
					//$urrOldData = checkOldRecord($value,"M6",$urr);
					sendRequestToPerson($curM6PlusIndexIndex,$m6PlusNumNum,$m6PlusData,$v,$rc,$urr,$uar);
					break;
			}
		}
	}
}

 

//派件function
function sendRequestToPerson(&$index,$number,$data,$value,$rc,$urr,$uar,$urrOld=null){

	$curUuData = $data[$index]['aauNo'];
	//派件給人
	$rc->update(array("aauNoUrge"=>$curUuData),$value["rcNo"]);
	if($urrOld != null){
		$urr->update(array("uuNoTransferTo"=>$curUuData),$urrOld[0]["urrNo"]);
	}	
	$urr->setWhereArray(array("rcNo"=>$value["rcNo"],"urrCurPeriod"=>$value["period"],"urrCurMValue"=>$value["upName"]));
	$urrdata = $urr->getWithConditions();
	//判斷是否有建立過資料
	if($urrdata == ""){
		//增加派件紀錄
		if($value["upName"] != "M0" and $value["upName"] != "M6+"){
			$urr->setWhereArray(array("rcNo"=>$value["rcNo"],"urrCurPeriod"=>$value["period"],"urrCurMValue"=>"M0"));
			$urrdata1 = $urr->getWithConditions();
			$curUuData = $urrdata1['0']['uuNo'];

		}
		$urrDataInput = array(
			"uuNo"=>$curUuData,
			"rcNo"=>$value["rcNo"],
			"aauNoRequest"=>"",
			"urrCurMValue"=>$value["upName"],
			"urrCurPeriod"=>$value["period"],
			"urrOverdueAomunt"=>$value["shouldPay"],
			"urrStatus"=>0,
			"uuNoTransferTo"=>""
		);
		$urr->insert($urrDataInput);
		if($index == ($number-1)){
			$index = 0;
		}else{
			$index++;
		}
		//並重算INDEX
		switch($value["upName"]){
			case "M0":
			case "M1":
			case "M2":
			case "M3":
			case "M4":
			case "M5":
			case "M6":
				$uar->update(array("uar".$value["upName"]."Index"=>$index),1);
				break;
			case "M6+":
				$uar->update(array("uarM6PlusIndex"=>$index),1);
				break;
		}
	}
}
/*
//更新回收失敗
function checkOldRecord($value,$upName,$urr){
	//先檢查是否M0有未回收
	$rcNo = $value["rcNo"];
	$urr->setWhereArray(array("rcNo"=>$rcNo,"urrCurMValue"=>$upName,"urrStatus"=>0));
	$urrCurData = $urr->getWithConditions();
	if($urrCurData != null){
		//舊紀錄更新為未回收
		$urr->update(array("urrStatus"=>1),$urrCurData[0]["urrNo"])	;
	}
	return $urrCurData;
}
*/
?>