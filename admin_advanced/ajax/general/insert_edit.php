<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

session_start();

$api = new API($_POST["table"]);

$inputData = array();

//客製區
switch($_POST["table"]){
	case "admin_advanced_roles":
		$mustFill = array("aarName","aafID");
		$insertColumns = array("aarName","aafID");
		break;
	case "note_person":
		$mustFill = array("npName");
		$insertColumns = array("npName","npIfActivate");
		break;
	case "admin_advanced_user":
		$mustFill = array( 
// 		"aarNo",
		"aauAccount","aauPwd","aauName");
		$insertColumns = array("aarNo","aauAccount","aauPwd","aauName","aauEmail","aauCell","aauExt","aauStartDay","aauLeaveDay");
}

//檢查必填
if(!empty($mustFill)){
	foreach($mustFill as $key=>$value){
		if(!isset($_POST[$value]) || $_POST[$value] == ""){
			$errMsg[$value] = "該欄位為必填。";
		}
	}
}
if(!isset($errMsg)){
	//必要轉換客製區
	switch($_POST["table"]){
		case "admin_advanced_roles":
			$_POST["aafID"] = json_encode($_POST["aafID"],true);
			break;
		case "admin_advanced_user":
			if(isset($_POST["aarNo"] )){
				$_POST["aarNo"] = json_encode($_POST["aarNo"]);
			}else{
				$_POST["aarNo"] ="[]";
			}
			$aauNo = $_SESSION["adminUserData"]["aauNo"];
			if($_POST["action"] == "edit"){
				$api->update(array("aauNoUpdate"=>$aauNo),$_POST["no"]);
			}else{
				$api->update(array("aauNoUpdate"=>$aauNo,"aauNoCreate"=>$aauNo),$_POST["no"]);
			}
			break;
	}
	//篩選資料
	foreach($_POST as $key=>$value){
		if(in_array($key,$insertColumns)){
			$inputData[$key] = $value;
		}
	}
}

if(!isset($errMsg)){
	if($_POST["action"] == "edit"){
		$api->update($inputData,$_POST["no"]);
	}else{
		$api->insert($inputData);
	}
	echo "OK";
}else{
	echo json_encode($errMsg,JSON_UNESCAPED_UNICODE);
}

?>