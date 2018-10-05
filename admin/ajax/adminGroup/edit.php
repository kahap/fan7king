<?php
	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../model/require_login.php');
	
	$ag = new Admin_Group();
	
	//原始資料
	$origData = $ag->getOneAGByNo($_POST["agNo"]);
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
	
	
	//如果沒有錯誤訊息
	if(empty(array_filter($errMsg))){
		$update = $ag->update($_POST, $_POST["agNo"]);
		$success="更新成功";
	}
	
	$result = array("success"=>$success,"errMsg"=>$errMsg);
	echo json_encode($result,true);

?>
