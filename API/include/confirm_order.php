<?php
$mem = new Member();
$or = new Orders();
$pm = new Product_Manage();
$p = new Product();
$re = new Recomm_Bonus_Apply();

if(isset($_POST["orNo"]) && $_POST["orNo"] != ""){
	$or_data = $or->getOneOrderByNo($_POST["orNo"]);
	$memberData = $mem->getOneMemberByNo($or_data[0]["memNo"]);
	$memNo = $or_data[0]["memNo"];

	//不可更改訂單
	$orIfEditable = 1;
	//進見狀態
	if($or_data[0]["orStatus"] == "5"){
		$or->updateStatus('6',$_POST["orNo"]);
		$or->updateStatusTime('6',$_POST["orNo"]);
		$str_title = '補件';
	}else{
		/*fb進件模式*/
		if($memberData[0]['memClass'] == '0' && $memberData[0]['memFBtoken'] != ''){
			if($memberData[0]['memEmailAuthen'] == '0' || $memberData[0]['memEmailAuthen'] == '') {
				$or->updateStatus('0',$_POST["orNo"]);
				$or->updateStatusTime('0',$_POST["orNo"]);
				$str_title = '已下單，Email未驗證';
			}elseif($memberData[0]['memEmailAuthen'] == '1'){
				$or->updateStatus('1',$_POST["orNo"]);
				$or->updateStatusTime('1',$_POST["orNo"]);
				$str_title = '未進件';
			}
		}elseif($memberData['0']['memFBtoken'] != ''){
			$or->updateStatus('1',$_POST["orNo"]);
			$or->updateStatusTime('1',$_POST["orNo"]);
			$str_title = '未進件';
		}
		/*一般帳號*/
		if($memberData[0]["memClass"] == '0' && $memberData[0]['memFBtoken'] == ''){
			if($memberData[0]['memEmailAuthen'] == '0' || $memberData[0]['memEmailAuthen'] == '') {
				$or->updateStatus('0',$_POST["orNo"]);
				$or->updateStatusTime('0',$_POST["orNo"]);
				$str_title = '已下單，Email未驗證';
			}elseif($memberData[0]['memEmailAuthen'] == '1'){
				$or->updateStatus('1',$_POST["orNo"]);
				$or->updateStatusTime('1',$_POST["orNo"]);
				$str_title = '未進件';
			}
		}else if($memberData['0']['memFBtoken'] == ''){
			$or->updateStatus('1',$_POST["orNo"]);
			$or->updateStatusTime('1',$_POST["orNo"]);
			$str_title = '未進件';
		}
	}

	$or->updateorIfEditable($orIfEditable,$_POST["orNo"]);

	$pmBuyAmnt = $pm->getOnePMByNo($or_data[0]['pmNo']);

	$pm->updatepmBuyAmnt($or_data[0]['pmNo'],$pmBuyAmnt[0]['pmBuyAmnt']);
	$p_data = $p->getOneProByNo($pmBuyAmnt[0]['proNo']);
	$Class = ($memberData[0]['memClass'] == '0') ? '學生':'非學生';
	
	
	
	if($memberData[0]['memEmailAuthen'] == '0' && $memberData[0]['memClass'] == '0'){
		$receiverNameAndEmails = Array('service@nowait.shop'=>"EC部");
	}elseif($or_data[0]['orStatus'] == '5'){
		$receiverNameAndEmails = Array('service@nowait.shop'=>"EC部","happyfan7@21-finance.com"=>"客服部","sinlenlin@gmail.com"=>"林青嵐","andy_kuo@21-finance.com"=>"郭原彰","dan_chang@21-finance.com"=>"客服1",'aa22760676@gmail.com'=>'客服人員D');
	}else{
		$receiverNameAndEmails = Array('service@nowait.shop'=>"EC部","happyfan7@21-finance.com"=>"客服部","sinlenlin@gmail.com"=>"林青嵐","andy_kuo@21-finance.com"=>"郭原彰","dan_chang@21-finance.com"=>"客服1","achappyfan7@gmail.com"=>"allan","tom200e@yahoo.com.tw"=>"tom");
	}
	$title = "【NoWait-".$str_title."】".$or_data[0]['orDate'].",流水號:".$_POST["orNo"].",".$memberData[0]['memName']."先生/小姐,訂單編號:".$or_data[0]['orCaseNo'];
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
								【NoWait-進件通知 此客戶來自APP】<br>

								身份證字號：'.$memberData[0]['memIdNum'].'<br>
								
								身份：'.$Class.'<br>

								訂單狀態：'.$str_title.'<br>

								商品明細如下：<br>

								訂單編號：'.$or_data[0]['orCaseNo'].'<br>

								訂購日期：'.$or_data[0]['orDate'].'<br>

								訂購商品：'.$p_data[0]['proName'].'<br>

								商品規格：'.$or_data[0]['orProSpec'].'<BR>

								分期期數：'.$or_data[0]['orPeriodAmnt'].' 期<BR>

								每期金額：'.number_format($or_data[0]["orPeriodTotal"]/$or_data[0]["orPeriodAmnt"]).' 元<BR>


							</td>
						</tr>
					</tbody>
				</table>';
	$email = new Email();			
	$send = $email->SendBCCEmail_smtp($receiverNameAndEmails, "happyfan@nowait.shop", "NoWait", $title, $content);
			/*if(in_array("allan",$receiverNameAndEmails)){
				$ch = curl_init("http://nowait.shop/php/index.php?inst=happyfan7&msg=".str_replace(" ","_",$title));
				curl_setopt($ch, CURLOPT_HTTPHEADER, false);
				$result = curl_exec($ch);
				curl_close($ch);
			}*/
	
	$email1 = new Email();
	if($memberData[0]['memEmailAuthen'] == 0 && $memberData[0]['memClass'] == '0'){
		//推播
		$deviceTokenArr = array();
		$androidRigistIdArr = array();
		$msgArr = array();
		$otherInfoArr = array();
		$apiAd = new API("app_data");
		$apiAd->getUniqueDeviceToken($memNo);
		$data = $apiAd->getData();
		if($data != null){
			$msgArr[0] = "您訂購的商品 - ".$p_data[0]['proName']."，離完成訂單還差一步，要麻煩您去您的學校Email系統收信，請點選信中的超連結完成驗證，會跳出網頁顯示「驗證成功，請立即登入」，如仍未收到信或驗證失敗，請加入官方客服LINEID：@happyfan7主動與客服人員洽詢，謝謝！";
			$otherInfoArr[0] = $or_data[0]['orNo'];
			$pushTitle = "您的下單已完成，但需驗證您的Email。";
			$apiIc = new API("inform_center");
			$icDataInput = array(
				"memNo"=>$memNo,
				"orNo"=>$otherInfoArr[0],
				"icTitle"=>$pushTitle,
				"icContent"=>$msgArr[0],
				"icDate"=>date("Y-m-d H:i:s",time())
			);
			$apiIc->insert($icDataInput);
			foreach($data as $whichAd=>$valueAd){
				if($valueAd["adDeviceId"] != ""){
					if(strlen($valueAd["adDeviceId"]) <= 65){
						$deviceTokenArr[0][] = $valueAd["adDeviceId"];
					}else{
						$androidRigistIdArr[0][] = $valueAd["adDeviceId"];
					}
				}
			}
			push_android($androidRigistIdArr[0],$msgArr[0],$pushTitle,$otherInfoArr[0]);
			$push = push("happy.pem",'happy',$deviceTokenArr,$msgArr,$otherInfoArr);
		}
		
		$receiverNameAndEmails1 = Array($memberData[0]['memAccount']=>$memberData[0]['memName']);
		$title1 = "【NoWait購物網】學校Email認證信件";
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
									<p>親愛的<span style="color:#FF9900;">'.$memberData[0]['memName'].' </span> 先生/小姐，您好：</p>
								</td>
							</tr>
							<tr>
								<td style="font-weight:bold;background-color:#F5F3F1;">
									<p>這封認證信是由<span style="color:#0006FF;text-decoration:underline;">NoWait購物網</span>所發出，<span style="color:red">請點選下面鏈結</span>開通您的會員帳號，您將享受NoWait購物網提供的會員購物服務。</p>
									<p>
										<a href=https://nowait.shop/php/member_id.php?pass_number='.$memberData[0]['pass_number'].'&memNo='.$memberData[0]['memNo'].'>https://nowait.shop/php/member_id.php?id='.$memberData[0]['pass_number'].'</a>
									</p>
									<p>若此帳號並非您本人所申請，請您不須理會此會員確認信函。 感謝您的支持，如有疑問歡迎到 <a href="https://nowait.shop/index.php?item=fmContactService" target="_blank"><span style="#FF9900;text-decoration:underline;">聯絡客服</span></a> 反應，NoWait將會為您處理。 NoWait購物網祝福您 順心如意!!</p>
								</td>
							</tr>
						</tbody>
					</table>
					';
		$send1 = $email1->SendBCCEmail_smtp($receiverNameAndEmails1, "happyfan@nowait.shop", "NoWait", $title1, $content1);
	}

	if($memberData[0]['memRecommCode'] != ""){
		$re_data = $re->getOneRBAByOrNo($_POST["orNo"]);
		if($re_data[0]['rbaRecMemNo'] == ''){
			$redata = $re->getOneRBAByOrNoRBAByMemNo($memberData[0]['memRecommCode'],$memberData[0]['memNo']);
			if(count($redata) != 1){
				$array['orNo'] = $_POST["orNo"];
				$array['rbaRecMemNo'] = $memberData[0]['memRecommCode'];
				$array['rbaStatus'] = 0;
				$array['rbamemNo'] = $memberData[0]['memNo'];
				$re->insert($array);
			}
		}
	}
	
	$output = array();
	if($memberData[0]['memClass'] == "0"){
		$output["isStudent"] = true;
		$output["memEmailAuthen"] = $memberData[0]["memEmailAuthen"];
	}else{
		$output["isStudent"] = false;
		$output["memEmailAuthen"] = "";
	}

	$api->setInformation($output, 1, 1, "完成下單，我們將會盡快與您聯絡！");
}else{
	$api->setInformation(false, 0, 0, "請帶入訂單編號");
}
$api->setResult();
?>