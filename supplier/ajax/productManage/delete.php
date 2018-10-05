<?php
	require_once('../../model/require_login.php');
	
	$pm = new Product_Manage();
	$pmNo = $_POST["pmNo"];
	
	$origPmData = $pm->getOnePMByNo($pmNo);
	
	if($origPmData[0]["pmMainSup"] == 1){
		echo "不可以刪除主要供應商，請先更換主要供應商。";
	}else{
		$delete = $pm->delete($pmNo);
		if($delete){
			echo "刪除成功";
		}else{
			echo "刪除失敗";
		}
	}
?>