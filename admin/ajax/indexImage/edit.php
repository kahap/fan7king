<?php
	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	$ad = new Advertise();
	
	$errMsg = array();
	$success = "";
	
	//原始資料
	$origData = $ad->getOne($_POST["adNo"]);
	
	//若空白就輸入原始資料
// 	foreach($_POST as $key=>$value){
// 		if(trim($value) == ""){
// 			$_POST[$key] = $origData[0][$key];
// 		}
// 	}
	
	//圖片上傳
	$imgErr = uploadImg("edit", $origData[0]["adImage"], true, "indexImg", 'adImage');
	if($imgErr != ""){
		$errMsg["adImageErr"] = $imgErr;
	}
	
	//結果輸出
	if(empty(array_filter($errMsg))){
		$update = $ad->update($_POST, $_POST["adNo"]);
		$success = "更新成功";
	}
	
	$result = array("success"=>$success,"errMsg"=>$errMsg);
	echo json_encode($result,true);
	
	

?>
