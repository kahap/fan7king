<?php
	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../model/require_login.php');
	
	$qa = new Que_And_Ans();
	
	//錯誤訊息
	$errMsg = array();
	//成功訊息
	$success="";
	
	//至少輸入廠商名稱
	if($_POST["qaQues"] == ""){
		$errMsg["qaQuesErr"] = "問題為必填";
	}
		
	
	//如果沒有錯誤訊息
	if(empty(array_filter($errMsg))){
		$insert = $qa->insert($_POST);
		$success="新增成功";
	}
	
	$result = array("success"=>$success,"errMsg"=>$errMsg);
	echo json_encode($result,true);

?>
