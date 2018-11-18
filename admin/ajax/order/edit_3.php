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
	
	date_default_timezone_set('Asia/Taipei');
	$date = date('Y-m-d', time());
	
	
	if($errMsg == ""){
		$deviceTokenArr = array();
		$androidRigistIdArr = array();
		$androidDataArr = array();
		$msgArr = array();
		$otherInfoArr = array();
		$count = 0;
		foreach($orNoArr as $key=>$value){
			$or->updateStatus(8, $value);
			$or->updateStatusTime(8, $value);
			$or->updateIfProcess(0, $value);
			$or->updateProcessTime("", $value);
			//設定訂貨日期
			$or->updateOrderFromSupDate($date, $value);
			
			//寄送EMAIL通知
			$email = new Email();
			$orOldData = $or->getOneOrderByNo($value);
			$memData = $mem->getOneMemberByNo($orOldData[0]["memNo"]);
			$pmData = $pm->getOnePMByNo($orOldData[0]["pmNo"]);
			$proData = $pro->getOneProByNo($pmData[0]["proNo"]);
			
			//推播
			$memNo = $orOldData[0]["memNo"];
			$api = new API("app_data");
			$api->getUniqueDeviceToken($memNo);
			$data = $api->getData();
			if($data != null){
				$msgArr[$count] = "您訂購的商品 - ".$proData[0]["proName"]."，備貨中";
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
			
			sendEmailForStatChange(8,$orOldData,$memData,$pmData,$proData,$email);
			$sql = "select rcNo from real_cases where rcRelateDataNo = '".$value."' && rcType = '0' ";
			$data = $or->getSql($sql);
			$Record = "insert into service_record (rcNo,aauNoService,content,time) value('".$data['0']['rcNo']."','".$_SESSION['userdata']['smName']."','狀態由核准變成".$or->statusArr[$orOldData[0]["orStatus"]]."','".date('Y-m-d H:i:s',time())."')";
			$or->getSql($Record);	
		}
		// $push = push("happy.pem",'happy',$deviceTokenArr,$msgArr,$otherInfoArr);
		echo "更新成功";
	}else{
		echo $errMsg;
	}
	
	

?>
