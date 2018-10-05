<?php
//一般註冊
$mem = new Member();

$mustFill = array("memAccount","memPwd","memDeviceToken","gpsLong","gpsLat");

foreach($mustFill as $key=>$value){
	if(!isset($_POST[$value])){
		$errMsg[] = "Missing: ".$value;
	}
}

if(!isset($errMsg)){
	
	if($_POST["memAccount"] != ""){
		//$memData = $mem->getMemberinformation($_POST);
		$memData = $mem->getMemberinformationNewApp($_POST);
	}else{
		$memData = null;
	}
	
	if($memData != null){
		if($memData["memAllowLogin"] == "1"){
			$token = md5(microtime());
			$memNo = $memData["memNo"];
			$device = $_POST["memDeviceToken"];
			$type = $_POST["adDeviceType"];
			$loginArr = array(
				"adTokenId"=>$token,
				"memNo"=>$memNo,
				"adDeviceId"=>$device,
				"adDeviceType"=>$type
			);
			$forGps = new API("gps");
			$gpsArr = array("gpsLong"=> $_POST["gpsLong"],"gpsLat"=> $_POST["gpsLat"]);
			$gpsArr["memNo"] = $memNo;
			$forGps->insert($gpsArr);
			
			$api->insert($loginArr);	
			$api->setInformation($loginArr, 1, 1, "登入成功！");
		}else{
			$api->setInformation(false, 0, 0, "您的帳戶已被停權，請洽客服人員。");
		}
	}else{
		$api->setInformation(false, 0, 0, "錯誤的帳號密碼，或已經有FB註冊");
	}
}else{
	$api->setInformation(false, 0, 0, $errMsg);
}
$api->setResult(false);



?>