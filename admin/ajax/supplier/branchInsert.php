<?php
	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../model/require_login.php');
	
	$sup = new SupplierBranch();
	
	//若空白就輸入原始資料
// 	foreach($_POST as $key=>$value){
// 		if(trim($value) == "" && $key != "supEmail"){
// 			$_POST[$key] = $origSupData[0][$key];
// 		}
// 	}
	
	//錯誤訊息
	$errMsg = "";
	//成功訊息
	$success="";
	
	foreach ($_POST as $key => $value) {
		if(strlen($value)<1){
			$errMsg = "請填滿所有欄位!";
		}
	}
	if(!isset($_POST['aauNo'])){
			$errMsg = "請填滿所有欄位!";
	}
	
	//如果沒有錯誤訊息
	if($errMsg==""){
		$update = $sup->insert($_POST, $_POST["supno"]);
		$success="新增成功";
	}
	
	$result = array("success"=>$success,"errMsg"=>$errMsg);
	echo json_encode($result,true);

?>
