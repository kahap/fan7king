<?php
	header (" content-type: text/HTML; charset=utf-8 ");
	require_once('../../model/require_login.php');
	
	$or = new Orders();
	$pro = new Product();
	$pm = new Product_Manage();
	$mem = new Member();
	$email = new Email();
	
	$orNo = $_POST["orNo"];
	
	$orOldData = $or->getOneOrderByNo($orNo);
	
	$memData = $mem->getOneMemberByNo($orOldData[0]["memNo"]);
	$pmData = $pm->getOnePMByNo($orOldData[0]["pmNo"]);
	$proData = $pro->getOneProByNo($pmData[0]["proNo"]);
	$status = sendEmailForStatChange(5,$orOldData,$memData,$pmData,$proData,$email);
	
	echo true;
	

?>
