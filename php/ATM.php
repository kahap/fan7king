<?php
include('../model/php_model.php');
include('AllPay.Payment.Integration.php');
try
{
	 $oPayment = new AllInOne();
	 /* 服務參數 */
	 $oPayment->HashKey = HashKey;
	 $oPayment->HashIV = HashIV;
	 $oPayment->MerchantID =MerchantID;
	/* 取得回傳參數 */
	 $arFeedback = $oPayment->CheckOutFeedback();
	 /* 檢核與變更訂單狀態 */
	 if (sizeof($arFeedback) > 0) {
	 foreach ($arFeedback as $key => $value) {
		 switch ($key){
			 /* 支付後的回傳的基本參數 */
			 case "MerchantID": $szMerchantID = $value; break;
			 case "MerchantTradeNo": $szMerchantTradeNo = $value; break;
			 case "PaymentDate": $szPaymentDate = $value; break;
			 case "PaymentType": $szPaymentType = $value; break;
			 case "PaymentTypeChargeFee": $szPaymentTypeChargeFee = $value; break;
			 case "RtnCode": $szRtnCode = $value; break;
			 case "RtnMsg": $szRtnMsg = $value; break;
			 case "SimulatePaid": $szSimulatePaid = $value; break;
			 case "TradeAmt": $szTradeAmt = $value; break;
			 case "TradeDate": $szTradeDate = $value; break;
			 case "TradeNo": $szTradeNo = $value; break;
			 case "PayAmt": $szPayAmt = $value; break;
			 case "RedeemAmt": $szRedeemAmt = $value; break;
			 default: break;
			 }
	 }
	 $or_code = substr($_POST['MerchantTradeNo'],0,5);
	 $or = new Orders();
	 $member = new Member();
	 $email = new Email();
	 $pm = new Product_Manage();
	 $p = new Product();
	 
	 $or_data = $or->getOneOrderByNo($or_code);
	 $or->updateorPaySuccess($or_data[0]['orNo']);
	 $pm_data = $pm->getOnePMByNo($or_data[0]['pmNo']);
	 $p_data = $p->getOneProByNo($pm_data[0]['proNo']);
	 $member_data=$member->getOneMemberByNo($or_data[0]['memNo']);
	 $receiverNameAndEmails = Array($member_data[0]['memAccount']=>$member_data[0]['memName'],
									'service@happyfan7.com'=>"EC部",'lainelinlin@gmail.com'=>'客服人員C',"happyfan7@21-finance.com"=>"客服部","sinlenlin@gmail.com"=>"林青嵐");
									/*memSubEmail
								'hsiang_chou@21-finance.com'=>"客服部",
								'tiffany_chen@21-finance.com'=>"客服部",
								'service@happyfan7.com'=>"EC部"
		*/
			$title = "標題:【樂分期-直購訂單】".$or_data[0]['orDate'].",流水號:".$or_data[0]['orNo'].", ".$member_data[0]['memName']."先生/小姐,訂單編號:".$or_data[0]['orCaseNo'];
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
										<p>親愛的<span style="color:#FF9900;">'.$member_data[0]['memName'].' </span> 先生/小姐，您好：</p>
									</td>
								</tr>
								<tr>
									<td style="font-weight:bold;background-color:#F5F3F1;">
										【樂分期-進件通知】<br>

										訂單狀態：處理中<br>

										商品明細如下：<br>

										訂單編號：'.$or_data[0]['orCaseNo'].'<br>

										訂購日期：'.$or_data[0]['orDate'].'<br> 
										
										訂購商品：'.$p_data[0]['proName'].'<br> 

										商品規格：'.$or_data[0]['orProSpec'].'<BR>
										
										總金額：'. number_format($or_data[0]['orPeriodTotal']).'<BR>
										
										
									</td>
								</tr>
							</tbody>
						</table>';
			$send = $email->SendBCCEmail_smtp($receiverNameAndEmails, "happyfan@happyfan7.com", "樂分期", $title, $content);
			header('Location: ../index.php?item=member_center&action=order_direct&method=3');
	 } else {
		echo "交易失敗";
	 }
}
	catch (Exception $e)
	{
	 // 例外錯誤處理。
		
	}	 
?>