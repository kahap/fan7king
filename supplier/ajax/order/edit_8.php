<?php
	session_start();
	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../model/require_login.php');
	
	$or = new Orders();
	$pro = new Product();
	$pm = new Product_Manage();
	$mem = new Member();
	
	$errMsg = "";
	
	$orNoArr = $_POST["orNo"];
	$orStatusArr = $_POST["orStatus"];
	$orHandleSupOutDateArr = $_POST["orHandleSupOutDate"];
	$orHandleTransportCompArr = $_POST["orHandleTransportComp"];
	$orHandleTransportSerialNumArr = $_POST["orHandleTransportSerialNum"];
	$orHandleGetFromSupDateArr = $_POST["orHandleGetFromSupDate"];
	
	foreach($orHandleGetFromSupDateArr as $key=>$value){
		$orDataEach = $or->getOneOrderByNo($orNoArr[$key]);
		if(trim($value) == "" && $orStatusArr[$key] == 9){
			$errMsg .= "訂單編號: ".$orDataEach[0]["orCaseNo"]." 要更改為已收貨前必須填入到貨日期。#".$orDataEach[0]["orCaseNo"]."#\n";
		}
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
			$or->updateStatus($orStatusArr[$key], $value);
			$or->updateOrderAndGetFromSup($orHandleSupOutDateArr[$key], $orHandleTransportCompArr[$key], $orHandleTransportSerialNumArr[$key], $orHandleGetFromSupDateArr[$key], $value);
			if($orOldData[0]["orStatus"] != $orStatusArr[$key]){
				$memData = $mem->getOneMemberByNo($orOldData[0]["memNo"]);
				$pmData = $pm->getOnePMByNo($orOldData[0]["pmNo"]);
				$proData = $pro->getOneProByNo($pmData[0]["proNo"]);
				
				$or->updateStatusTime($orStatusArr[$key], $value);
				$or->updateIfProcess(0, $value);
				$or->updateProcessTime("", $value);
				
				//推播
				$memNo = $orOldData[0]["memNo"];
				$api = new API("app_data");
				/*$api->getUniqueDeviceToken($memNo);
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
				}*/
				
				//寄送EMAIL通知
				$email = new Email();
				sendEmailForStatChange($orStatusArr[$key],$orOldData,$memData,$pmData,$proData,$email);
					$sql = "select rcNo from real_cases where rcRelateDataNo = '".$value."' && rcType = '0' ";
			$data = $or->getSql($sql);
			$Record = "insert into service_record (rcNo,aauNoService,content,time) value('".$data['0']['rcNo']."','".$_SESSION['supplieruserdata']['supName']."','狀態由".($or->statusArr[$orOldData[0]["orStatus"]])."變成".($or->statusArr[$orStatusArr[$key]])."','".date('Y-m-d H:i:s',time())."')";
			$or->getSql($Record);
			}			
		}
		//$push = push("happy.pem",'happy',$deviceTokenArr,$msgArr,$otherInfoArr);
		echo "更新成功";
	}else{
		echo $errMsg;
	}
	
	

?>
