<?php 

if(isset($_POST["adTokenId"]) && $_POST["adTokenId"] != ""){
	$email = new Email();
	$mem = new Member();
	$memNo = getMemberNo();
	$memData = $mem->getOneMemberByNo($memNo);
	
	if($memData[0]["memAccount"] != ""){
		$receiverNameAndEmails = Array($memData[0]["memAccount"]=>$memData[0]['memName'],'sinlenlin@gmail.com'=>'客服人員A','biglee2275@gmail.com'=>'客服人員B','aa22760676@gmail.com'=>'客服人員D');
		$title = "[重要]樂分期會員認證信";
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
									<p>親愛的<span style="color:#FF9900;">'.$memData[0]['memName'].' </span> 先生/小姐，您好：</p>
								</td>
							</tr>
							<tr>
								<td style="font-weight:bold;background-color:#F5F3F1;">
									<p>這封認證信是由<span style="color:#0006FF;text-decoration:underline;">樂分期購物網</span>所發出，<span style="color:red">請點選下面鏈結</span>開通您的會員帳號，您將享受樂分期購物網提供的會員購物服務。</p>
									<p>
										<a href=https://happyfan7.com/php/member_id.php?pass_number='.$memData[0]['pass_number'].'&memNo='.$memData[0]['memNo'].'>https://happyfan7.com/php/member_id.php?pass_number='.$memData[0]['pass_number'].'&memNo='.$memData[0]['memNo'].'</a>
									</p>
									<p>若此帳號並非您本人所申請，請您不須理會此會員確認信函。 感謝您的支持，如有疑問歡迎到 <a href="https://happyfan7.com/index.php?item=contact" target="_blank"><span style="#FF9900;text-decoration:underline;">聯絡客服</span></a> 反應，樂分期將會為您處理。 樂分期購物網祝福您 順心如意!!</p>
								</td>
							</tr>
						</tbody>
					</table>
					';




		//$send = $email->SendEmail_smtp($memData[0]["memAccount"],$memData[0]['memName'], "service@happyfan7.com", "樂分期", $title, $content);
		$send = $email->SendBCCEmail_smtp($receiverNameAndEmails, "service@happyfan7.com", "樂分期", $title, $content);
		if($send != ""){
			$api->setInformation(false, 0, 0, $send);
		}else{
			$api->setInformation(true, 1, 1, "已重新寄出驗證信件。");
		}
	}else{
		$api->setInformation(false, 0, 0, "您在初次下單後系統將會立即發送認證信至您填寫的信箱！");
	}
}else{
	$api->setInformation(false, 0, 0, "請帶入adTokenId。");
}
$api->setResult();

?>