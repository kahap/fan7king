<?php

	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../model/require_login.php');
	
	$rbs = new Recomm_Bonus_Success();
	
	$result = array();
	
	foreach($_POST as $key=>$value){
		$$key = $value;
	}
	
	$rbsData = $rbs->getOneRBSByNo($rbsNo);
	
	$rbaNo = $rbsData[0]["rbaNo"];
	$rbaNoArr = json_decode($rbaNo,true);
	
	$rba = new Recomm_Bonus_Apply();
	$or = new Orders();
	$mem = new Member();
	
	foreach($rbaNoArr as $rbaNoEach){
		$rbaData = $rba->getOneRBAByNo($rbaNoEach);
		$orNo = $rbaData[0]["orNo"];
		$orData = $or->getOneOrderByNo($orNo);
		
		$or->changeToReadable($orData[0],1);
		$memData = $mem->getOneMemberByNo($orData[0]["memNo"]);
		$inputArr = array("訂單日期"=>$orData[0]["orDate"],
				"會員姓名"=>$memData[0]["memName"],
				"身份證字號"=>$memData[0]["memIdNum"],
				"訂單編號"=>$orData[0]["orCaseNo"],
				"訂單狀態"=>$orData[0]["orStatus"]);
		array_push($result,$inputArr);
	}
	
	
	
	echo json_encode($result,JSON_UNESCAPED_UNICODE);

?>