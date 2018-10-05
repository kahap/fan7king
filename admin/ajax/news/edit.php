<?php
	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../model/require_login.php');
	
	$news = new News();
	
	//原始資料
	$origData = $news->getOneNewsByNo($_POST["newsNo"]);
	
// 	//若空白就輸入原始資料
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
		$update = $news->update($_POST, $_POST["newsNo"]);
		$success="更新成功";
	}
	
	$result = array("success"=>$success,"errMsg"=>$errMsg);
	echo json_encode($result);

?>
