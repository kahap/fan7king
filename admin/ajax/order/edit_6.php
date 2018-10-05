<?php
	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../model/require_login.php');
	
	$or = new Orders();
	
	$errMsg = "";
	
	$orNoArr = $_POST["orNo"];
	$orStatusArr = $_POST["orStatus"];
	
	foreach($orStatusArr as $key=>$value){
		$orDataEach = $or->getOneOrderByNo($orNoArr[$key]);
		if($orDataEach[0]["orIfProcessInCurrentStatus"] == 0 && $orStatusArr[$key] == 2){
			$errMsg .= "訂單編號: ".$orDataEach[0]["orCaseNo"]." 若要送件'審查中'前，請先完成列印。#".$orDataEach[0]["orNo"]."#\n";
		}
	}
	
	if($errMsg == ""){
		foreach($orNoArr as $key=>$value){
			$orOldData = $or->getOneOrderByNo($value);
			$or->updateStatus($orStatusArr[$key],$value);
			if($orOldData[0]["orStatus"] != $orStatusArr[$key]){
				$or->updateStatusTime($orStatusArr[$key], $value);
				$or->updateIfProcess(0, $value);
				$or->updateProcessTime("", $value);
			}
		}
		echo "更新成功";
	}else{
		echo $errMsg;
	}
	
	

?>
