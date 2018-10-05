<?php
	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	$ad = new Advertise();
	
	//錯誤訊息
	$errMsg = array();
	//成功訊息
	$success="";
	
	
	foreach($_POST as $key=>$value){
		$$key = $value;
	}
	
	
	//圖片上傳
	$imgErr = uploadImg("insert", "", false, "indexImg", 'adImage');
	if($imgErr != ""){
		$errMsg["adImageErr"] = $imgErr;
	}
	
	//結果輸出
	if(empty(array_filter($errMsg))){
		$insert = $ad->insert($_POST);
		$success = "新增成功";
	}
	
	$result = array("success"=>$success,"errMsg"=>$errMsg);
	echo json_encode($result);
?>
