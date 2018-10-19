<?php
	session_start();
	$member = new Member();
	include "cls/email/class.phpmailer.php"; //匯入PHPMailer類別
	$email = new Email();
	$pm = new Product_Manage();
	$pro = new Product();
	$memberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
	//商品上架
	$pmData = $pm->getOnePMByNo($or_data[0]["pmNo"]);
	
	//商品
	$proData = $pro->getOneProByNo($pmData[0]["proNo"]);
	
	
	if($_SESSION['user']['memNo'] == ""){
		$errg = "拒絕存取";
	}
	if($errg == ""){
			$or->updateStatus('7',$_GET['orno']);
			$or->updateStatusTime('7',$_GET['orno']);
			$receiverNameAndEmails = Array('service@happyfan7.com'=>"EC部",'aa22760676@gmail.com'=>'客服人員D');
			//'hsiang_chou@21-finance.com'=>"客服部",'tiffany_chen@21-finance.com'=>"客服部",
			$title = "標題:【樂分期-取消訂單】".$or_data[0]['orDate'].",流水號:".$or_data['0']['orNo'].", ".$memberData[0]['memName']."先生/小姐,訂單編號:".$or_data[0]['orCaseNo'];
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
										<p>顧客姓名：<span style="color:#FF9900;">'.$memberData[0]['memName'].' </span> 先生/小姐</p>
									</td>
								</tr>
								<tr>
									<td style="font-weight:bold;background-color:#F5F3F1;">
										【樂分期-取消訂單】<br>

										身份證字號：'.$memberData[0]['memIdNum'].'<br>

										訂單狀態：取消訂單<br>

										商品明細如下：<br>

										訂單編號：'.$or_data[0]['orCaseNo'].'<br>

										訂購日期：'.$or_data[0]['orDate'].'<br> 
										
										訂購商品：'.$proData[0]['proName'].'<br> 

										商品規格：'.$or_data[0]['orProSpec'].'<BR>
										
										分期期數：'.$or_data[0]['orPeriodAmnt'].' 期<BR>
										
										每期金額：'.number_format($or_data[0]['orPeriodTotal']/$or_data[0]['orPeriodAmnt']).' 元<BR>
										
										
									</td>
								</tr>
							</tbody>
						</table>';
			$send = $email->SendBCCEmail_smtp($receiverNameAndEmails, "happyfan@happyfan7.com", "樂分期", $title, $content);
			
			$email = new Email();
			$receiverNameAndEmails1 = Array($memberData[0]['memAccount']=>$memberData[0]['memName']);
			$title1 = "【樂分期購物網】您訂購的商品已取消訂單(訂單編號:".$or_data[0]['orCaseNo'].")";
			$content1 = '<table width="660" align="center" cellpadding="10" cellspacing="1" style="border:3px solid #999;">
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
								<p>親愛的<span style="color:#FF9900;">'.$memberData[0]['memName'].' </span> 先生/小姐，您好：</p>
							</td>
						</tr>
						<tr>
									<td style="font-weight:bold;background-color:#F5F3F1;">
										您所訂購的商品已取消訂單，您此次訂購的商品明細如下：<br>

										訂單編號：'.$or_data[0]['orCaseNo'].'<br>

										訂購日期：'.$or_data[0]['orDate'].'<br> 
										
										訂購商品：'.$proData[0]['proName'].'<br> 

										商品規格：'.$or_data[0]['orProSpec'].'<BR>
										
										分期期數：'.$or_data[0]['orPeriodAmnt'].' 期<BR>
										
										每期金額：'.number_format($or_data[0]['orPeriodTotal']/$or_data[0]['orPeriodAmnt']).' 元<BR>
										

										
									</td>
								</tr>
						<tr>
							<td>
								<p>
									感謝您的支持，如有疑問歡迎到 <a href="https://happyfan7.com/index.php?item=fmContactService" target="_blank"><span style="#FF9900;text-decoration:underline;">聯絡客服</span></a> 反應，
									樂分期將會為您處理。如需訂購其他商品請至 <a href="https://happyfan7.com/index.php" target="_blank"><span style="color:blue;text-decoration:underline;">樂分期購物網</span></a> 選購。
								</p>
							</td>
						</tr>						
					</tbody>
				</table>';
			$send = $email->SendBCCEmail_smtp($receiverNameAndEmails1, "happyfan@happyfan7.com", "樂分期", $title1, $content1);
			echo "<script>alert('已取消訂單');</script>";
			echo "<script>location.href='index.php?item=member_center&action=purchase&orno=".$_GET['orno']."';</script>";
	}
	
	
?>