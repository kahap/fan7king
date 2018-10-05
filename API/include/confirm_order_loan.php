<?php
$mem = new Member();
$or = new Orders();
$pm = new Product_Manage();
$p = new Product();
$re = new Recomm_Bonus_Apply();
$mco = new API("motorbike_cellphone_orders");

if(isset($_POST["mcoNo"]) && $_POST["mcoNo"] != ""){
	$mco->getOne($_POST["mcoNo"]);
	$or_data = $mco->getData();
	$memberData = $mem->getOneMemberByNo($or_data[0]["memNo"]);
	$memNo = $or_data[0]["memNo"];
	
	//新增通話紀錄
	if(isset($_POST["pcName"],$_POST["pcNumer"],$_POST["pcStatus"],$_POST["pcTime"])){
		$pc = new API("phone_call");
		$nameArr = $_POST["pcName"];
		$numberArr = $_POST["pcNumer"];
		$statsArr = $_POST["pcStatus"];
		$timeArr = $_POST["pcTime"];
	
		if(is_array($nameArr) && !empty(array_filter($nameArr))){
			foreach($nameArr as $key=>$value){
				$pcArray["pcName"] = $value;
				$pcArray["pcNumer"] = $numberArr[$key];
				$pcArray["pcStatus"] = $statsArr[$key];
				$pcArray["pcTime"] = $timeArr[$key];
				$pcArray["memNo"] = $memNo;
				$pc->insert($pcArray);
			}
		}
	}

	//不可更改訂單
	$orIfEditable = 1;
	//進見狀態
	if($or_data[0]["mcoStatus"] == "5"){
		$mco->update(array("mcoStatus"=>"6"),$_POST["mcoNo"]);
		$str_title = '補件';
	}else{
		/*fb進件模式*/
		if($memberData[0]['memClass'] == '0' && $memberData[0]['memFBtoken'] != ''){
			if($memberData[0]['memEmailAuthen'] == '0' || $memberData[0]['memEmailAuthen'] == '') {
				$mco->update(array("mcoStatus"=>"0"),$_POST["mcoNo"]);
				$str_title = '已下單，Email未驗證';
			}elseif($memberData[0]['memEmailAuthen'] == '1'){
				$mco->update(array("mcoStatus"=>"1"),$_POST["mcoNo"]);
				$str_title = '未進件';
			}
		}elseif($memberData['0']['memFBtoken'] != ''){
			$mco->update(array("mcoStatus"=>"1"),$_POST["mcoNo"]);
			$str_title = '未進件';
		}
		/*一般帳號*/
		if($memberData[0]["memClass"] == '0' && $memberData[0]['memFBtoken'] == ''){
			if($memberData[0]['memEmailAuthen'] == '0' || $memberData[0]['memEmailAuthen'] == '') {
				$mco->update(array("mcoStatus"=>"0"),$_POST["mcoNo"]);
				$str_title = '已下單，Email未驗證';
			}elseif($memberData[0]['memEmailAuthen'] == '1'){
				$mco->update(array("mcoStatus"=>"1"),$_POST["mcoNo"]);
				$str_title = '未進件';
			}
		}else if($memberData['0']['memFBtoken'] == ''){
			$mco->update(array("mcoStatus"=>"1"),$_POST["mcoNo"]);
			$str_title = '未進件';
		}
	}

	$mco->update(array("mcoIfEditable"=>$orIfEditable),$_POST["mcoNo"]);

 	$Class = ($memberData[0]['memClass'] == '0') ? '學生':'非學生';
 	$email = new Email();
	
 	if($memberData[0]['memEmailAuthen'] == '0' && $memberData[0]['memClass'] == '0'){
 		$receiverNameAndEmails = Array('service@happyfan7.com'=>"EC部");
 	}else{
 		if($or_data[0]["mcoStatus"] == "5"){
 			$receiverNameAndEmails = Array('service@happyfan7.com'=>"EC部","happyfan7@21-finance.com"=>"客服部","sinlenlin@gmail.com"=>"林青嵐","andy_kuo@21-finance.com"=>"郭原彰","dan_chang@21-finance.com"=>"客服1");
 		}else{
 			$receiverNameAndEmails = Array('service@happyfan7.com'=>"EC部","happyfan7@21-finance.com"=>"客服部","sinlenlin@gmail.com"=>"林青嵐","achappyfan7@gmail.com"=>"Allan","andy_kuo@21-finance.com"=>"郭原彰","dan_chang@21-finance.com"=>"客服1","tom200e@yahoo.com.tw"=>"tom");
 		}
 	}
 	$title = "【樂分期-手機/機車 ".$str_title."】".$or_data[0]['mcoCaseNo'].",流水號:".$_POST["mcoNo"].",".$memberData[0]['memName']."先生/小姐,訂單編號:".$or_data[0]['mcoCaseNo'];
 	$content = '<table width="660" align="center" cellpadding="10" cellspacing="1" style="border:3px solid #999;">
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
 								【樂分期-手機/機車進件通知 此客戶來自APP】<br>

 								身份證字號：'.$memberData[0]['memIdNum'].'<br>
								
 								身份：'.$Class.'<br>

 								訂單狀態：'.$str_title.'<br>

 								商品明細如下：<br>

 								訂單編號：'.$or_data[0]['mcoCaseNo'].'<br>

 								下單日期：'.$or_data[0]['mcoDate'].'<br>

 								貸款類型：'.(($or_data[0]['mcoType'] == "1") ? "手機":"機車").'<br>

 								申貸金額'.$or_data[0]['mcoPeriodTotal'].'<BR>

 								期數：'.$or_data[0]['mcoPeriodAmount'].' 期<BR>

 								每期金額：'.number_format($or_data[0]['mcoMinMonthlyTotal']).' 元<BR>


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
// 	$email1 = new Email();
// 	if($memberData[0]['memEmailAuthen'] == 0 && $memberData[0]['memClass'] == '0'){
// 		//推播
// 		$deviceTokenArr = array();
// 		$androidRigistIdArr = array();
// 		$msgArr = array();
// 		$otherInfoArr = array();
// 		$apiAd = new API("app_data");
// 		$apiAd->getUniqueDeviceToken($memNo);
// 		$data = $apiAd->getData();
// 		if($data != null){
// 			$msgArr[0] = "您訂購的商品 - ".$p_data[0]['proName']."，離完成訂單還差一步，要麻煩您去您的學校Email系統收信，請點選信中的超連結完成驗證，會跳出網頁顯示「驗證成功，請立即登入」，如仍未收到信或驗證失敗，請加入官方客服LINEID：@happyfan7主動與客服人員洽詢，謝謝！";
// 			$otherInfoArr[0] = $or_data[0]['mcoNo'];
// 			$pushTitle = "您的下單已完成，但需驗證您的Email。";
// 			$apiIc = new API("inform_center");
// 			$icDataInput = array(
// 				"memNo"=>$memNo,
// 				"mcoNo"=>$otherInfoArr[0],
// 				"icTitle"=>$pushTitle,
// 				"icContent"=>$msgArr[0],
// 				"icDate"=>date("Y-m-d H:i:s",time())
// 			);
// 			$apiIc->insert($icDataInput);
// 			foreach($data as $whichAd=>$valueAd){
// 				if($valueAd["adDeviceId"] != ""){
// 					if(strlen($valueAd["adDeviceId"]) <= 65){
// 						$deviceTokenArr[0][] = $valueAd["adDeviceId"];
// 					}else{
// 						$androidRigistIdArr[0][] = $valueAd["adDeviceId"];
// 					}
// 				}
// 			}
// 			push_android($androidRigistIdArr[0],$msgArr[0],$pushTitle,$otherInfoArr[0]);
// 			$push = push("happy.pem",'happy',$deviceTokenArr,$msgArr,$otherInfoArr);
// 		}
		
// 		$receiverNameAndEmails1 = Array($memberData[0]['memAccount']=>$memberData[0]['memName']);
// 		$title1 = "【樂分期購物網】學校Email認證信件";
// 		$content1 = '
// 					<table width="660" align="center" cellpadding="10" cellspacing="1" style="border:3px solid #999;">
// 						<tbody>
// 							<tr>
// 								<td style="text-align:center;">
// 									<img src="https://happyfan7.com/assets/images/logo_2.png" />
// 								</td>
// 							</tr>
// 							<tr>
// 								<td style="color:#FF3333;font-weight:bold;text-align:center;">此為系統自動通知信，請勿直接回信！</td>
// 							</tr>
// 							<tr>
// 								<td style="color:black;font-weight:bold;">
// 									<p>親愛的<span style="color:#FF9900;">'.$memberData[0]['memName'].' </span> 先生/小姐，您好：</p>
// 								</td>
// 							</tr>
// 							<tr>
// 								<td style="font-weight:bold;background-color:#F5F3F1;">
// 									<p>這封認證信是由<span style="color:#0006FF;text-decoration:underline;">樂分期購物網</span>所發出，<span style="color:red">請點選下面鏈結</span>開通您的會員帳號，您將享受樂分期購物網提供的會員購物服務。</p>
// 									<p>
// 										<a href=https://happyfan7.com/php/member_id.php?pass_number='.$memberData[0]['pass_number'].'&memNo='.$memberData[0]['memNo'].'>https://happyfan7.com/php/member_id.php?id='.$memberData[0]['pass_number'].'</a>
// 									</p>
// 									<p>若此帳號並非您本人所申請，請您不須理會此會員確認信函。 感謝您的支持，如有疑問歡迎到 <a href="https://happyfan7.com/index.php?item=fmContactService" target="_blank"><span style="#FF9900;text-decoration:underline;">聯絡客服</span></a> 反應，樂分期將會為您處理。 樂分期購物網祝福您 順心如意!!</p>
// 								</td>
// 							</tr>
// 						</tbody>
// 					</table>
// 					';
// 		$send1 = $email1->SendBCCEmail_smtp($receiverNameAndEmails1, "happyfan@happyfan7.com", "樂分期", $title1, $content1);
// 	}

// 	if($memberData[0]['memRecommCode'] != ""){
// 		$re_data = $re->getOneRBAByOrNo($_POST["mcoNo"]);
// 		if($re_data[0]['rbaRecMemNo'] == ''){
// 			$redata = $re->getOneRBAByOrNoRBAByMemNo($memberData[0]['memRecommCode'],$memberData[0]['memNo']);
// 			if(count($redata) != 1){
// 				$array['mcoNo'] = $_POST["mcoNo"];
// 				$array['rbaRecMemNo'] = $memberData[0]['memRecommCode'];
// 				$array['rbaStatus'] = 0;
// 				$array['rbamemNo'] = $memberData[0]['memNo'];
// 				$re->insert($array);
// 			}
// 		}
// 	}

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