<?php
	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	$pm = new Product_Manage();
	
	//原始資料
	$origData = $pm->getOnePMByNo($_POST["pmNo"]);
	
	//若空白就輸入原始資料
// 	foreach($_POST as $key=>$value){
// 		if(trim($value) == ""){
// 			$_POST[$key] = $origData[0][$key];
// 		}
// 	}
	
	$update = $pm->updateSupPrice($_POST["pmSupPrice"], $_POST["pmNo"]);
	
	if($update){
		echo "更新成功！ ".$_POST["pmSupPrice"];
	}else{
		echo "更新失敗！";
	}

?>
