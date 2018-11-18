<?php
	session_start();
	include('../model/php_model.php');
	$member = new Member();
	$email = new Email();
	$lg = new Loyal_Guest();
	$or = new Orders();
	$columnName = $or->getAllColumnNames("member");
	foreach($columnName as $key => $value){
		$colum[$value['COLUMN_NAME']] = $value['COLUMN_COMMENT'];
	}
	
	$_POST['memBday'] = $_POST['year']."-".$_POST['month']."-".$_POST['date'];
	$array = array('memOther','memLineId','memSubEmail','memRecommCode');
	$array1 = array('memOther','memLineId','memSubEmail','memRecommCode','memSchool');
	foreach($_POST as $key => $value){
		if($value == ""){
			if($_POST['memClass'] == 0){
				if(!in_array($key,$array)){
					$errg[] = $colum[$key]."格式有誤";
				}
			}else{
				if(!in_array($key,$array1)){
					$errg[] = $colum[$key]."格式有誤";
				}
			}
		}
	}
	
	//$errg = $_POST['memGender'];
	if($_POST['memAccount'] != ""){
		if(validEmail($_POST['memAccount']) == false){
			$errg[] = "Email驗證有誤";
		}
		
	}
	
	if($member->check_id($_POST['memIdNum'])){
		$errg[] = "身分證字號重複或者不能為空";
	}
	if($member->check_account($_POST['memAccount'])){
		$errg[] = "帳號已經註冊過了或者不能為空!!";
	}
	$msg = implode(',',$errg);
	if($msg == ""){
			
			$_POST['memRecommCode'] = ($_SESSION['user']['sharcode'] != "") ? $_SESSION['user']['sharcode']:$_POST['memRecommCode'];
			$_POST['memAllowLogin'] = '1';
			$_POST['memRegistMethod'] = '1';
			
			$_POST['pass_number'] = Pass();
			$memNo = $member->insert($_POST);
			
			
			
			$receiverNameAndEmails = Array($memberData[0]['memAccount']=>$memberData[0]['memName'],'sinlenlin@gmail.com'=>'客服人員A','biglee2275@gmail.com'=>'客服人員B','lainelinlin@gmail.com'=>'客服人員C','aa22760676@gmail.com'=>'客服人員D');
			$title = "[重要]NoWait會員認證信";
			$content = '	
						<table width="660" align="center" cellpadding="10" cellspacing="1" style="border:3px solid #999;">
							<tbody>
								<tr>
									<td style="text-align:center;">
										<img src="http://nowait.shop/assets/images/logo_2.png" />
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
											<a href=http://nowait.shop/php/member_id.php?pass_number='.$_POST['pass_number'].'&memNo='.$memNo.'>http://nowait.shop/php/member_id.php?id='.$_POST['pass_number'].'</a>
										</p>
										<p>若此帳號並非您本人所申請，請您不須理會此會員確認信函。 感謝您的支持，如有疑問歡迎到 <a href="http://nowait.shop/index.php?item=fmContactService" target="_blank"><span style="#FF9900;text-decoration:underline;">聯絡客服</span></a> 反應，NoWait將會為您處理。 NoWait購物網祝福您 順心如意!!</p>
									</td>
								</tr>
							</tbody>
						</table>
						';
			
			
			

			$send = $email->SendBCCEmail_smtp($receiverNameAndEmails, "happyfan@nowait.shop", "NoWait", $title, $content);
			
			$msg = '4';
	}
	
	echo $msg;
	
	function Pass($i=8) { 
	    srand((double)microtime()*1000000); 
	    return strtoupper(substr(md5(uniqid(rand())),rand(0,32-$i),$i)); 
	}
	
	function validEmail($email) {
	$regexp = "^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$";
	if ($email != '') {
		return true;
	}else{
		return false;
	}
}	
?>