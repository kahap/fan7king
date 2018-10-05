<?php
	require_once('../../model/require_login.php');
	
	$qaa = new Qa_App();
	$qaaNo = $_POST["qaaNo"];
	
	$delete = $qaa->delete($qaaNo);
	if($delete){
		echo "刪除成功";
	}else{
		echo "刪除失敗";
	}
?>