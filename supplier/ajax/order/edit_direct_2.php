<?php
	header (" content-type: text/HTML; charset=utf-8 ");
	require_once('../../model/require_login.php');
	
	$or = new Orders();
	
	$errMsg = "";
	
	$orNoArr = $_POST["orNo"];
	$orStatusArr = $_POST["orStatus"];
	$orHandleSupOutDateArr = $_POST["orHandleSupOutDate"];
	$orHandleTransportCompArr = $_POST["orHandleTransportComp"];
	$orHandleTransportSerialNumArr = $_POST["orHandleTransportSerialNum"];
	$orHandleGetFromSupDateArr = $_POST["orHandleGetFromSupDate"];
	
	foreach($orHandleGetFromSupDateArr as $key=>$value){
		$orDataEach = $or->getOneOrderByNo($orNoArr[$key]);
		if(trim($value) == "" && $orStatusArr[$key] == 3){
			$errMsg .= "訂單編號: ".$orDataEach[0]["orCaseNo"]." 要更改為已收貨前必須填入到貨日期。#".$orDataEach[0]["orCaseNo"]."#\n";
		}
	}
	
	if($errMsg == ""){
		foreach($orNoArr as $key=>$value){
			$orOldData = $or->getOneOrderByNo($value);
			//增加審查時間紀錄
			if($orOldData[0]["orStatus"] != $orStatusArr[$key]){
				$or->updateStatusTimeDirect($orStatusArr[$key],$value);
				$or->updateIfProcess(0, $value);
				$or->updateProcessTime("", $value);
			}
			$or->updateStatus($orStatusArr[$key], $value);
			$or->updateOrderAndGetFromSup($orHandleSupOutDateArr[$key], $orHandleTransportCompArr[$key], $orHandleTransportSerialNumArr[$key], $orHandleGetFromSupDateArr[$key], $value);
		}
		echo "更新成功";
	}else{
		echo $errMsg;
	}
	
	

?>
