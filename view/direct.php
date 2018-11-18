<?php
	
	
	$receiverNameAndEmails = Array('tom200e@hotmail.com'=>"EC部");
					$paymethod = ($or_data[0]['orPayBy'] != '2') ? 'ATM':'WebATM';
					$title = "【NoWait-直購通知】".$or_data[0]['orDate'].",流水號:".$_SESSION['ord_code'].", ".$memberData[0]['memName']."先生/小姐,訂單編號:".$or_data[0]['orCaseNo'];
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
										<p>顧客姓名：<span style="color:#FF9900;">'.$memberData[0]['memName'].' </span> 先生/小姐</p>
									</td>
								</tr>
								<tr>
									<td style="font-weight:bold;background-color:#F5F3F1;">
										【NoWait-直購通知】<br>

										身份證字號：'.$memberData[0]['memIdNum'].'<br>

										付款方式：'.$paymethod.'<br>

										商品明細如下：<br>

										訂單編號：'.$or_data[0]['orCaseNo'].'<br>

										訂購日期：'.$or_data[0]['orDate'].'<br> 
										
										訂購商品：'.$_SESSION['shopping_product'][0]['proName'].'<br> 

										商品規格：'.$or_data[0]['orProSpec'].'<BR>
										
										總金額：'.number_format($or_data[0]['orPeriodTotal']).' 元<BR>
										
										
									</td>
								</tr>
							</tbody>
						</table>';
					$email = new Email1();					
					$send = $email->SendBCCEmail_smtp($receiverNameAndEmails, "service@nowait.shop", "NoWait", $title, $content);
					
					
					$receiverNameAndEmails2 = Array($memberData[0]['memAccount']=>$memberData[0]['memName']);
					$title1 = "【NoWait購物網】您訂購的商品(訂單編號: ".$or_data[0]['orCaseNo'].")";
					$content1 = '	
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
										<p>親愛的<span style="color:#FF9900;">'.$_SESSION['shopping_user'][0]['memName'].' </span> 先生/小姐，您好：</p>

									</td>
								</tr>
								<tr>
											<td style="font-weight:bold;background-color:#F5F3F1;">
												您此次訂購的商品明細如下：<br>

												訂單編號：'.$or_data[0]['orCaseNo'].'<br>

												訂購日期：'.$or_data[0]['orDate'].'<br> 
												
												訂購商品：'.$_SESSION['shopping_product'][0]['proName'].'<br> 

												商品規格：'.$or_data[0]['orProSpec'].'<BR>
												
												總金額：'.number_format($or_data[0]['orPeriodTotal']).' 元<BR>
												

												
											</td>
										</tr>
								<tr>
									<td>
										<p>
											感謝您的支持，如有疑問歡迎到 <a href="https://nowait.shop/index.php?item=fmContactService" target="_blank"><span style="#FF9900;text-decoration:underline;">聯絡客服</span></a> 反應，
											NoWait將會為您處理。如需訂購其他商品請至 <a href="https://nowait.shop/index.php" target="_blank"><span style="color:blue;text-decoration:underline;">NoWait購物網</span></a> 選購。
										</p>
									</td>
								</tr>						
							</tbody>
						</table>
						';
					$email = new Email1();	
					$send = $email->SendBCCEmail_smtp($receiverNameAndEmails2, "service@nowait.shop", "NoWait", $title1, $content1);

?>