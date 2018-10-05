<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

session_start();
date_default_timezone_set('Asia/Taipei');

$uu = new API("urge_user");
$up = new API("urge_period");
$aau = new API("admin_advanced_user");

$allUuData = $uu->getAll();
$aauNoArr = array();

foreach($_POST as $key=>$value){
	$$key = $value;
}

if($allUuData != null){
	foreach($allUuData as $key=>$value){
		$aauNoArr[$value["upNo"]][] = $value["aauNo"];
	}
}
if(array_key_exists($upNo, $aauNoArr) && in_array($aauNo,$aauNoArr[$upNo])){
	$errMsg = "此使用者已經是該逾期範圍的催收人員。";
}


if(!isset($errMsg)){
	if(isset($uuNo)){
		$uu->update(array("aauNo"=>$aauNo,"upNo"=>$upNo), $uuNo);
	}else{
		$uu->insert(array("aauNo"=>$aauNo,"upNo"=>$upNo));
	}
	echo "OK";
}else{
	echo $errMsg;
}


?>