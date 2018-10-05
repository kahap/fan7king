<?php
	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	$cat = new Category();
	
	$errMsg = array();
	$success = "";
	
	//原始資料
	$origData = $cat->getOneCatByNo($_POST["catNo"]);
	
	//若空白就輸入原始資料
// 	foreach($_POST as $key=>$value){
// 		if(trim($value) == ""){
// 			$_POST[$key] = $origData[0][$key];
// 		}
// 	}
	
	//圖片上傳
	$imgErr = uploadImg("edit", $origData[0]["catImage"], true, "catImg", 'catImage');
	if($imgErr != ""){
		$errMsg["catImageErr"] = $imgErr;
	}
	$iconErr = uploadImg("edit", $origData[0]["catIcon"], true, "catIcon", 'catIcon');
	if($iconErr != ""){
		$errMsg["catIconErr"] = $iconErr;
	}
	
	//結果輸出
	if(empty(array_filter($errMsg))){
		$update = $cat->update($_POST, $_POST["catNo"]);
		$success = "更新成功";
	}
	
	$result = array("success"=>$success,"errMsg"=>$errMsg);
	echo json_encode($result);
	
	

?>
