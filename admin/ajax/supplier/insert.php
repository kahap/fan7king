<?php
	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../model/require_login.php');
	
	$sup = new Supplier();
	
	//錯誤訊息
	$errMsg = array();
	//成功訊息
	$success="";
	
	//至少輸入廠商名稱
	if($_POST["supName"] == ""){
		$errMsg["supNameErr"] = "廠商名稱為必填";
	}
	
	//圖片上傳
	$err = uploadImg("insert", "", true, "supplier-stamp", 'supStampImg');
	if($err != ""){
		$errMsg["supStampImgErr"] = $err;
	}
	
	
	//如果沒有錯誤訊息
	if(empty(array_filter($errMsg))){
		$update = $sup->insert($_POST);
		$success="新增成功";
	}
	
	$result = array("success"=>$success,"errMsg"=>$errMsg);
	echo json_encode($result,true);

?>
