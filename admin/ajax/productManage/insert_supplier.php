<?php
	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../model/require_login.php');
	
	$pm = new Product_Manage();
	$proNo = $_POST["proNo"];
	$pmData = $pm->getAllByProNameAndGroup($proNo);
	
	$supNo = "";
	
	foreach($_POST as $key=>$value){
		$pmData[0][$key] = $value;
	}
	
	unset($pmData[0]["pmNo"]);
	$pmData[0]["pmMainSup"] = 0;
	
	//錯誤訊息
	$errMsg = array();
	//成功訊息
	$success="";
	
	if(!isset($_POST["supNo"]) || $_POST["supNo"] == "" || $_POST["supNo"] == "請選擇"){
		$errMsg["supNoErr"] = "請選擇所屬廠商。";
	}
	
	//如果沒有錯誤訊息
	if(empty(array_filter($errMsg))){
		$update = $pm->insert($pmData[0]);
		$success="新增成功";
	}
	
	$result = array("success"=>$success,"errMsg"=>$errMsg);
	echo json_encode($result,true);

?>
