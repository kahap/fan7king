<?php
	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	$pm = new Product_Manage();
	
	
	foreach($_POST as $key=>$value){
		$$key = $value;
	}
	
	$update = $pm->updateStatus($pmStatus, $pmNo);
	
	if($update){
		echo "更新成功！";
	}else{
		echo "更新失敗！";
	}

?>
