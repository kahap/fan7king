<?php
$mustFill = array("adTokenId","gpsLat","gpsLong");

foreach($mustFill as $key=>$value){
	if(!isset($_POST[$value]) || $_POST[$value] == ""){
		$errMsg[] = "Missing ".$value.".";
	}
}

if(!isset($errMsg)){
	$memNo = getMemberNo();
	
	$array = array(
		"memNo"=>$memNo,
		"gpsLat"=>$_POST["gpsLat"],
		"gpsLong"=>$_POST["gpsLong"]
	);

	$api->insert($array);

	$api->setInformation(true, 1, 1, "成功新增");
}else{
	$api->setInformation(false, 0, 1, $errMsg);
}
$api->setResult();
?>