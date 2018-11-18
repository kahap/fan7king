<?php

	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	$email = new Email();
	
	$errMsg = "";
	
	//check box
	$sendAll = "";
	$receiverMail = array();
	$receiverName = array();
	
	
	
	foreach($_POST as $key=>$value){
		$$key=$value;
	}
	
	$receiverNameAndEmails = array();
	
	
	if(isset($_POST["sendAll"]) || isset($_POST["receiverMail"]) || isset($_POST["receiverName"])){
		if(isset($_POST["sendAll"])){
			$sendAll = $_POST["sendAll"];
			$member = new Member();
			$allMemData = $member->getAllMember();
			foreach ($allMemData as $key=>$value){
				$receiverNameAndEmails[$value["memAccount"]] = $value["memName"];
			}
		}else if(isset($_POST["receiverMail"])){
			$receiverMail = $_POST["receiverMail"];
			$receiverName = $_POST["receiverName"];
			$which = 0;
			foreach ($receiverMail as $value){
				$receiverNameAndEmails[$value] = $receiverName[$which];
				$which++;
			}
		}
	}else{
		$errMsg .= "*請填入使用者\n";
	}
	if(trim($title) == ""){
		$errMsg .= "*請填入郵件主旨\n";
	}
	if(trim($content) == ""){
		$errMsg .= "*請填入郵件內容\n";
	}
	
	if($errMsg == ""){
		$send = $email->SendBCCEmail_smtp($receiverNameAndEmails, "service@happyfan7.com", "NoWait", $title, $content);
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