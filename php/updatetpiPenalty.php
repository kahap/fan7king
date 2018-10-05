<?php

	//每天update滯納金

	include('../model/php_model.php');

	$member = new Member();

	$db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);

	//更新連假後產生的滯納金
	

	$sql = "update `tpi` set `tpiPenalty` = '0' WHERE `tpiActualDate` != '' && `tpiPenalty` = '300' && `tpiOverdueDays` <= 2 && tpiPeriodTotal = tpiPaidTotal";

	$db->updateRecords($sql);

	$sql1 = "update `tpi` set  `tpiPenalty` = '0',`tpiOverdueDays` = DATEDIFF(tpiActualDate,tpiSupposeDate) WHERE `tpiSupposeDate` > `tpiActualDate` && `tpiPeriodTotal` = `tpiPaidTotal` && `tpiPenalty` != 0";

	$db->updateRecords($sql1);

	$sql2 = "update `tpi` set `tpiOverdueDays` = DATEDIFF(`tpiActualDate`,`tpiSupposeDate`) WHERE `tpiOverdueDays` != DATEDIFF(`tpiActualDate`,`tpiSupposeDate`) && `tpiActualDate` != ''";

	$db->updateRecords($sql2);

	

	

	//更新連續兩期以上有滯納金的案件

	$sql3 = "update `tpi` set `tpiOverdueDays` = DATEDIFF(CURDATE(),`tpiSupposeDate`),`tpiPenalty` = '300' WHERE `tpiActualDate` = '' && `tpiSupposeDate` <= DATE_SUB(NOW(), INTERVAL 3 DAY) && `tpiPenalty` = '0'";

	$db->updateRecords($sql3);

	

  //抓出所有未正確還款案件

	$sql4 = "select * from `tpi` WHERE `tpiActualDate` != '' and `tpiPeriodTotal`+`tpiPenalty` != `tpiPaidTotal` and `tpiPeriodTotal` > `tpiPaidTotal` and `tpiIfCancelPenalty` = '0' && `tpiOverdueDays` >= 3"; 

	$data = $db->selectRecords($sql4);

	

	foreach($data as $key => $value){

		$sql5 = "update `tpi` set `tpiOverdueDays` = DATEDIFF(CURDATE(),`tpiSupposeDate`) WHERE `tpiNo` = '".$value['tpiNo']."'";
		$db->updateRecords($sql5);

		

		$sql6 = "update `urge_request_records` set `urPayStatus` = '0',`PayDate` = '' WHERE `rcNo` = '".$value['rcNo']."' && `urrCurPeriod` = '".$value['tpiPeriod']."'";

		$db->updateRecords($sql6); 

	}

	

	 //抓出所有未正確還款案件

	$sql6 = "SELECT * FROM `tpi` WHERE `tpiPaidTotal` = '300'"; 

	$data = $db->selectRecords($sql6);

	

	foreach($data as $key => $value){

		$sql7 = "update `tpi` set `ErrorTime` = '".$value['tpiActualDate']."', `tpiActualDate` = '',`tpiOverdueDays` = '' WHERE `rcNo` = '".$value['rcNo']."' && `tpiPeriod` = '".$value['tpiPeriod']."'";

		$db->updateRecords($sql7);

	}



	//抓出逾期天數未正確的

	$sql8 = "update `tpi` set tpiOverdueDays = DATEDIFF(now(),`tpiSupposeDate`),tpiPenalty = '300'  WHERE `tpiActualDate` = '' and  DATEDIFF(now(),`tpiSupposeDate`) >= 3  and `tpiOverdueDays` = 0";

	$db->updateRecords($sql8);
	
	//抓出逾期天數未正確的

	$sql9 = "update `tpi` set tpiOverdueDays = DATEDIFF(now(),`tpiSupposeDate`),tpiPenalty = '300'  WHERE `tpiActualDate` = '' and  DATEDIFF(now(),`tpiSupposeDate`) >= 3";

	$db->updateRecords($sql9);

	
	


?>