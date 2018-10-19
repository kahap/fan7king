<?php
	session_start();
	include('../model/php_model.php');
	$member = new Member();
	$or = new Orders();
	$pm = new Product_Manage();
	$p = new Product();
	$_GET['pass_number'];
	$_GET['memNo'];
	if($_GET['memNo'] != ""){
		$member_data = $member->getMembermemEmailAuthen($_GET);
		$orMemData = $or->getOrByMemberAndMethod($member_data['memNo'],1);
		
		if($member_data != ""){
			if($member_data['memEmailAuthen'] != "1"){
				$member->update_memEmailAuthen($_GET['memNo']);
				if(count($orMemData)>=1){
					foreach($orMemData as $key => $value){
						if($value['orStatus'] == '0'){
							$or->updateStatus('1',$value['orNo']);
							$or->updateStatusTime('1',$value['orNo']);
							
							$pmBuyAmnt = $pm->getOnePMByNo($value['pmNo']);
							$p_data = $p->getOneProByNo($pmBuyAmnt[0]['proNo']);
							$Class = ($member_data['memClass'] == '0') ? '學生':'非學生';
			$email = new Email();				
			$receiverNameAndEmails = Array('service@happyfan7.com'=>"EC部","happyfan7@21-finance.com"=>"客服部","sinlenlin@gmail.com"=>"林青嵐","achappyfan7@gmail.com"=>"Allan","andy_kuo@21-finance.com"=>"郭原彰","dan_chang@21-finance.com"=>"客服1",'aa22760676@gmail.com'=>'客服人員D');
			
			$title = "【樂分期-未進件】".$value['orDate'].",流水號:".$value['orNo'].",".$member_data['memName']."先生/小姐,訂單編號:".$value['orCaseNo'];
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
										<p>顧客姓名：<span style="color:#FF9900;">'.$member_data['memName'].' </span> 先生/小姐</p>
									</td>
								</tr>
								<tr>
									<td style="font-weight:bold;background-color:#F5F3F1;">
										【樂分期-進件通知】<br>

										身份證字號：'.$member_data['memIdNum'].'<br>
										
										身份：'.$Class.'<br>

										訂單狀態：未進件<br>

										商品明細如下：<br>

										訂單編號：'.$value['orCaseNo'].'<br>

										訂購日期：'.$value['orDate'].'<br> 
										
										訂購商品：'.$p_data[0]['proName'].'<br> 

										商品規格：'.$value['orProSpec'].'<BR>
										
										分期期數：'.$value['orPeriodAmnt'].' 期<BR>
										
										每期金額：'.number_format($value['orPeriodTotal']/$value['orPeriodAmnt']).' 元<BR>
										
										
									</td>
								</tr>
							</tbody>
						</table>';
			$send = $email->SendBCCEmail_smtp($receiverNameAndEmails, "happyfan@happyfan7.com", "樂分期", $title, $content);
			/*if(in_array("Allan",$receiverNameAndEmails)){
				$ch = curl_init("http://happyfan7.com/php/index.php?inst=happyfan7&msg=".str_replace(" ","_",$title));
				curl_setopt($ch, CURLOPT_HTTPHEADER, false);
				$result = curl_exec($ch);
				curl_close($ch);
			}*/
						}
					}
				}
				
				$_SESSION['user']['status'] = "success";
			}else{
				$_SESSION['user']['status'] = "already";
			}
		}else{
			$_SESSION['user']['status'] = "error";
		}
	}else{
		$_SESSION['user']['status'] = "error";
	}
	echo "<script>location.href='../index.php?item=login'</script>";

?>