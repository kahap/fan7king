<?php
	header (" content-type: text/HTML; charset=utf-8 ");
	require_once('../../model/require_login.php');
	
	$qaa = new Qa_App();
	
	//原始資料
	$origData = $qaa->getOne($_POST["qaaNo"]);
	
	
	//錯誤訊息
	$errMsg = array();
	//成功訊息
	$success="";
	
	
	//如果沒有錯誤訊息
	if(empty(array_filter($errMsg))){
		$update = $qaa->update($_POST, $_POST["qaaNo"]);
		$success="更新成功";
	}
	
	$result = array("success"=>$success,"errMsg"=>$errMsg);
	echo json_encode($result);

?>
