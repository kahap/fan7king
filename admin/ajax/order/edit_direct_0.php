<?php
	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../model/require_login.php');
	
	$or = new Orders();
	
	$errMsg = "";
	
	$orNoArr = $_POST["orNo"];
	
	date_default_timezone_set('Asia/Taipei');
	$date = date('Y-m-d', time());
	
	foreach($orNoArr as $key=>$value){
		$orDataEach = $or->getOneOrderByNo($value);
		if($orDataEach[0]["orStatus"] == 0 && $orDataEach[0]["orPaySuccess"] == 0){
			$errMsg .= "訂單編號: ".$orDataEach[0]["orCaseNo"]." 的尚未完成付款，無法更換為出貨中。\n";
		}
	}
	
	
	if($errMsg == ""){
		foreach($orNoArr as $key=>$value){
			$orOldData = $or->getOneOrderByNo($value);
			//增加審查時間紀錄
			$or->updateStatusTimeDirect(2,$value);
			$or->updateStatus(2, $value);
			$or->updateIfProcess(0, $value);
			$or->updateProcessTime("", $value);
			//設定訂貨日期
			$or->updateOrderFromSupDate($date, $value);
		}
		echo "更新成功";
	}else{
		echo $errMsg;
	}
	
	

?>
