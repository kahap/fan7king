<?php

	//每天update 已還款人狀態

	include('../model/php_model.php');

	$member = new Member();

	$db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);

	$sql = "SELECT `tpi`.`rcNo`,`tpi`.`tpiPeriod`,`tpi`.`tpiActualDate` FROM `tpi`

join urge_request_records as urr on urr.rcNo = `tpi`.`rcNo` and  urr.urrCurPeriod = `tpi`.`tpiPeriod` WHERE `tpiPenalty` != '' && `tpiActualDate` != '' && `tpiIfCancelPenalty` = '0' && `tpiPeriodTotal`+`tpiPenalty` = `tpiPaidTotal` && urr.urPayStatus = '0'";

	$data = $db->selectRecords($sql);

	

	

	foreach($data as $key => $value){

		$sql1 =  "update `urge_request_records` set `urPayStatus` = '1', PayDate='".$value['tpiActualDate']."' WHERE `rcNo` = '".$value['rcNo']."' && `urrCurPeriod` = '".$value['tpiPeriod']."' ";

		$db->updateRecords($sql1);

	}

	

	$sql2 = "SELECT * FROM `urge_request_records`,tpi WHERE urge_request_records.rcNo = tpi.rcNo and urge_request_records.urrCurPeriod = tpi.tpiPeriod and tpi.tpiActualDate != '' and tpi.tpiPeriodTotal = tpi.tpiPaidTotal and urge_request_records.urPayStatus = 0";

	$data = $db->selectRecords($sql2);

	foreach($data as $key => $value){

		$sql1 =  "update `urge_request_records` set `urPayStatus` = '1', PayDate='".$value['tpiActualDate']."' WHERE `rcNo` = '".$value['rcNo']."' && `urrCurPeriod` = '".$value['tpiPeriod']."' ";

		$db->updateRecords($sql1);

	}  
	

?>