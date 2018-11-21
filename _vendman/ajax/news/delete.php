<?php
	require_once('../../model/require_login.php');
	
	$news = new News();
	$newsNo = $_POST["newsNo"];
	
	$delete = $news->delete($newsNo);
	if($delete){
		echo "刪除成功";
	}else{
		echo "刪除失敗";
	}
?>