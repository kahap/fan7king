<?php
	header ("content-type: text/HTML; charset=utf-8 ");
	require_once('../../model/require_login.php');
	
	foreach($_POST as $key=>$value){
		$$key = $value;
	}
	
	//錯誤訊息
	$errMsg = "";
	
	
	if($supPwd == ""){
		$errMsg = "必須填入驗證碼";
	}
	else
    {
	    $sup = new Supplier();
        $sup->updateCP($supNo , $supPwd) ;
	}		
	echo $errMsg;

?>
