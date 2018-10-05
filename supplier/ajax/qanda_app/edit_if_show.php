<?php

	header (" content-type: text/HTML; charset=utf-8 ");
	require_once('../../model/require_login.php');
	
	$qaa = new Qa_App();
	
	$result = "";
	
	foreach($_POST as $key=>$value){
		$$key = $value;
	}
	
	$qaa->updateIfShow($qaaIfShow, $qaaNo);
	
	$result="更新成功！";
	
	echo $result;

?>