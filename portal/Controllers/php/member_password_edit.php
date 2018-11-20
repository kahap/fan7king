<?php

	session_start();
	include('../../model/php_model.php');

	$member = new Member();
	$email = new Email();	
	$memberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
	if(password_verify($_POST['memPwd'],$memberData['0']['memPwd'])){
		$member->update_fornMember_password($_POST,$_SESSION['user']['memNo']);
			
			$reciemail = ($memberData[0]['memClass'] == '0') ? $memberData[0]['memSubEmail']:$memberData[0]['memAccount'];
			$receiverNameAndEmails = Array($reciemail=>$memberData[0]['memName']);

			$title = "【NoWait購物網】通知密碼已變更成功";
			$content = 'NoWait 通知您的密碼已變更成功！！！此為系統自動信通知的信件，切勿回覆！若有問題，歡迎來電告知客服 (02-26560619)';
			$sms = new SMSHttp();
        
			$subject = "密碼變更通知";	//簡訊主旨，主旨不會隨著簡訊內容發送出去。用以註記本次發送之用途。可傳入空字串。
			$mobile = $memberData['0']['memCell'];	//接收人之手機號碼。格式為: +886912345678或09123456789。多筆接收人時，請以半形逗點隔開( , )，如0912345678,0922333444。
			$sendTime= "";		//簡訊預定發送時間。-立即發送：請傳入空字串。-預約發送：請傳入預計發送時間，若傳送時間小於系統接單時間，將不予傳送。格式為YYYYMMDDhhmnss；例如:預約2009/01/31 15:30:00發送，則傳入20090131153000。若傳遞時間已逾現在之時間，將立即發送。
			
			// //傳送簡訊
			$sms->sendSMS($subject,$content,$mobile,$sendTime);
			
		echo true;
	}else{
		echo false;
	}
	
?>