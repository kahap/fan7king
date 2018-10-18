<?php

if(isset($_POST["adTokenId"]) && $_POST["adTokenId"] != ""){

	$memNo = getMemberNo();

	$mem = new Member();

	$memData = $mem->getOneMemberByNo($memNo);

	$os = new API("other_setting");

	$os->setRetrieveArray(array("iosVersion","androidVersion"));

	$os->getWithWhereAndJoinClause();

	$osData = $os->getData();


	if($memData[0]["memAllowLogin"] != "1"){
		//登出
		$api->delete($_POST);
		$api->setInformation($osData[0], 0, 0, "您的帳號已停權，現在即將登出，請洽詢客服人員。");
	}else{
            $loginArr = array(
				"iosVersion" => $osData[0]["iosVersion"],
				"androidVersion" => $osData[0]["androidVersion"],
				"memClass" => $memData[0]["memClass"],
				"memIdNum" => $memData[0]["memIdNum"],
			);
		$api->setInformation($loginArr, 1, 1, "帳號激活中。");

	}

}else{

	$api->setInformation($osData[0], 0, 0, "請帶入TOKEN。");

}

$api->setResult();



?>