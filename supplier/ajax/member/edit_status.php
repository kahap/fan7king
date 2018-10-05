<?php

	header (" content-type: text/HTML; charset=utf-8 ");
	require_once('../../model/require_login.php');
	
	$mem = new Member();
	
	$result = "";
	
	foreach($_POST as $key=>$value){
		$$key = $value;
	}
	
	$mem->changeMemberLoginable($memAllowLogin, $memNo);
	
	$result="更新成功！";
	
	echo $result;

?>