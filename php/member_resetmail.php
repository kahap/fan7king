<?php
	session_start();
	header('Content-Type: text/html; charset=utf8');
	include('../model/php_model.php');
	
	$member = new Member();
	$memberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
	$email = new Email();
	$receiverNameAndEmails = Array($memberData[0]['memAccount']=>$memberData[0]['memName'],'sinlenlin@gmail.com'=>'客服人員A','aa22760676@gmail.com'=>'客服人員D');
	$title = "【樂分期購物網】會員認證信件";
	$content = '	
				<table width="660" align="center" cellpadding="10" cellspacing="1" style="border:3px solid #999;">
					<tbody>
						<tr>
							<td style="text-align:center;">
								<img src="https://happyfan7.com/assets/images/logo_2.png" />
							</td>
						</tr>
						<tr>
							<td style="color:#FF3333;font-weight:bold;text-align:center;">此為系統自動通知信，請勿直接回信！</td>
						</tr>
						<tr>
							<td style="color:black;font-weight:bold;">
								<p>親愛的<span style="color:#FF9900;">'.$_POST['memName'].' </span> 先生/小姐，您好：</p>
							</td>
						</tr>
						<tr>
							<td style="font-weight:bold;background-color:#F5F3F1;">
								<p>這封認證信是由<span style="color:#0006FF;text-decoration:underline;">樂分期購物網</span>所發出，<span style="color:red">請點選下面鏈結</span>開通您的會員帳號，您將享受樂分期購物網提供的會員購物服務。</p>
								<p>
									<a href=https://happyfan7.com/php/member_id.php?pass_number='.$_POST['pass_number'].'&memNo='.$_SESSION['user']['memNo'].'>https://happyfan7.com/php/member_id.php?pass_number='.$_POST['pass_number'].'&memNo='.$_SESSION['user']['memNo'].'</a>
								</p>
								<p>若此帳號並非您本人所申請，請您不須理會此會員確認信函。 感謝您的支持，如有疑問歡迎到 <a href="https://happyfan7.com/index.php?item=fmContactService" target="_blank"><span style="#FF9900;text-decoration:underline;">聯絡客服</span></a> 反應，樂分期將會為您處理。 樂分期購物網祝福您 順心如意!!</p>
							</td>
						</tr>
					</tbody>
				</table>
				';
	
	
	

	$send = $email->SendBCCEmail_smtp($receiverNameAndEmails, "service@happyfan7.com", "樂分期", $title, $content);
	if($send != ""){
		echo false;
	}else{
		echo true;
	}


?>