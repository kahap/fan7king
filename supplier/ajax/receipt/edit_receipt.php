<?php
	header (" content-type: text/HTML; charset=utf-8 ");
	require_once('../../model/require_login.php');
	
	$ts = new Other_Setting();
	$origData = $ts->getAll();
	
	$errMsg = "";

	if($_POST["receiptDays"] == ""){
		$errMsg = "不可為空白";
	}else{
		if(!is_numeric($_POST["receiptDays"])){
			$errMsg = "必須為數字";
		}
	}
	
	$ts->updateReceiptDays($_POST["receiptDays"]);
	
	if($errMsg != ""){
		echo $errMsg;
	}else{
		echo "更新成功";
	}

?>
