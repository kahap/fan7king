<?php
	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	$hk = new Hotkeys();
	
	//錯誤訊息
	$errMsg = array();
	//成功訊息
	$success="";
	
	
	foreach($_POST as $key=>$value){
		$$key = $value;
	}
	
	//驗證
	if($hkKey == ""){
		$errMsg["hkKeyErr"] = "不可為空白。";
	}
	
	//結果輸出
	if(empty(array_filter($errMsg))){
		$insert = $hk->insert($_POST);
		$success = "新增成功";
	}
	
	$result = array("success"=>$success,"errMsg"=>$errMsg);
	echo json_encode($result);
?>
