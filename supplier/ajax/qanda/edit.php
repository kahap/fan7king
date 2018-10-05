<?php
	header (" content-type: text/HTML; charset=utf-8 ");
	require_once('../../model/require_login.php');
	
	$qa = new Que_And_Ans();
	
	//原始資料
	$origData = $qa->getOneQAByNo($_POST["qaNo"]);
	
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
		$update = $qa->update($_POST, $_POST["qaNo"]);
		$success="更新成功";
	}
	
	$result = array("success"=>$success,"errMsg"=>$errMsg);
	echo json_encode($result);

?>
