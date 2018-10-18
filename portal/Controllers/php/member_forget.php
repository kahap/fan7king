<?php

	header('Content-Type: text/html; charset=utf8');
	include('../model/php_model.php');
	
	$email = new Email();
	$member = new Member();
	$aaa = (empty($_POST['memAccount'])) ? 'EMAIL是空':$_POST['memAccount'];
	$member_data = $member->getMemberforgetdata($aaa);
	if($member_data != ""){
	$title = "[重要]樂分期會員密碼通知";
	$content = '	
				<table width="660" align="center" cellpadding="10" cellspacing="1" style="border:3px solid #999;">
					<tbody>
						<tr>
							<td style="text-align:center;">
								<img src="http://happyfan7.com/assets/images/logo_2.png" />
							</td>
						</tr>
						<tr>
							<td style="color:#FF3333;font-weight:bold;text-align:center;">此為系統自動通知信，請勿直接回信！</td>
						</tr>
						<tr>
							<td style="color:black;font-weight:bold;">
								<p>親愛的<span style="color:#FF9900;">'.$member_data['memName'].' </span> 先生/小姐，您好：</p>
							</td>
						</tr>
						<tr>
							<td style="font-weight:bold;background-color:#F5F3F1;">
								<p>這封會員密碼信是由<span style="color:#0006FF;text-decoration:underline;">樂分期購物網</span>所發出，<span style="color:red">您的密碼如下</span>再登入會員帳號，您將享受樂分期購物網提供的會員購物服務。</p>
								<p>
									<span>'.$member_data['memPwd'].'</span>
								</p>
								<p>若此帳號並非您本人所申請，請您不須理會此會員確認信函。 感謝您的支持，如有疑問歡迎到 <a href="http://happyfan7.com/index.php?item=fmContactService" target="_blank"><span style="#FF9900;text-decoration:underline;">聯絡客服</span></a> 反應，樂分期將會為您處理。 樂分期購物網祝福您 順心如意!!</p>
							</td>
						</tr>
					</tbody>
				</table>
				';
	
	
	

	$send = $email->SendEmail_smtp($member_data['memAccount'],$member_data['memName'], "happyfan@happyfan7.com", "樂分期", $title, $content);
	print_r($send);
		if($send != ""){
			echo false;
		}else{
			echo true;
		}
	}else{
		echo false;
	}


?>