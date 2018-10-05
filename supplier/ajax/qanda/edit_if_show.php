<?php

	header (" content-type: text/HTML; charset=utf-8 ");
	require_once('../../model/require_login.php');
	
	$qa = new Que_And_Ans();
	
	$result = "";
	
	foreach($_POST as $key=>$value){
		$$key = $value;
	}
	
	$qa->updateIfShow($qaIfShow, $qaNo);
	
	$result="更新成功！";
	
	echo $result;

?>