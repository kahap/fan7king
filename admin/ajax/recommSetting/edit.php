<?php
	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	$rs = new Recomm_Setting();
	
	$errMsg = array();
	$success = "";
	
	//原始資料
	$origData = $rs->getSetting();
	
	//若空白就輸入原始資料
	foreach($_POST as $key=>$value){
// 		if(trim($value) == ""){
// 			$_POST[$key] = $origData[0][$key];
// 		}
		$$key = $value;
	}
	
	if(!is_numeric($rsTotalPerOrder)){
		$errMsg["rsTotalPerOrderErr"] = "只能填入數字";
	}
	if(!is_numeric($rsDaysLimit)){
		$errMsg["rsDaysLimitErr"] = "只能填入數字";
	}
	if(!is_numeric($rsMinimum)){
		$errMsg["rsMinimumErr"] = "只能填入數字";
	}
	if(!is_numeric($rsCharge)){
		$errMsg["rsChargeErr"] = "只能填入數字";
	}
	
	//結果輸出
	if(empty(array_filter($errMsg))){
		$update = $rs->update($_POST);
		$success = "更新成功";
	}
	
	$result = array("success"=>$success,"errMsg"=>$errMsg);
	echo json_encode($result,true);
	
	

?>
