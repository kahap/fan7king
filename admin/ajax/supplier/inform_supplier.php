<?php
	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../model/require_login.php');
	
	$or = new Orders();
	$pro = new Product();
	$pm = new Product_Manage();
	$mem = new Member();
	$sup = new Supplier();
	
	$allSupData = $sup->getAllSupplier();
	
	date_default_timezone_set('Asia/Taipei');
	$availDate = date('Y-m-d', time()-(8*86400));
	
	foreach($allSupData as $key=>$value){
		$supEmailAddr = $value["supEmail"];
		$ecEmailAddr = "service@happyfan7.com";
		
		//收貨日期
		$orGetFromSupData = $or->getOrdersForSupplierPayment($availDate, $value["supNo"]);
		if($orGetFromSupData != null){
			$title = "【NoWait-到貨日期】提醒供應商「".$value['supName']."」，可請款共".sizeof($orGetFromSupData)."筆訂單(如為現金交易，則無需理會此信)";
			
			//訂購商品內容
			$tableContent = "";
			foreach($orGetFromSupData as $keyIn=>$valueIn){
				$email = new Email();
				$email2 = new Email();
				$memData = $mem->getOneMemberByNo($valueIn["memNo"]);
				$pmData = $pm->getOnePMByNo($valueIn["pmNo"]);
				$proData = $pro->getOneProByNo($pmData[0]["proNo"]);
				
				$tableContent .= '<tr>
							<td style="border:1px solid #000;">'.$valueIn["orDate"].'</td>
							<td style="border:1px solid #000;">'.$valueIn["orCaseNo"].'</td>
							<td style="color:red;border:1px solid #000;">'.$valueIn["orHandleGetFromSupDate"].'</td>
							<td style="border:1px solid #000;">'.$memData[0]["memName"].'</td>
							<td style="border:1px solid #000;max-width:100px;">'.$proData[0]["proName"].'</td>
							<td style="border:1px solid #000;">'.$valueIn["orProSpec"].'</td>
							<td style="border:1px solid #000;">'.$valueIn["orAmount"].'</td>
							<td style="border:1px solid #000;">'.$valueIn["orSupPrice"].'</td>
						<tr>';
			}
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
								<p>親愛的<span style="color:#FF9900;">'.$value['supName'].' </span> 老闆，您好：</p>
							</td>
						</tr>
						<tr>
							<td style="font-weight:bold;background-color:#F5F3F1;">
								<p>
									今日為<span style="color:blue;">'.date('Y/m/d',time()).'</span>，以下訂單商品「到貨日期」已過7天猶豫期(如第7日為例假日可能須順延1天)，通知您可請款共 <b>'.sizeof($orGetFromSupData).'</b> 筆訂單，如下：
								</p>
								<p>
									<table style="border-collapse:collapse;width:100%;">
										<tr>
											<th style="border:1px solid #000;">訂單日期</th>
											<th style="border:1px solid #000;">訂單編號</th>
											<th style="color:red;border:1px solid #000;">到貨日期</th>
											<th style="border:1px solid #000;">訂購人</th>
											<th style="border:1px solid #000;">商品名稱</th>
											<th style="border:1px solid #000;">商品規格</th>
											<th style="border:1px solid #000;">數量</th>
											<th style="border:1px solid #000;">供貨價</th>
										</tr>
										'.$tableContent.'
									</table>
								</p>
								<p>
									請您儘速備妥相關請款資料與NoWait聯繫，謝謝您的配合！<br>
									<span style="color:red;">(如為現金交易，則無需理會此信)</span>
								</p>
							</td>
						</tr>
					</tbody>
				</table>
				';
			$sendSup = $email->SendEmail_smtp($supEmailAddr,$value['supName'], "happyfan7@21-finance.com", "NoWait", $title, $content);
			$sendEc = $email2->SendEmail_smtp($ecEmailAddr,$value['supName'], "happyfan7@21-finance.com", "NoWait", $title, $content);
		}
		
		//換貨簽收
		$orChangeProData = $or->getOrdersForSupplierPaymentAfterProChange($availDate, $value["supNo"]);
		if($orChangeProData != null){
			$title = "【NoWait-換貨日期】提醒供應商「".$value['supName']."」，可請款共".sizeof($orChangeProData)."筆訂單(如為現金交易，則無需理會此信)";
			
			//訂購商品內容
			$tableContent = "";
			foreach($orChangeProData as $keyIn=>$valueIn){
				$email3 = new Email();
				$email4 = new Email();
				$memData = $mem->getOneMemberByNo($valueIn["memNo"]);
				$pmData = $pm->getOnePMByNo($valueIn["pmNo"]);
				$proData = $pro->getOneProByNo($pmData[0]["proNo"]);
				
				$tableContent .= '<tr>
							<td style="border:1px solid #000;">'.$valueIn["orDate"].'</td>
							<td style="border:1px solid #000;">'.$valueIn["orCaseNo"].'</td>
							<td style="color:red;border:1px solid #000;">'.$valueIn["orHandleChangeProDate"].'</td>
							<td style="border:1px solid #000;">'.$memData[0]["memName"].'</td>
							<td style="border:1px solid #000;max-width:100px;">'.$proData[0]["proName"].'</td>
							<td style="border:1px solid #000;">'.$valueIn["orProSpec"].'</td>
							<td style="border:1px solid #000;">'.$valueIn["orAmount"].'</td>
							<td style="border:1px solid #000;">'.$valueIn["orSupPrice"].'</td>
						<tr>';
			}
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
								<p>親愛的<span style="color:#FF9900;">'.$value['supName'].' </span> 老闆，您好：</p>
							</td>
						</tr>
						<tr>
							<td style="font-weight:bold;background-color:#F5F3F1;">
								<p>
									今日為<span style="color:blue;">'.date('Y/m/d',time()).'</span>，以下訂單商品「換貨簽收日期」已過7天猶豫期(如第7日為例假日可能須順延1天)，通知您可請款共 <b>'.sizeof($orChangeProData).'</b> 筆訂單，如下：
								</p>
								<p>
									<table style="border-collapse:collapse;width:100%;">
										<tr>
											<th style="border:1px solid #000;">訂單日期</th>
											<th style="border:1px solid #000;">訂單編號</th>
											<th style="color:red;border:1px solid #000;">換貨簽收日期</th>
											<th style="border:1px solid #000;">訂購人</th>
											<th style="border:1px solid #000;">商品名稱</th>
											<th style="border:1px solid #000;">商品規格</th>
											<th style="border:1px solid #000;">數量</th>
											<th style="border:1px solid #000;">供貨價</th>
										</tr>
										'.$tableContent.'
									</table>
								</p>
								<p>
									請您儘速備妥相關請款資料與NoWait聯繫，謝謝您的配合！<br>
									<span style="color:red;">(如為現金交易，則無需理會此信)</span>
								</p>
							</td>
						</tr>
					</tbody>
				</table>
				';
			$sendSup = $email3->SendEmail_smtp($supEmailAddr,$value['supName'], "happyfan7@21-finance.com", "NoWait", $title, $content);
			$sendEc = $email4->SendEmail_smtp($ecEmailAddr,$value['supName'], "happyfan7@21-finance.com", "NoWait", $title, $content);
		}
	}
	
?>