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
	$orDocProvideReasonArr = $_POST["orDocProvideReason"];
	$orDocProvideCommentArr = $_POST["orDocProvideComment"];
	
	
	if($errMsg == ""){
		$deviceTokenArr = array();
		$androidRigistIdArr = array();
		$androidDataArr = array();
		$msgArr = array();
		$otherInfoArr = array();
		$count = 0;
		foreach($orNoArr as $key=>$value){
			$orOldData = $or->getOneOrderByNo($value);
			$or->updateDocProvideReason($orDocProvideReasonArr[$key],$orDocProvideCommentArr[$key], $value);
			$orNewData = $or->getOneOrderByNo($value);
			
			if($orDocProvideReasonArr[$key] != "無" && $orDocProvideReasonArr[$key] != "0" &&
				((($orDocProvideReasonArr[$key] == "1" || $orDocProvideReasonArr[$key] == "自訂") && (($orOldData[0]["orDocProvideComment"] != $orDocProvideCommentArr[$key] && $orDocProvideCommentArr[$key] != "") || ($orDocProvideReasonArr[$key] != $orOldData[0]["orDocProvideReason"]))) ||
				$orDocProvideReasonArr[$key] != "1" && $orDocProvideReasonArr[$key] != "自訂" && ($orOldData[0]["orDocProvideReason"] != $orDocProvideReasonArr[$key] || $orOldData[0]["orDocProvideComment"] != $orDocProvideCommentArr[$key]))
				){
				//推播
				$memData = $mem->getOneMemberByNo($orOldData[0]["memNo"]);
				$pmData = $pm->getOnePMByNo($orOldData[0]["pmNo"]);
				$proData = $pro->getOneProByNo($pmData[0]["proNo"]);
				$memNo = $orNewData[0]["memNo"];
				$api = new API("app_data");
				$api->getUniqueDeviceToken($memNo);
				$data = $api->getData();
				if($data != null){
					if($orNewData[0]["orDocProvideReason"] == "1"){
						$msgArr[$count] = "您訂購的商品 - ".$proData[0]["proName"]."，".$orNewData[0]["orDocProvideComment"];
					}else{
						$msgArr[$count] = "您訂購的商品 - ".$proData[0]["proName"]."，".$or->reasonArr[$orNewData[0]["orDocProvideReason"]]."。".$orNewData[0]["orDocProvideComment"];
					}
					$otherInfoArr[$count] = $value;
					$pushTitle = "您的訂單需要補件";
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
				sendEmailForStatChange(5,$orNewData,$memData,$pmData,$proData,$email);
			}
			$sql = "select rcNo from real_cases where rcRelateDataNo = '".$value."' && rcType = '0' ";
			$data = $or->getSql($sql);
			$Record = "insert into service_record (rcNo,aauNoService,content,time) value('".$data['0']['rcNo']."','".$_SESSION['userdata']['smName']."','狀態由".($or->statusArr[$orOldData[0]["orStatus"]])."變成待補','".date('Y-m-d H:i:s',time())."')";
			$or->getSql($Record);
		}
		$push = push("happy.pem",'happy',$deviceTokenArr,$msgArr,$otherInfoArr);
		echo "更新成功";
		// print_r($otherInfoArr);
		
	}else{
		echo $errMsg;
	}
	
	

?>
