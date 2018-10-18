<?php
	session_start();
	include('../model/php_model.php');
	$member = new Member();
	$email = new Email();
	$memberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
	$rbs = new Recomm_Bonus_Success();
	
	$notbing = array('rbsBankAccName'=>'個人存摺戶名','rbsBankName'=>'匯款銀行名稱','rbsBankBranchName'=>'分行名稱','rbsBankAcc'=>'匯款帳號');
	foreach($_POST as $key =>$value){
		if($value == ""){
			if(array_key_exists($key,$notbing)){
				$errg[] = $notbing[$key];
			}				
		}
	}
	$msg = implode(',',$errg);
	
	if($msg == ""){
	$status = $rbs->updatDATA($_POST,$_SESSION['rbs_id']);
			$receiverNameAndEmails = Array('service@happyfan7.com'=>"EC部",'lainelinlin@gmail.com'=>'客服人員C',"sinlenlin@gmail.com"=>"林青嵐");
			$title = "【樂分期購物網】 ".$memberData[0]['memName']."  先生/小姐，申請推薦碼獎金".$_SESSION['rbsTotal']."元";
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
										<p>顧客姓名：<span style="color:#FF9900;">'.$memberData[0]['memName'].' </span> 先生/小姐</p>
									</td>
								</tr>
								<tr>
									<td style="font-weight:bold;background-color:#F5F3F1;">
										系統編號：'.$_SESSION['rbs_id'].'<br>
									</td>
								</tr>
								<tr>
									管理員可至連結至後台>會員管理>推薦碼獎金撥款，進行後續撥款作業。
								</tr>
							</tbody>
						</table>';
			$send = $email->SendBCCEmail_smtp($receiverNameAndEmails, "happyfan@happyfan7.com", "樂分期", $title, $content);
	



	unset($_SESSION['MackMoney']);
	unset($_SESSION['rbsTotal']);
	echo 1;
	}else{
		echo $msg;
	}
?>