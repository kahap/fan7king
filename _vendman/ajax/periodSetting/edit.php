<?php
	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	$ps = new Period_Setting();
	
	//原始資料
	$origData = $ps->getOnePSByNo($_POST["psNo"]);
	
	$errMsg = array();
	$success = "";
	
	//若空白就輸入原始資料
	foreach($_POST as $key=>$value){
// 		if(trim($value) == ""){
// 			$_POST[$key] = $origData[0][$key];
// 		}
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
		$update = $ps->update($_POST,$_POST["psNo"]);
		$success = "更新成功";
	}
	
	$result = array("success"=>$success,"errMsg"=>$errMsg);
	echo json_encode($result);
	
	

?>
