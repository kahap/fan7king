<?php
//一般註冊
$mem = new Member();

$mustFill = array("memFBtoken");

foreach($_POST as $key=>$value){
	$$key = $value;
}

if(!isset($memFBtoken)){
	$errMsg = "抓不到FB ID";
}

if(!isset($errMsg)){
	$memData = $mem->check_FBtoken($memFBtoken);
	if(empty($memData)){
		$forMem = new API("member");
		$forGps = new API("gps");
		//確認會員初始的所需欄位
		$requiredData = array("memFBtoken","memName","memSubEmail","memGender","memDeviceToken");
		//補足其他預設欄位
		$_POST["memAllowLogin"] = "1";
		$_POST["memRegistMethod"] = "0";
		$_POST["memEmailAuthen"] = "0";
		$_POST["memRegistDate"] = date("Y-m-d H:i:s",time());
		$_POST["pass_number"] = Pass();

		foreach($requiredData as $key=>$value){
			if(!isset($_POST[$value])){
				$errMsg[] .= "Missing ".$value;
			}
		}

		if(!isset($errMsg)){
			$gpsArr = array("gpsLong"=>$gpsLong,"gpsLat"=>$gpsLat);
			$type = $_POST["adDeviceType"];
			//移除經緯度
			unset($_POST["gpsLong"]);
			unset($_POST["gpsLat"]);
			unset($_POST["adDeviceType"]);
			$forMem->insert($_POST);
			$memNo = $forMem->db->getIdForInsertedRecord();

			$gpsArr["memNo"] = $memNo;
			$forGps->insert($gpsArr);
			$token = md5(microtime());
			$device = $_POST["memDeviceToken"];
			$loginArr = array(
					"adTokenId"=>$token,
					"memNo"=>$memNo,
					"adDeviceId"=>$device,
					"adDeviceType"=>$type
			);
			$api->insert($loginArr);

            $loginArr = array(
					"adTokenId"=>$token,
					"memNo"=>$memNo,
                    "memClass"=>"",
                    "memIdNum"=>"",
					"adDeviceId"=>$device,
					"adDeviceType"=>$type
			);
			$api->setInformation($loginArr, 1, 1, "登入成功！");
		}else{
			$api->setInformation(false, 0, 0, $errMsg);
		}
	}else{
		if($memData["memAllowLogin"] == "1"){
			$token = md5(microtime());
			$memNo = $memData["memNo"];
            $memClass = $memData["memClass"];
            $memIdNum = $memData["memIdNum"];
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

            $loginArr = array(
				"adTokenId"=>$token,
				"memNo"=>$memNo,
                "memClass"=>$memClass,
                "memIdNum"=>$memIdNum,
				"adDeviceId"=>$device,
				"adDeviceType"=>$type
			);
			$api->setInformation($loginArr, 1, 1, "登入成功！");
		}else{
			$api->setInformation(false, 0, 0, "您的帳戶已被停權，請洽客服人員。");
		}
	}
}else{
	$api->setInformation(false, 0, 0, $errMsg);
}
$api->setResult();

function Pass($i=8) {
	srand((double)microtime()*1000000);
	return strtoupper(substr(md5(uniqid(rand())),rand(0,32-$i),$i));
}

?>