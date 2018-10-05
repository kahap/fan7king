<?php
session_start();
	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../model/require_login.php');
	
	$or = new Orders();
	
	$errMsg = "";
	
	$orNoArr = $_POST["orNo"];
	$orStatusArr = $_POST["orStatus"];
	$orHandleRefundDateArr = $_POST["orHandleRefundDate"];
	
	foreach($orHandleRefundDateArr as $key=>$value){
		$orDataEach = $or->getOneOrderByNo($orNoArr[$key]);
		if(trim($value) == "" && $orStatusArr[$key] == 13){
			$errMsg .= "訂單編號: ".$orDataEach[0]["orCaseNo"]." 要更改為完成退貨前必須填入退貨簽收日期。#".$orDataEach[0]["orCaseNo"]."#\n";
		}
	}
	
	if($errMsg == ""){
		foreach($orNoArr as $key=>$value){
			$orOldData = $or->getOneOrderByNo($value);
			$or->updateStatus($orStatusArr[$key], $value);
			if($orOldData[0]["orStatus"] != $orStatusArr[$key]){
				$or->updateStatusTime($orStatusArr[$key], $value);
				$or->updateIfProcess(0, $value);
				$or->updateProcessTime("", $value);
			}
			$or->updateRefundDate($orHandleRefundDateArr[$key], $value);
		}
			$sql = "select rcNo from real_cases where rcRelateDataNo = '".$_POST["orNo"]."' && rcType = '0' ";
			$data = $or->getSql($sql);
			$Record = "insert into service_record (rcNo,aauNoService,content,time) value('".$data['0']['rcNo']."','".$_SESSION['userdata']['smName']."','狀態由".($or->statusArr[$orOldData[0]["orStatus"]])."變成".($or->statusArr[$_POST["orStatus"]])."','".date('Y-m-d H:i:s',time())."')";
			$or->getSql($Record);
		echo "更新成功";
	}else{
		echo $errMsg;
	}
	
	

?>
