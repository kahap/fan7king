<?php
	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	$lg = new Loyal_Guest();
	
// 	//原始資料
// 	$origData = $lg->getOneRBAByNo($_POST["lgNo"]);
	
// 	//若空白就輸入原始資料
// 	foreach($_POST as $key=>$value){
// 		if(trim($value) == ""){
// 			$_POST[$key] = $origData[0][$key];
// 		}
// 	}
	
	$update = $lg->update($_POST, $_POST["lgNo"]);
	
	if($update){
		echo "更新成功！ ".$_POST["lgIdNum"];
	}else{
		echo "更新失敗！";
	}

?>
