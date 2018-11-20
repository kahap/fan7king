<?php

	header (" content-type: text/HTML; charset=utf-8 ");
	require_once('../../model/require_login.php');
	
	$or = new Orders();
	$rba = new Recomm_Bonus_Apply();
	$rs = new Recomm_Setting();
	$rsData = $rs->getSetting();
	
	$allRbaData = $rba->getAllRBA();
	
	foreach($allRbaData as $key=>$value){
		$orData = $or->getOneOrderByNo($value["orNo"]);
		$orDate = $orData[0]["orReportPeriod10Date"];
		$ifPass = false;
		if($orDate != ""){
			if((time()-strtotime($orData[0]["orReportPeriod10Date"])) >= $rsData[0]["rsDaysLimit"]*86400){
				$ifPass = true;
			}
		}
		if($orData[0]["orStatus"] == 10 && $ifPass){
			$rba->updateStatus(1, $value["rbaNo"]);
		}else{
			$rba->updateStatus(0, $value["rbaNo"]);
		}
	}
	

?>