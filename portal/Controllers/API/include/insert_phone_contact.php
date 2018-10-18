<?php
$memNo = getMemberNo();
$lastArr = $_POST["pcLastName"];
$firstArr = $_POST["pcFirstName"];
$cellArr = $_POST["pcCell"];

foreach($lastArr as $key=>$value){
	$array["pcLastName"] = $value;
	$array["pcFirstName"] = $firstArr[$key];
	$array["pcCell"] = $cellArr[$key];
	$array["memNo"] = $memNo;
	$api->insert($array);
}
$api->setInformation(true, 200, count($lastArr), "Successfully inserted");
$api->setResult();
?>