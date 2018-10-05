<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

$pur = new API("pay_upload_records");
$aau = new API("admin_advanced_user");

$purData = $pur->getUploadHistory($_POST["which"]);

if($purData != null){
	foreach($purData as $key=>&$value){
		foreach($value as $kIn=>&$vIn){
			if($kIn == "purType"){
				$vIn = '<a data-no="'.$value["purNo"].'" class="delete-pay waves-effect waves-light btn red m-b-xs">刪除</a>';
			}else if($kIn == "aauNo"){
				$aauData = $aau->getOne($vIn);
				$vIn = $aauData[0]["aauName"];
			}else if($kIn == "prNo"){
				unset($value[$kIn]);
			}
		}
	}
}

echo json_encode($purData,JSON_UNESCAPED_UNICODE);

?>