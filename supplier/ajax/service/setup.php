<?php
	header ("content-type: text/HTML; charset=utf-8 ");
	require_once('../../model/require_login.php');
	
	foreach($_POST as $key=>$value){
		$$key = $value;
	}
	
	//錯誤訊息
	$errMsg = "";
	
	
	if($supPwd == ""){
		$errMsg = "必須填入新密碼";
	}
	else
    {
	    $sup = new Supplier();
        $sup->updatePwd($supNo , $supPwd) ;
	}		
	echo $errMsg;

?>
