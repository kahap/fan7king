<?php
	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	$lg = new Loyal_Guest();
	
	//錯誤訊息
	$errMsg = array();
	//成功訊息
	$success="";
	
	
	foreach($_POST as $key=>$value){
		$$key = $value;
	}
	
	//驗證
	if($lgIdNum == ""){
		$errMsg["lgIdNumErr"] = "不可為空白。";
	}else{
		if(strlen($lgIdNum) != 10){
			$errMsg["lgIdNumErr"] = "有效的身分證為10碼。";
		}else{
			if(!preg_match("/[A-Z]{1}[0-9]{9}/", $lgIdNum)){
				$errMsg["lgIdNumErr"] = "身分證格式錯誤。";
			}
		}
	}
	
	//結果輸出
	if(empty(array_filter($errMsg))){
		$insert = $lg->insert($lgIdNum);
		$success = "新增成功";
	}
	
	$result = array("success"=>$success,"errMsg"=>$errMsg);
	echo json_encode($result);
?>
