<?php

if(isset($_POST["os"])){
	if(isset($_POST["version"])){
		switch($_POST["os"]){
			case "android":
				$array["androidVersion"] = $_POST["version"];
				break;
			case "ios":
				$array["iosVersion"] = $_POST["version"];
				break;
		}
	}else{
		$errMsg = "請帶入最新版本參數。";
	}
}else{
	$errMsg = "請帶入作業系統參數。";
}

//若沒錯誤的話預設其他欄位
if(!isset($errMsg)){
	$api->updateAll($array);
	$api->setInformation(true, 1, 1, "修改成功。");
}else{
	$api->setInformation(false, 0, 0, $errMsg);
}
$api->setResult();


?>