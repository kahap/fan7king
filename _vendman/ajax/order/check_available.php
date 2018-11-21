<?php
	header (" content-type: text/HTML; charset=utf-8 ");
	require_once('../../model/require_login.php');
	
	
	$pm = new Product_Manage();
	
	foreach($_POST as $key=>$value){
		$$key = $value;
	}
	
	$pmData = $pm->getOnePMBySupAndPro($proNo, $supNo);
	
	if($pmData[0]["pmStatus"] == 2){
		echo "NO";
	}else{
		echo "OK";
	}
	

?>
