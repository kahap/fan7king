<?php
header ("content-type: text/HTML; charset=utf-8");
require_once(__DIR__.'/../../model/require_login.php');

$or = new Orders();
$pro = new Product();
$pm = new Product_Manage();
$mem = new Member();
$os = new Other_Setting();

$osData = $os->getAll();


//取出審查中的分期訂單
$allStat2Data = $or->getOneOrderByOrStatusAndMethod(2,1);


if($allStat2Data != null){
	$deviceTokenArr = array();
	$androidRigistIdArr = array();
	$androidDataArr = array();
	$msgArr = array();
	$otherInfoArr = array();
	$count = 0;
	foreach($allStat2Data as $key=>$value){
		$email = new Email();
		$memData = $mem->getOneMemberByNo($value["memNo"]);
		$pmData = $pm->getOnePMByNo($value["pmNo"]);
		$proData = $pro->getOneProByNo($pmData[0]["proNo"]);
		
		$emailAddr = "";
		if(trim($memData[0]["memSubEmail"]) != ""){
			$emailAddr = $memData[0]["memSubEmail"];
		}else{
			$emailAddr = $memData[0]["memAccount"];
		}
		
		$query = file_get_contents("http://api.21-finance.com/api/State/".$value["orInternalCaseNo"]);
		if($query != "\"審查中\""){
			if($query == "\"核准\""){
				$or->updateStatus(3, $value["orNo"]);
				$or->updateStatusTime(3,$value["orNo"]);
				$or->updateIfProcess(0, $value["orNo"]);
				$or->updateProcessTime("", $value["orNo"]);
				
				//推播
				$memNo = $value["memNo"];
				$api = new API("app_data");
				$api->getUniqueDeviceToken($memNo);
				$data = $api->getData();
				if($data != null){
					$msgArr[$count] = "您訂購的商品 - ".$proData[0]["proName"]."，已核准";
					$otherInfoArr[$count] = $value["orNo"];
					$apiIc = new API("inform_center");
					$icDataInput = array(
						"memNo"=>$memNo,
						"orNo"=>$otherInfoArr[$count],
						"icTitle"=>"訂單狀態更新通知",
						"icContent"=>$msgArr[$count],
						"icDate"=>date("Y-m-d H:i:s",time())
					);
					$apiIc->insert($icDataInput);
					foreach($data as $whichAd=>$valueAd){
						if($valueAd["adDeviceId"] != ""){
							if(strlen($valueAd["adDeviceId"]) <= 65){
								$deviceTokenArr[$count][] = $valueAd["adDeviceId"];
							}else{
								$androidRigistIdArr[$count][] = $valueAd["adDeviceId"];
							}
						}
					}
					push_android($androidRigistIdArr[$count],$msgArr[$count],"訂單狀態更新通知",$otherInfoArr[$count]);
					$count++;
				}
				
				if($osData[0]["textSwitch"] == 1){
					//簡訊
					$titleTxt = "【NoWait購物網】您訂購的商品分期已核准";
					$contentTxt = '【NoWait.com】通知您購買訂單編號'.$value["orCaseNo"].'，本公司審核已核准通過，後續出貨狀態請至[會員中心]查詢';
					
					$fp = fsockopen("xsms.aptg.com.tw", 80, $errno, $errstr, 30);
					if (!$fp){
						echo 'Could not open connection.';
					}else{
						$xmlpacket ='<soap-env:Envelope xmlns:soap-env="http://schemas.xmlsoap.org/soap/envelope/">
										 <soap-env:Header/>
										 <soap-env:Body>
										 <Request>
										 <MDN>0906815981</MDN>
										 <UID>0906815981</UID>
										 <UPASS>123456</UPASS>
										 <Subject>'.$titleTxt.'</Subject>
										 <Retry>Y</Retry>
										 <Message>'.$contentTxt.'</Message>
										 <MDNList><MSISDN>'.$memData[0]['memCell'].'</MSISDN></MDNList>
										 </Request>
										 </soap-env:Body>
										</soap-env:Envelope>';
						$contentlength = strlen($xmlpacket);
						$out = "POST /XSMSAP/api/APIRTFastRequest HTTP/1.1\r\n";
						$out .= "Host: 210.200.64.111\r\n";
						$out .= "Connection: close\r\n";
						$out .= "Content-type: text/xml;charset=utf-8\r\n";
						$out .= "Content-length: $contentlength\r\n\r\n";
						$out .= "$xmlpacket";
						fwrite($fp, $out);
							
						$theOutput = "";
							
						while (!feof($fp)){
							$theOutput .= fgets($fp, 128);
						}
							
						fclose($fp);
						// echo $theOutput is the response returned from the remote script
					}
				}
				
				
				$title = "【NoWait購物網】您訂購的商品分期已核准";
				
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
								<p>親愛的<span style="color:#FF9900;">'.$memData[0]['memName'].' </span> 先生/小姐，您好：</p>
							</td>
						</tr>
						<tr>
							<td style="font-weight:bold;background-color:#F5F3F1;">
								<p>親愛的顧客您好,NoWait網站通知您購買訂單編號'.$value["orCaseNo"].'，本公司審核已核准通過，您的商品將在2-5天送達，後續出貨狀態請至 <span style="color:red;">[會員中心]</span>查詢。</p>
								<p>
									您此次訂購的商品明細如下：<br>
									訂購日期：'.$value["orDate"].'<br>
									訂購商品：'.$proData[0]["proName"].'<br>
									商品規格：'.$value["orProSpec"].'<br>
								</p>
								<p>
									感謝您的支持，如有疑問歡迎到 <a href="https://nowait.shop/?item=fmContactService" target="_blank">聯絡客服</a> 反應，NoWait將會為您處理。如需訂購其他商品請至 <a href="https://nowait.shop/" target="_blank">NoWait購物網</a>選購。
								</p>
							</td>
						</tr>
					</tbody>
				</table>
				';
				$send = $email->SendEmail_smtp($emailAddr,$memData[0]['memName'], "service@nowait.shop", "NoWait", $title, $content);
			}else if($query == "\"婉拒\""){
				$or->updateStatus(4, $value["orNo"]);
				$or->updateStatusTime(4,$value["orNo"]);
				$or->updateIfProcess(0, $value["orNo"]);
				$or->updateProcessTime("", $value["orNo"]);
				
				//推播
				$memNo = $value["memNo"];
				$api = new API("app_data");
				$api->getUniqueDeviceToken($memNo);
				$data = $api->getData();
				if($data != null){
					$msgArr[$count] = "您訂購的商品 - ".$proData[0]["proName"]."，已婉拒無法通過";
					$otherInfoArr[$count] = $value["orNo"];
					$apiIc = new API("inform_center");
					$icDataInput = array(
						"memNo"=>$memNo,
						"orNo"=>$otherInfoArr[$count],
						"icTitle"=>"訂單狀態更新通知",
						"icContent"=>$msgArr[$count],
						"icDate"=>date("Y-m-d H:i:s",time())
					);
					$apiIc->insert($icDataInput);
					foreach($data as $whichAd=>$valueAd){
						if($valueAd["adDeviceId"] != ""){
							if(strlen($valueAd["adDeviceId"]) <= 65){
								$deviceTokenArr[$count][] = $valueAd["adDeviceId"];
							}else{
								$androidRigistIdArr[$count][] = $valueAd["adDeviceId"];
							}
						}
					}
					push_android($androidRigistIdArr[$count],$msgArr[$count],"訂單狀態更新通知",$otherInfoArr[$count]);
					$count++;
				}
				
				$title = "【NoWait購物網】您訂購的商品分期結果為婉拒無法通過";
				
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
								<p>親愛的<span style="color:#FF9900;">'.$memData[0]['memName'].' </span> 先生/小姐，您好：</p>
							</td>
						</tr>
						<tr>
							<td style="font-weight:bold;background-color:#F5F3F1;">
								<p>
									親愛的顧客您好,NoWait網站通知您購買訂單編號'.$value["orCaseNo"].'，依本公司審核結果無法通過，此筆訂單交易將不成立。
								</p>
								<p>
									您此次訂購的商品明細如下：<br>
									訂購日期：'.$value["orDate"].'<br>
									訂購商品：'.$proData[0]["proName"].'<br>
									商品規格：'.$value["orProSpec"].'<br>
								</p>
								<p>
									感謝您的支持，如有疑問歡迎到 <a href="https://nowait.shop/?item=fmContactService" target="_blank">聯絡客服</a> 反應，NoWait將會為您處理。如需訂購其他商品請至 <a href="https://nowait.shop/" target="_blank">NoWait購物網</a>選購。
								</p>
							</td>
						</tr>
					</tbody>
				</table>
				';
				$send = $email->SendEmail_smtp($emailAddr,$memData[0]['memName'], "service@nowait.shop", "NoWait", $title, $content);
			}else if($query == "\"客戶撤件\""){
				$or->updateStatus(7, $value["orNo"]);
				$or->updateStatusTime(7,$value["orNo"]);
				$or->updateIfProcess(0, $value["orNo"]);
				$or->updateProcessTime("", $value["orNo"]);
				
				//推播
				$memNo = $value["memNo"];
				$api = new API("app_data");
				$api->getUniqueDeviceToken($memNo);
				$data = $api->getData();
				if($data != null){
					$msgArr[$count] = "您訂購的商品 - ".$proData[0]["proName"]."，已取消訂單";
					$otherInfoArr[$count] = $value["orNo"];
					$apiIc = new API("inform_center");
					$icDataInput = array(
						"memNo"=>$memNo,
						"orNo"=>$otherInfoArr[$count],
						"icTitle"=>"訂單狀態更新通知",
						"icContent"=>$msgArr[$count],
						"icDate"=>date("Y-m-d H:i:s",time())
					);
					$apiIc->insert($icDataInput);
					foreach($data as $whichAd=>$valueAd){
						if($valueAd["adDeviceId"] != ""){
							if(strlen($valueAd["adDeviceId"]) <= 65){
								$deviceTokenArr[$count][] = $valueAd["adDeviceId"];
							}else{
								$androidRigistIdArr[$count][] = $valueAd["adDeviceId"];
							}
						}
					}
					push_android($androidRigistIdArr[$count],$msgArr[$count],"訂單狀態更新通知",$otherInfoArr[$count]);
					$count++;
				}
				
				$title = "【NoWait購物網】您訂購的商品已取消訂單(訂單編號: ".$value["orCaseNo"].")";
				
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
								<p>親愛的<span style="color:#FF9900;">'.$memData[0]['memName'].' </span> 先生/小姐，您好：</p>
							</td>
						</tr>
						<tr>
							<td style="font-weight:bold;background-color:#F5F3F1;">
								<p>
									您所訂購的商品已取消訂單，您此次訂購的商品明細如下：
								</p>
								<p>
									您此次訂購的商品明細如下：<br>
									訂單編號：'.$value["orCaseNo"].'<br>
									訂購日期：'.$value["orDate"].'<br>
									訂購商品：'.$proData[0]["proName"].'<br>
									商品規格：'.$value["orProSpec"].'<br>
								</p>
								<p>
									感謝您的支持，如有疑問歡迎到 <a href="https://nowait.shop/?item=fmContactService" target="_blank">聯絡客服</a> 反應，NoWait將會為您處理。如需訂購其他商品請至 <a href="https://nowait.shop/" target="_blank">NoWait購物網</a>選購。
								</p>
							</td>
						</tr>
					</tbody>
				</table>
				';
				$send = $email->SendEmail_smtp($emailAddr,$memData[0]['memName'], "service@nowait.shop", "NoWait", $title, $content);
			}
		}
	}
	$push = push("happy.pem",'happy',$deviceTokenArr,$msgArr,$otherInfoArr);
}

$allOrData = $or->getAllInternalCaseOr();

if($allOrData != null){
	foreach($allOrData as $key=>$value){
		$query = file_get_contents("http://api.21-finance.com/api/State/".$value["orInternalCaseNo"]);
		if($query == "\"已完成\""){
			if($value["orStatus"] != 10){
				$or->updateStatus(10, $value["orNo"]);
				$or->updateStatusTime(10,$value["orNo"]);
				$or->updateIfProcess(0, $value["orNo"]);
				$or->updateProcessTime("", $value["orNo"]);
			}
		}
	}
}

?>