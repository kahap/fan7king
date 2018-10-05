<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

$or = new API("orders");

$orNoArr = 

$orNewData = $or->getOne($orNo);
$outputData[$keyPerson] = array(
		"orRealCaseNo"=>$orNewData[0]["orRealCaseNo"],
		"orApproStatus"=>$or->approStatusArr[$orNewData[0]["orApproStatus"]],
		"orPeriodAmnt"=>$orNewData[0]["orPeriodAmnt"],
		"orPeriodTotalEach"=>$orNewData[0]["orPeriodTotal"]/$orNewData[0]["orPeriodAmnt"]
);

?>