<?php
	session_start();
	include('../model/php_model.php');
	$or = new Orders();
	$member = new Member();
	$email = new Email();
	$pm = new Product_Manage();
	$p = new Product();
	
	
	$or_data = $or->getOneOrderByNo($_SESSION['ord_code']);
	
	$pm_data = $pm->getOnePMByNo($or_data[0]['pmNo']);
	$p_data = $p->getOneProByNo($pm_data[0]['proNo']);
	$memberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
	$re = new Recomm_Bonus_Apply();
	if($_SESSION['shopping_user'][0]['memNo'] == ""){
		$errg = false;
	}
	if($errg == ""){
		$orIfEditable = '1';
		$or->updateorIfEditable($orIfEditable,$_SESSION['ord_code']);
		$receiverNameAndEmails = Array($_SESSION['shopping_user'][0]['memAccount']=>$_SESSION['shopping_user'][0]['memName'],'lainelinlin@gmail.com'=>'客服人員C','service@nowait.shop'=>"EC部","happyfan7@21-finance.com"=>"客服部","sinlenlin@gmail.com"=>"林青嵐",'aa22760676@gmail.com'=>'客服人員D');/*memSubEmail
								'hsiang_chou@21-finance.com'=>"客服部",
								'tiffany_chen@21-finance.com'=>"客服部",
								'service@nowait.shop'=>"EC部"
		*/
			$title = "標題:【NoWait-未進件】".$or_data[0]['orDate'].",流水號:".$_SESSION['ord_code'].", ".$_SESSION['shopping_user'][0]['memName']."先生/小姐,訂單編號:".$or_data[0]['orCaseNo'];
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
										<p>親愛的<span style="color:#FF9900;">'.$_SESSION['shopping_user'][0]['memName'].' </span> 先生/小姐，您好：</p>
									</td>
								</tr>
								<tr>
									<td style="font-weight:bold;background-color:#F5F3F1;">
										【NoWait-進件通知】<br>

										身份證字號：'.$_SESSION['shopping_user'][0]['memIdNum'].'<br>

										訂單狀態：處理中<br>

										商品明細如下：<br>

										訂單編號：'.$or_data[0]['orCaseNo'].'<br>

										訂購日期：'.$or_data[0]['orDate'].'<br> 
										
										訂購商品：'.$p_data[0]['proName'].'<br> 

										商品規格：'.$or_data[0]['orProSpec'].'<BR>
										
										分期期數：'.$or_data[0]['orPeriodAmnt'].'<BR>
										
										每期金額：'.$or_data[0]['orPeriodTotal'].'<BR>
										
										
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