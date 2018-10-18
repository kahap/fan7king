<?php
$memNo = getMemberNo();
$lastArr = $_POST["ptPhoneNum"];
$firstArr = $_POST["ptTime"];
$cellArr = $_POST["ptContent"];

foreach($lastArr as $key=>$value){
	$array["ptPhoneNum"] = $value;
	$array["ptTime"] = $firstArr[$key];
	$array["ptContent"] = $cellArr[$key];
	$array["memNo"] = $memNo;
	$api->insert($array);
}
$api->setInformation(true, 200, count($lastArr), "Successfully inserted");
$api->setResult();
?>