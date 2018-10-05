<?php
	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	$ps = new Period_Setting2();
	
	//錯誤訊息
	$errMsg = array();
	//成功訊息
	$success="";
	
	
	foreach($_POST as $key=>$value){
		$$key = $value;
	}
	
	if(!is_numeric($psMonthNum)){
		$errMsg["psMonthNumErr"] = "只能填入數字";
	}
	if(!is_numeric($psRatio)){
		$errMsg["psRatioErr"] = "只能填入數字";
	}
	if(!is_numeric($psOrder)){
		$errMsg["psOrderErr"] = "只能填入數字";
	}
	
	//結果輸出
	if(empty(array_filter($errMsg))){
		$insert = $ps->insert($_POST);
		$success = "新增成功";
	}
	
	$result = array("success"=>$success,"errMsg"=>$errMsg);
	echo json_encode($result);
?>
