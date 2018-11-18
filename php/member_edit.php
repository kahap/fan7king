<?php
	session_start();
	include('../model/php_model.php');
	$member = new Member();
	$email = new Email();
	$or = new Orders();
	$memberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
	$iforder = $or->getOrderhistory($_SESSION['user']['memNo']);
	
	$columnName1 = $or->getAllColumnNames("member");
	foreach($columnName1 as $key => $value){
		$colum1[$value['COLUMN_NAME']] = $value['COLUMN_COMMENT'];
	}
	$other = array('memOther','memLineId','memSubEmail');
	foreach($_POST as $key => $value){
		if($value == ""){
			if(!in_array($key,$other)){
				if($_POST['memClass'] == '0'){
					$errg[] = $colum1[$key];
				}
				
			}

		}
	}
	If(strrpos($_POST['memSubEmail'],'@') == false){
		$errg[] = "常用聯絡Email不是確實的Email ";
	}
	if($_POST['memGender'] == ""){
		$errg[] = "請填寫性別欄位";
	}
	
	$msg = implode(',',$errg);
	if($errg == ""){
		
		if($memberData['0']['memEmailAuthen'] == 0){
			$_POST['memBday'] = $_POST['year']."-".$_POST['month']."-".$_POST['date'];
			$member->update_fornMember($_POST,$_SESSION['user']['memNo']);
			$receiverNameAndEmails = $_POST['memAccount'];
				$title = "【NoWait購物網】會員認證信件‏";
				$content = '	
							<table width="660" align="center" cellpadding="10" cellspacing="1" style="border:3px solid #999;">
								<tbody>
									<tr>
										<td style="text-align:center;">
											<img src="https://nowait.shop/assets/images/logo_2.png" />
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
											<p>這封認證信是由<span style="color:#0006FF;text-decoration:underline;">NoWait購物網</span>所發出，<span style="color:red">請點選下面鏈結</span>開通您的會員帳號，您將享受NoWait購物網提供的會員購物服務。</p>
											<p>
												<a href=https://nowait.shop/php/member_id.php?pass_number='.$_POST['pass_number'].'&memNo='.$_POST['memNo'].' target="_blank">https://nowait.shop/php/member_id.php?id='.$_POST['pass_number'].'</a>
											</p>
											<p>若此帳號並非您本人所申請，請您不須理會此會員確認信函。 感謝您的支持，如有疑問歡迎到 <a href="https://nowait.shop/index.php?item=contact" target="_blank"><span style="#FF9900;text-decoration:underline;">聯絡客服</span></a> 反應，NoWait將會為您處理。 NoWait購物網祝福您 順心如意!!</p>
										</td>
									</tr>
								</tbody>
							</table>
							';
				
				
				

				$send = $email->SendEmail_smtp($receiverNameAndEmails,$_POST['memName'], "happyfan@nowait.shop", "NoWait", $title, $content);	
			echo "1";
		}else{
			if($memberData['0']['memEmailAuthen'] == 1){
				$_POST['memAccount'] = $memberData['0']['memAccount'];
				$_POST['memBday'] = $memberData['0']['memBday'];
				$_POST['memIdNum'] = $memberData['0']['memIdNum'];
				if($iforder != ""){
					$_POST['memName'] = $memberData['0']['memName'];
				}
			}
			//$_POST['memBday'] = $_POST['year']."-".$_POST['month']."-".$_POST['date'];
			$member->update_fornMember($_POST,$_SESSION['user']['memNo']);
			$email_account = ($memberData[0]['memClass']=='0') ? $memberData[0]['memSubEmail']:$memberData[0]['memAccount'];
			$receiverNameAndEmails = Array($email_account=>$memberData[0]['memName']);
			$title = "【NoWait購物網】通知基本資料已變更成功";
			$content = '	
						<table width="660" align="center" cellpadding="10" cellspacing="1" style="border:3px solid #999;">
							<tbody>
								<tr>
									<td style="text-align:center;">
										<img src="https://nowait.shop/assets/images/logo_2.png" />
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
										<p>親愛的會員您好，您的基本資料已變更成功。</p>
									</td>
								</tr>
							</tbody>
						</table>';
			$send = $email->SendBCCEmail_smtp($receiverNameAndEmails, "happyfan@nowait.shop", "NoWait", $title, $content);
			echo "1";
		}
	}else{
		echo $msg;
	}
	
?>