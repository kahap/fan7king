<?php
$url = "http://api.21-finance.com/api/mail";
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
								<p>親愛的<span style="color:#FF9900;">測試人員 </span> 先生/小姐，您好：</p>
							</td>
						</tr>
						<tr>
							<td style="font-weight:bold;background-color:#F5F3F1;">
								<p>親愛的顧客您好,樂分期網站通知您購買訂單編號A201806255001，本公司審核已核准通過，您的商品將在2-5天送達，後續出貨狀態請至 <span style="color:red;">[會員中心]</span>查詢。</p>
								<p>
									您此次訂購的商品明細如下：<br>
									訂購日期：2018-06-20<br>
									訂購商品：天使洪<br>
									商品規格：安安你好<br>
								</p>
								<p>
									請確認收貨人姓名、地址是否有誤：<br>
									收貨人：測試一下<br>
									收貨地址：大安區101號<br>
								</p>
								<p>
									感謝您的支持，如有疑問歡迎到 <a href="https://happyfan7.com/?item=fmContactService" target="_blank">聯絡客服</a> 反應，樂分期將會為您處理。如需訂購其他商品請至 <a href="https://happyfan7.com/" target="_blank">樂分期購物網</a>選購。
								</p>
							</td>
						</tr>
					</tbody>
				</table>';

$receiverNameAndEmails = Array('sander0127@yahoo.com.tw'=>'sander');
$address = implode(", ",array_keys($receiverNameAndEmails));

$context = stream_context_create(array(
    'http' => array(
        'method' => 'POST',
        'header' => 'Content-type: application/x-www-form-urlencoded',
        'content' => http_build_query(
            array("Address"=>$address, "Subject"=>"[未進件]樂分期通知信", "Content"=>$content,"ApiKey"=>"說好的瑪莎拉蒂勒")
        ),
        'timeout' => 60
    )
));

$resp = file_get_contents($url, FALSE, $context);
print_r($resp);
?>