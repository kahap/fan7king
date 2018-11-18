<?php
	session_start();
	include('../model/php_model.php');
	$member = new Member();
	$email = new Email();
	$memberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
	if($memberData['0']['memPwd'] == $_POST['memPwd']){
		$member->update_fornMember_password($_POST,$_SESSION['user']['memNo']);
			
			$reciemail = ($memberData[0]['memClass'] == '0') ? $memberData[0]['memSubEmail']:$memberData[0]['memAccount'];
			$receiverNameAndEmails = Array($reciemail=>$memberData[0]['memName']);
			$title = "【NoWait購物網】通知密碼已變更成功";
			$content = '	
						<table width="660" align="center" cellpadding="10" cellspacing="1" style="border:3px solid #999;">
							<tbody>
								<tr>
									<td style="text-align:center;">
										<img src="http://nowait.shop/assets/images/logo_2.png" />
									</td>
								</tr>
								<tr>
									<td style="color:#FF3333;font-weight:bold;text-align:center;">
										此為系統自動通知信，請勿直接回信！<br>
										若您有任何問題，請透過網站<a href="http://nowait.shop/index.php?item=fmContactService" target="_blank"><span style="#FF9900;text-decoration:underline;">聯絡客服</span></a>人員查詢。
									</td>
								</tr>
								<tr>
									<td style="color:black;font-weight:bold;background-color:#F5F3F1;">
										<p>親愛的會員您好，您的密碼已變更成功。</p>
									</td>
								</tr>
							</tbody>
						</table>';
			$send = $email->SendBCCEmail_smtp($receiverNameAndEmails, "happyfan@nowait.shop", "NoWait", $title, $content);
		echo true;
	}else{
		echo false;
	}
	
?>