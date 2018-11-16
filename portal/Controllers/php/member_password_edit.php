<?php

	session_start();
	include('../../model/php_model.php');

	$member = new Member();
	$email = new Email();

	$memberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
	if($memberData['0']['memPwd'] == $_POST['memPwd']){
		$member->update_fornMember_password($_POST,$_SESSION['user']['memNo']);
			
			$reciemail = ($memberData[0]['memClass'] == '0') ? $memberData[0]['memSubEmail']:$memberData[0]['memAccount'];
			$receiverNameAndEmails = Array($reciemail=>$memberData[0]['memName']);

			$title = "【樂分期購物網】通知密碼已變更成功";
			$content = '';
			// TODO 寄簡訊通知
		echo true;
	}else{
		echo false;
	}
	
?>