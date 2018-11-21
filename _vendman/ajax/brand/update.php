<?php
	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	$bra = new Brand();
	
// 	//原始資料
// 	$origData = $bra->getOneBrandByNo($_POST["braNo"]);
	
// 	//若空白就輸入原始資料
// 	foreach($_POST as $key=>$value){
// 		if(trim($value) == ""){
// 			$_POST[$key] = $origData[0][$key];
// 		}
// 	}
	
	if(isset($_POST["braIfDisplay"])){
		$update = $bra->updateDisplay($_POST["braIfDisplay"], $_POST["braNo"]);
		if($update){
			echo "更新成功！";
		}else{
			echo "更新失敗！";
		}
	}else{
		$update = $bra->update($_POST, $_POST["braNo"]);
		if($update){
			echo "更新成功！";
		}else{
			echo "更新失敗！";
		}
	}
	
	

?>
