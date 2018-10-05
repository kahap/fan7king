<?php
	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../model/require_login.php');
	
	$ag = new Admin_Group();
	
	$arr = array();
	
	if(isset($_POST["agRight"])){
		foreach($_POST["agRight"] as $key=>$value){
			array_push($arr, $value);
		}
		$_POST["agRight"] = json_encode($arr);
	}else{
		$_POST["agRight"] = "";
	}
	
	//錯誤訊息
	$errMsg = array();
	
	//成功訊息
	$success="";
	
	if(trim($_POST["agName"]) == ""){
		$errMsg["agNameErr"] = "請輸入群組名稱";
	}
	
	
	//如果沒有錯誤訊息
	if(empty(array_filter($errMsg))){
		$insert = $ag->insert($_POST);
		$success="新增成功";
	}
	
	$result = array("success"=>$success,"errMsg"=>$errMsg);
	echo json_encode($result,true);

?>
