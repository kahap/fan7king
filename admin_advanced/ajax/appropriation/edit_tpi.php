<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

session_start();
date_default_timezone_set('Asia/Taipei');

$rc = new API("real_cases");
$tpi = new API("tpi");
$etr = new API("edit_tpi_records");

$tpiNoArr = $_POST["tpiNo"];
$tpiDateArr = $_POST["tpiActualDate"];
$tpiPaidArr = $_POST["tpiPaidTotal"];
$tpiIfCancelPenaltyArr = $_POST["tpiIfCancelPenalty"];

$tpiTemp = $tpi->getOne($tpiNoArr[0]);
$rcData = $rc->getOne($tpiTemp[0]["rcNo"]);
$tpi->setWhereArray(array("rcNo"=>$rcData[0]["rcNo"]));
$tpi->setOrderArray(array("tpiPeriod"=>false));
$tpiData = $tpi->getWithConditions();

foreach($tpiData as $eachTpi=>$tpiContent){
	foreach($tpiNoArr as $key=>$value){
		$tpi->update(array("tpiIfCancelPenalty"=>$tpiIfCancelPenaltyArr[$key]), $value);
		if($tpiIfCancelPenaltyArr[$key] == "1"){
			//滯納金調整為0
			$tpiPenalty[$key] = '0';
			$tpi->update(array("tpiPenalty"=>$tpiPenalty[$key]), $value);
		}
		if($tpiContent["tpiNo"] == $value && ($tpiDateArr[$key] != $tpiContent["tpiActualDate"] || $tpiPaidArr[$key] != $tpiContent["tpiPaidTotal"])){
			if(($tpiDateArr[$key] != "" && DateTime::createFromFormat("Y-m-d", $tpiDateArr[$key]) !== false) || ($tpiPaidArr[$key] != "" && is_numeric($tpiPaidArr[$key]))){
				$log = "";
				if($tpiDateArr[$key] != $tpiContent["tpiActualDate"]){
					$log .= "第".$tpiContent["tpiPeriod"]."期的日期：從 ".$tpiContent["tpiActualDate"]." 變更到 ".$tpiDateArr[$key]."<br>";
					$tpi->update(array("tpiActualDate"=>$tpiDateArr[$key]), $value);
// 					if($tpiDateArr[$key] != ""){
// 						$days = (strtotime($tpiDateArr[$key]) - strtotime($tpiContent["tpiSupposeDate"]))/86400;
// 						$tpi->update(array("tpiOverdueDays"=>$days), $value);
// 						if($days >= 7){
// 							$tpi->update(array("tpiPenalty"=>$tpi->getPenaltyAmount()), $value);
// 						}
// 					}
				}
				if($tpiPaidArr[$key] != $tpiContent["tpiPaidTotal"]){
					$origAmount = $tpiContent["tpiPaidTotal"] == "" ? "無" : $tpiContent["tpiPaidTotal"];
					$editAmount = $tpiPaidArr[$key] == "" ? "無" : $tpiPaidArr[$key];
					$log .= "第".$tpiContent["tpiPeriod"]."期的實付金額：從 ".$origAmount." 變更到 ".$editAmount."<br>";
					$tpi->update(array("tpiPaidTotal"=>$tpiPaidArr[$key]), $value);
				}
				$etrInputData = array(
					"rcNo"=>$rcData[0]["rcNo"],
					"aauNo"=>$_SESSION["adminUserData"]["aauNo"],
					"etrDetails"=>$log
				);
				$etr->insert($etrInputData);
			}
		}
	}
}

//重新分配錢數
$tpiNewData = $tpi->getWithConditions();
$tpiRecalc = new TPI($tpiNewData);
$tpiRecalc->setFinalTotal(0);
$tpiRecalc->recalculate($tpi, date("Y-m-d",time()));
$tpiRecalc->checkIfFinishPaying($tpiTemp[0]["rcNo"]);

echo "OK";


?>