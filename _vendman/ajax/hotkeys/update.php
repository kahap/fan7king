<?php
	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	$hk = new Hotkeys();
	
	
	$update = $hk->updateEnable($_POST["hkEnable"], $_POST["hkNo"]);
	
	if($update){
		echo "更新成功！";
	}else{
		echo "更新失敗！";
	}

?>
