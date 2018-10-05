<?php
header (" content-type: text/HTML; charset=utf-8 ");
require_once('../../model/require_login.php');

$or = new Orders();

$orNo = $_POST["orNo"];

$orData = $or->getOneOrderByNo($orNo);

if($orData[0]["orIfProcessInCurrentStatus"] == 0){
	date_default_timezone_set('Asia/Taipei');
	$date = date('Y-m-d H:i:s', time());
	
	$or->updateIfProcess(1, $orNo);
	$or->updateProcessTime($date, $orNo);
	
	echo "已將訂單編號：".$orData[0]["orCaseNo"]."　登錄成'已處理'，並登錄'處理時間'！";
}

?>