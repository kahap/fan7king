<?php
	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	$cat = new Category();
	
	//錯誤訊息
	$errMsg = array();
	//成功訊息
	$success="";
	
	//最後一筆
	$catData = $cat->getAllCatDesc();
	$catNo = ++$catData[0]["catNo"];
	
	foreach($_POST as $key=>$value){
		$$key = $value;
	}
	
	
	//驗證
	if($catName == ""){
		$errMsg["catNameErr"] = "分類名稱不可為空白。";
	}
	
	//圖片上傳
	$imgErr = uploadImg("insert", "", true, "catImg", 'catImage');
	if($imgErr != ""){
		$errMsg["catImageErr"] = $imgErr;
	}
	$iconErr = uploadImg("insert", "", true, "catIcon", 'catIcon');
	if($iconErr != ""){
		$errMsg["catIconErr"] = $iconErr;
	}
	
	//結果輸出
	if(empty(array_filter($errMsg))){
		$insert = $cat->insert($_POST,$catNo);
		$success = "新增成功";
	}
	
	$result = array("success"=>$success,"errMsg"=>$errMsg);
	echo json_encode($result);
?>
