<?php
	header (" content-type: text/HTML; charset=utf-8 ");
	require_once('../../model/require_login.php');
	
	$sm = new System_Manager();
	
	//錯誤訊息
	$errMsg = array();
	
	foreach($_POST as $key=>$value){
		$$key = $value;
	}
	
	if(!isset($smName) || trim($smName) == ""){
		$errMsg["smNameErr"] = "請輸入管理員姓名";
	}
	
	if(!isset($smAccount) || trim($smAccount) == ""){
		$errMsg["smAccountErr"] = "請輸入管理員帳號";
	}
	
	if(!isset($smPwd) || trim($smPwd) == ""){
		$errMsg["smPwdErr"] = "請輸入管理員密碼";
	}else{
		if (!ctype_alnum($smPwd)){
			$errMsg["smPwdErr"] = "密碼只可以為英文+數字";
		}
	}
	
	//成功訊息
	$success="";
	
	//如果沒有錯誤訊息
	if(empty(array_filter($errMsg))){
		$insert = $sm->insert($_POST);
		$success="新增成功";
	}
	
	$result = array("success"=>$success,"errMsg"=>$errMsg);
	echo json_encode($result);

?>
