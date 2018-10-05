<?php
	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../cls/WADB.cls.php');
	require_once('../../cfg/cfg.inc.php');
	require_once('../../cls/System_Manager.cls.php');
	$sm = new System_Manager();
	
	//原始資料
	$origData = $sm->getOneSMByNo($_POST["smNo"]);
	
	//若空白就輸入原始資料
// 	foreach($_POST as $key=>$value){
// 		if(trim($value) == ""){
// 			$_POST[$key] = $origData[0][$key];
// 		}
// 	}
	
	//錯誤訊息
	$errMsg = array();
	
	//成功訊息
	$success="";
	
	
	//如果沒有錯誤訊息
	if(empty(array_filter($errMsg))){
		$update = $sm->update($_POST, $_POST["smNo"]);
		$success="更新成功";
	}
	
	$result = array("success"=>$success,"errMsg"=>$errMsg);
	echo json_encode($result,true);

?>
