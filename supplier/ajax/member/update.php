<?php
	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	$member = new Member();
	$lg = new Loyal_Guest();
	
	
	//原始資料
	$origMemData = $member->getOneMemberByNo($_POST["memNo"]);
	
	//若空白就輸入原始資料
	$member->changeToData($_POST);
// 	foreach($_POST as $key=>$value){
// 		if(trim($value) == ""){
// 			$_POST[$key] = $origMemData[0][$key];
// 		}
// 	}
	
	//老客戶編輯
	if($_POST["ifLoyal"] == 0){
		$lg->deleteByIdNum($_POST["memIdNum"]);
	}else{
		$lg->insert($_POST["memIdNum"]);
	}
	
	$update = $member->updateMember($_POST, $_POST["memNo"]);
	
	if($update){
		echo "更新成功！";
	}else{
		echo "更新失敗！";
	}

?>
