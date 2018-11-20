<?php
	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	$bra = new Brand();
	
	//錯誤訊息
	$errMsg = array();
	//成功訊息
	$success="";
	
	//最後一筆
	$braData = $bra->getAllBrandDesc();
	if($braData[0]["braNo"]<9){
		$braNo = "0".++$braData[0]["braNo"];
	}else{
		$braNo = ++$braData[0]["braNo"];
	}
	
	foreach($_POST as $key=>$value){
		$$key = $value;
	}
	
	//驗證
	if($braName == ""){
		$errMsg["braNameErr"] = "品牌名稱不可為空白。";
	}
	
	//結果輸出
	if(empty(array_filter($errMsg))){
		$insert = $bra->insert($_POST,$braNo);
		$success = "新增成功";
	}
	
	$result = array("success"=>$success,"errMsg"=>$errMsg);
	echo json_encode($result);
?>
