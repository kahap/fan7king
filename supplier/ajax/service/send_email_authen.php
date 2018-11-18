<?php

	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	$email = new Email();
	$member = new Member();
	
	
	$errMsg = "";
	
	
	foreach($_POST as $key=>$value){
		$$key=$value;
	}
	$memData = $member->getOneMemberByNo($memNo);
	
	$receiverNameAndEmails = array();
	$receiverNameAndEmails[$memData[0]["memAccount"]] = $memData[0]["memName"];
	
	$title = "【NoWait購物網】請驗證您的Email信箱";
	
	$content = "感謝您申請";
	
	
	if($errMsg == ""){
		$send = $email->SendBCCEmail_smtp($receiverNameAndEmails, "happyfan7@21-finance.com", "NoWait", $title, $content);
		if($send != ""){
			$errMsg .= $send."\n\n";
			echo $errMsg;
		}else{
			echo "送出成功！";
		}
	}else{
		echo $errMsg;
	}

?>