<?php
	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../model/require_login.php');
	
	$qaa = new Qa_App();
	
	//錯誤訊息
	$errMsg = array();
	//成功訊息
	$success="";
	
	//至少輸入廠商名稱
	if($_POST["qaaQues"] == ""){
		$errMsg["qaaQuesErr"] = "問題為必填";
	}
		
	
	//如果沒有錯誤訊息
	if(empty(array_filter($errMsg))){
		$insert = $qaa->insert($_POST);
		$success="新增成功";
	}
	
	$result = array("success"=>$success,"errMsg"=>$errMsg);
	echo json_encode($result,true);

?>
