<?php
	session_start();
	include('../../model/php_model.php');

	$or = new Orders();
	$member = new Member();
	$email = new Email();
	$pm = new Product_Manage();
	$p = new Product();

	$or_data = $or->getOneOrderByNo($_SESSION['ord_code']);
	$memberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);

	$re = new Recomm_Bonus_Apply();
    $errg="";
	if($_SESSION['shopping_user'][0]['memNo'] == ""){
		$errg = "Session已過期";
	}

	if($errg == ""){
		$orIfEditable = '1';
		if($or_data[0]['orStatus'] == '5'){
			$or->updateStatus('6',$_SESSION['ord_code']);
			$or->updateStatusTime('6',$_SESSION['ord_code']);	
			$str_title = '補件';
		}else{
			/*fb進件模式*/
			if($memberData[0]['memClass'] == '0' && $memberData[0]['memFBtoken'] != ''){
				if($memberData[0]['memEmailAuthen'] == '0' or $memberData[0]['memEmailAuthen'] == '') {
					$or->updateStatus('0',$_SESSION['ord_code']);
					$or->updateStatusTime('0',$_SESSION['ord_code']);
				}elseif($memberData[0]['memEmailAuthen'] == '1'){
					$or->updateStatus('1',$_SESSION['ord_code']);
					$or->updateStatusTime('1',$_SESSION['ord_code']);
				}
			}elseif($memberData['0']['memFBtoken'] != ''){
				$or->updateStatus('1',$_SESSION['ord_code']);
				$or->updateStatusTime('1',$_SESSION['ord_code']);
			}
			
			/*一般帳號*/
			if($memberData[0]['memEmailAuthen'] == '1' && $memberData[0]['memFBtoken'] == ''){
				$or->updateStatus('1',$_SESSION['ord_code']);
				$or->updateStatusTime('1',$_SESSION['ord_code']);
			}

			//Email沒有驗證
			// if($memberData[0]['memEmailAuthen'] == '0' && $memberData[0]['memClass'] == '0'){
			// 	$str_title = '已下單，Email未驗證';
			// }else{
			// 	$str_title = '未進件';
			// }
			
		}

		//是否可編輯
		$or->updateorIfEditable($orIfEditable,$_SESSION['ord_code']);

        //實際購買人數+1
		$pmBuyAmnt = $pm->getOnePMByNo($or_data[0]['pmNo']);
		$pm->updatepmBuyAmnt($or_data[0]['pmNo'],$pmBuyAmnt[0]['pmBuyAmnt']);


		$p_data = $p->getOneProByNo($pmBuyAmnt[0]['proNo']);
		$Class = ($memberData[0]['memClass'] == '0') ? '學生':'非學生';

		/*
		if($memberData[0]['memEmailAuthen'] == '0' && $memberData[0]['memClass'] == '0'){
			$receiverNameAndEmails = Array('service@happyfan7.com'=>"EC部");
		}elseif($or_data[0]['orStatus'] == '5'){
			$receiverNameAndEmails = Array('service@happyfan7.com'=>"EC部","happyfan7@21-finance.com"=>"客服部","sinlenlin@gmail.com"=>"林青嵐","andy_kuo@21-finance.com"=>"郭原彰","dan_chang@21-finance.com"=>"客服1",'aa22760676@gmail.com'=>'客服人員D');
		}else{
			$receiverNameAndEmails = Array('service@happyfan7.com'=>"EC部","happyfan7@21-finance.com"=>"客服部","sinlenlin@gmail.com"=>"林青嵐","achappyfan7@gmail.com"=>"Allan","andy_kuo@21-finance.com"=>"郭原彰","dan_chang@21-finance.com"=>"客服1",'aa22760676@gmail.com'=>'客服人員D');
		}
			$title = "【樂分期-".$str_title."】".$or_data[0]['orDate'].",流水號:".$_SESSION['ord_code'].",".$memberData[0]['memName']."先生/小姐,訂單編號:".$or_data[0]['orCaseNo'];
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
										【樂分期-進件通知】<br>

										身份證字號：'.$memberData[0]['memIdNum'].'<br>

										身份：'.$Class.'<br>
										
										訂單狀態：'.$str_title.'<br>

										商品明細如下：<br>

										訂單編號：'.$or_data[0]['orCaseNo'].'<br>

										訂購日期：'.$or_data[0]['orDate'].'<br> 
										
										訂購商品：'.$p_data[0]['proName'].'<br> 

										商品規格：'.$or_data[0]['orProSpec'].'<BR>
										
										分期期數：'.$or_data[0]['orPeriodAmnt'].' 期<BR>
										
										每期金額：'.number_format($or_data[0]['orPeriodTotal']/$or_data[0]['orPeriodAmnt']).' 元<BR>
										
										
									</td>
								</tr>
							</tbody>
						</table>';
			$send = $email->SendBCCEmail_smtp($receiverNameAndEmails, "service@happyfan7.com", "樂分期", $title, $content);*/
			/**if(in_array("Allan",$receiverNameAndEmails)){
				$ch = curl_init("http://test.happyfan7.com/php/index.php?inst=happyfan7&msg=".str_replace(" ","_",$title));
				curl_setopt($ch, CURLOPT_HTTPHEADER, false);
				$result = curl_exec($ch);
				curl_close($ch);
			}**/
			
        /*if($memberData[0]['memEmailAuthen'] == 0 && $memberData[0]['memClass'] == '0'){
			$email = new Email();
			$receiverNameAndEmails1 = Array($memberData[0]['memAccount']=>$memberData[0]['memName'],'sinlenlin@gmail.com'=>'客服人員A','biglee2275@gmail.com'=>'客服人員B','min_yeon@kimo.com'=>'客服人員C','aa22760676@gmail.com'=>'客服人員D');
			$title1 = "【樂分期購物網】學校Email認證信件";
			$content1 = '	
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
								<p>親愛的<span style="color:#FF9900;">'.$_SESSION['shopping_user'][0]['memName'].' </span> 先生/小姐，您好：</p>
							</td>
						</tr>
						<tr>
							<td style="font-weight:bold;background-color:#F5F3F1;">
								<p>這封認證信是由<span style="color:#0006FF;text-decoration:underline;">樂分期購物網</span>所發出，<span style="color:red">請點選下面鏈結</span>開通您的會員帳號，您將享受樂分期購物網提供的會員購物服務。</p>
								<p>
									<a href=https://happyfan7.com/php/member_id.php?pass_number='.$memberData[0]['pass_number'].'&memNo='.$_SESSION['user']['memNo'].'>https://happyfan7.com/php/member_id.php?pass_number='.$memberData[0]['pass_number'].'&memNo='.$_SESSION['user']['memNo'].'</a>
								</p>
								<p>若此帳號並非您本人所申請，請您不須理會此會員確認信函。 感謝您的支持，如有疑問歡迎到 <a href="https://happyfan7.com/index.php?item=fmContactService" target="_blank"><span style="#FF9900;text-decoration:underline;">聯絡客服</span></a> 反應，樂分期將會為您處理。 樂分期購物網祝福您 順心如意!!</p>
							</td>
						</tr>
					</tbody>
				</table>
				';
			$send = $email->SendBCCEmail_smtp($receiverNameAndEmails1, "service@happyfan7.com", "樂分期", $title1, $content1);
		}else{
			$email = new Email();
			$receiverNameAndEmails2 = Array($memberData[0]['memAccount']=>$memberData[0]['memName']);
			$title1 = "【樂分期購物網】您訂購的商品審核中(訂單編號: ".$or_data[0]['orCaseNo'].")";
			$content1 = '	
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
								<p>親愛的<span style="color:#FF9900;">'.$_SESSION['shopping_user'][0]['memName'].' </span> 先生/小姐，您好：</p>
								<p style="color:red;">下單當日請注意手機！！平日若超過2天未接獲電話，麻煩請洽客服人員並告知申請人可接電話時間。
國定例假日下單案件較多，若無接到照會電話屬正常，若連假後仍無接獲電話亦可主動與客服聯絡，感謝您！</p>
							</td>
						</tr>
						<tr>
									<td style="font-weight:bold;background-color:#F5F3F1;">
										您所訂購的商品審核中，您此次訂購的商品明細如下：<br>

										訂單編號：'.$or_data[0]['orCaseNo'].'<br>

										訂購日期：'.$or_data[0]['orDate'].'<br> 
										
										訂購商品：'.$_SESSION['shopping_product'][0]['proName'].'<br> 

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
				</table>
				';
			$send = $email->SendBCCEmail_smtp($receiverNameAndEmails2, "service@happyfan7.com", "樂分期", $title1, $content1);
			
		}*/
		
		
		if($memberData[0]['memRecommCode'] != ""){
            //訂單編號取得單一獎金
			$re_data = $re->getOneRBAByOrNo($_SESSION['ord_code']);

			if($re_data[0]['rbaRecMemNo'] == ''){
			    //得到推薦獎金會員紀錄
				$redata = $re->getOneRBAByOrNoRBAByMemNo($memberData[0]['memRecommCode'],$memberData[0]['memNo']);
				if(count($redata) != 1){
					$array['orNo'] = $_SESSION['ord_code'];
					$array['rbaRecMemNo'] = $memberData[0]['memRecommCode'];
					$array['rbaStatus'] = 0;
					$array['rbamemNo'] = $memberData[0]['memNo'];
					$re->insert($array);	
				}
			}
		}
		
		unset($_SESSION['ord_code']);
		unset($_SESSION['shopping_user']['0']);
		unset($_SESSION['shopping_product']['0']);	
		echo true;
	}else{
		echo false;
	}
	
?>