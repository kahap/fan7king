<?php
	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../model/require_login.php');
	
	$or = new Orders();
	$pro = new Product();
	$pm = new Product_Manage();
	$mem = new Member();
	
	$allOrData = $or->getAllOrders();
	
	$errMsg = "";
	
	$orNoArr = $_POST["orNo"];
	$orStatusArr = $_POST["orStatus"];
	$orInternalCaseNoArr = $_POST["orInternalCaseNo"];
	
	foreach($orInternalCaseNoArr as $key=>$value){
		$orDataEach = $or->getOneOrderByNo($orNoArr[$key]);
		if((trim($value) == "" && $orStatusArr[$key] == 2) || ($orDataEach[0]["orIfProcessInCurrentStatus"] == 0 && $orStatusArr[$key] == 2)){
			$errMsg .= "訂單編號: ".$orDataEach[0]["orCaseNo"]." 的'內部訂單編號'沒填或者訂單尚未列印，請確認修改狀態時必須輸入內部訂單編號及完成列印。#".$orDataEach[0]["orNo"]."#\n";
		}
		foreach($allOrData as $keyIn=>$valueIn){
			if($valueIn["orInternalCaseNo"] == $value && $value != "" && $valueIn["orNo"] != $orNoArr[$key]){
				$errMsg .= "訂單編號: ".$orDataEach[0]["orCaseNo"]." 的'內部訂單編號'與 訂單編號: ".$valueIn["orCaseNo"]."重複。#".$orDataEach[0]["orNo"]."#\n";
			}
		}
	}
	
	if(count(array_unique(array_values(array_filter($orInternalCaseNoArr))))<count(array_values(array_filter($orInternalCaseNoArr)))){
		$errMsg .= "您所輸入的內部訂單編號有重複，請確認。";
	}
	
	
	if($errMsg == ""){
		$deviceTokenArr = array();
		$androidRigistIdArr = array();
		$androidDataArr = array();
		$msgArr = array();
		$otherInfoArr = array();
		$count = 0;
		foreach($orNoArr as $key=>$value){
			$orOldData = $or->getOneOrderByNo($value);
			$or->updateInternalCaseNo($orInternalCaseNoArr[$key], $value);
			//增加審查時間紀錄
			if($orOldData[0]["orStatus"] != $orStatusArr[$key]){
				$memData = $mem->getOneMemberByNo($orOldData[0]["memNo"]);
				$pmData = $pm->getOnePMByNo($orOldData[0]["pmNo"]);
				$proData = $pro->getOneProByNo($pmData[0]["proNo"]);
				
				$or->updateStatus($orStatusArr[$key], $value);
				$or->updateStatusTime($orStatusArr[$key],$value);
				$or->updateIfProcess(0, $value);
				$or->updateProcessTime("", $value);
				//讓使用者不可編輯訂單
				$or->updateIfEditable(1, $value);
				
				//推播
				$memNo = $orOldData[0]["memNo"];
				$api = new API("app_data");
				$api->getUniqueDeviceToken($memNo);
				$data = $api->getData();
				if($data != null){
					$msgArr[$count] = "您訂購的商品 - ".$proData[0]["proName"]."，".$or->statusArr[$orStatusArr[$key]];
					$otherInfoArr[$count] = $value;
					$pushTitle = "訂單狀態更新通知";
					$apiIc = new API("inform_center");
					$icDataInput = array(
						"memNo"=>$memNo,
						"orNo"=>$otherInfoArr[$count],
						"icTitle"=>$pushTitle,
						"icContent"=>$msgArr[$count],
						"icDate"=>date("Y-m-d H:i:s",time())
					);
					$apiIc->insert($icDataInput);
					foreach($data as $whichAd=>$valueAd){
						if($valueAd["adDeviceId"] != ""){
							if(strlen($valueAd["adDeviceId"]) <= 65){
								$deviceTokenArr[$count][] = $valueAd["adDeviceId"];
							}else{
								$androidRigistIdArr[$count][] = $valueAd["adDeviceId"];
							}
						}
					}
					push_android($androidRigistIdArr[$count],$msgArr[$count],$pushTitle,$otherInfoArr[$count]);
					$count++;
				}
				
				//寄送EMAIL通知
				$email = new Email();
				$msg = sendEmailForStatChange($orStatusArr[$key],$orOldData,$memData,$pmData,$proData,$email);
			}
		}
		$push = push("happy.pem",'happy',$deviceTokenArr,$msgArr,$otherInfoArr);
		echo "更新成功";
	}else{
		echo $errMsg;
	}
	
	

?>
