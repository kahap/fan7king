<?php
	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	$cat = new Category();
	
	$update = $cat->updateDisplay($_POST["catIfDisplay"], $_POST["catNo"]);
	if($update){
		echo "更新成功！";
	}else{
		echo "更新失敗！";
	}
	
	

?>
