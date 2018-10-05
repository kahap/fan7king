<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

date_default_timezone_set('Asia/Taipei');

//逾期天數
$tpi = new API("tpi");
$rc = new API("real_cases");
// $bar = new API("barcode");

$arr = ["201704085004","201704115003","201704145054","201704195028","201704205002","201704205027",
		"201704245003","201704245014"];

foreach($arr as $rcCaseNo){
	$rc->setWhereArray(array("rcCaseNo"=>$rcCaseNo));
	$rcData = $rc->getWithConditions();

	$tpi->setWhereArray(array("rcNo"=>$rcData[0]["rcNo"]));
	$tpiData = $tpi->getWithConditions();

	if($tpiData != null){
		foreach($tpiData as $key=>$value){
			if($value["tpiActualDate"] == ""){
				$tpi->update(array("tpiOverdueDays"=>""), $value["tpiNo"]);
			}
		}
	}
}


?>