<?php
	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../model/require_login.php');
	
	$sup = new Supplier();
	
	//原始資料
	$origSupData = $sup->getOneSupplierByNo($_POST["supNo"]);
	
	//若空白就輸入原始資料
// 	foreach($_POST as $key=>$value){
// 		if(trim($value) == "" && $key != "supEmail"){
// 			$_POST[$key] = $origSupData[0][$key];
// 		}
// 	}
	
	//錯誤訊息
	$errMsg = array();
	//成功訊息
	$success="";
	
	//圖片上傳
	$err = uploadImg("edit", $origSupData[0]["supStampImg"], true, "supplier-stamp", 'supStampImg');
	if($err != ""){
		$errMsg["supStampImgErr"] = $err;
	}
	
	//如果沒有錯誤訊息
	if(empty(array_filter($errMsg))){
		$update = $sup->update($_POST, $_POST["supNo"]);
		$success="更新成功";
	}
	
	$result = array("success"=>$success,"errMsg"=>$errMsg);
	echo json_encode($result,true);

?>
