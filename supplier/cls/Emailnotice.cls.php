<?

//class Emailnotice extends Email{
class Emailnotice{
	//專案管理員帳號開通 通知信
	public function create_company_success( $company_name,
						$user_name,
						$user_password,
						$company_email,
						$admin_email){
		$company_name 		= mysqli_real_escape_string($this->db->oDbLink, $company_name);
		$user_name 		= mysqli_real_escape_string($this->db->oDbLink, $user_name);
		$user_password 		= mysqli_real_escape_string($this->db->oDbLink, $user_password);
		$company_email 		= mysqli_real_escape_string($this->db->oDbLink, $company_email);
		$admin_email 		= mysqli_real_escape_string($this->db->oDbLink, $admin_email);	
		include("email/class.phpmailer.php");
			// 產生 Mailer 實體
			$mail = new PHPMailer();		
			// 設定為 SMTP 方式寄信
			$mail->IsSMTP();			
			// SMTP 伺服器的設定，以及驗證資訊
			$mail->SMTPAuth = true;      
			$mail->SMTPSecure = "ssl";    
			$mail->Host = "smtp.gmail.com";
			$mail->Port = 465;
			// 信件內容的編碼方式       
			$mail->CharSet = "utf-8";
			
			// 信件處理的編碼方式
			$mail->Encoding = "base64";
			
			// SMTP 驗證的使用者資訊
			$mail->Username = "ss910316@gmail.com";
			$mail->Password = "moq0w1e2r3";     
			
			// 信件內容設定  
			$mail->From = $admin_email;
			$mail->FromName = 'sunnycar資訊';
			$mail->Subject = '[重要] '.$user_name.' 使用者問題回報'; 
			$mail->Body = '	
				<table width="660" align="center" cellpadding="10" cellspacing="1" style="border:3px solid #999;">
					<tbody>
						<tr>
							<td style="color:#FF3333;font-weight:bold;text-align:center;">此為系統自動通知信</td>
						</tr>
						<tr>
							<td style="color:black;font-weight:bold;">
								<p>親愛的<span style="color:#FF9900;">管理員 </span> 您好，以下是使用者問題回報資訊:</p>
							</td>
						</tr>
						<tr>
							<td style="color:#black;font-weight:bold;text-align:center;background-color:#F5F3F1;">
								<p>主題：<span style="color:#3333CC">'.$company_name.'</span></p>
								<p>使用者名稱：<span style="color:#3333CC">'.$user_name.'</span></p>
								<p>使用者電子郵件是：<span style="color:#3333CC">'.$company_email.'</span></p>
								<p>訊息：<span style="color:#3333CC">'.$user_password.'</span></p>								
							</td>
						</tr>
					</tbody>
				</table>
				';
			$mail->IsHTML(true);
			$mail->AddAddress($admin_email, $user_name);
			
			if(!$mail->Send()) {     
				return  $status['error'] = "Mail error: " . $mail->ErrorInfo;     
			}else {     
				return true;     
			} 
	}
	
	public function create_user_success( $company_name,
						$user_name,
						$user_password,
						$company_email,
						$admin_email){
		$company_name 		= mysqli_real_escape_string($this->db->oDbLink, $company_name);
		$user_name 		= mysqli_real_escape_string($this->db->oDbLink, $user_name);
		$user_password 		= mysqli_real_escape_string($this->db->oDbLink, $user_password);
		$company_email 		= mysqli_real_escape_string($this->db->oDbLink, $company_email);
		$admin_email 		= mysqli_real_escape_string($this->db->oDbLink, $admin_email);	
		include("email/class.phpmailer.php");
			// 產生 Mailer 實體
			$mail = new PHPMailer();		
			// 設定為 SMTP 方式寄信
			$mail->IsSMTP();			
			// SMTP 伺服器的設定，以及驗證資訊
			$mail->SMTPAuth = true;      
			$mail->SMTPSecure = "ssl";    
			$mail->Host = "smtp.gmail.com";
			$mail->Port = 465;
			// 信件內容的編碼方式       
			$mail->CharSet = "utf-8";
			
			// 信件處理的編碼方式
			$mail->Encoding = "base64";
			
			// SMTP 驗證的使用者資訊
			$mail->Username = "tom200e@gmail.com";
			$mail->Password = "F127332338";     
			
			// 信件內容設定  
			$mail->From = $admin_email;
			$mail->FromName = '三聯科技';
			$mail->Subject = '[重要] '.$company_name.' 使用者開通成功'; 
			$mail->Body = '	
				<table width="660" align="center" cellpadding="10" cellspacing="1" style="border:3px solid #999;">
					<tbody>
						<tr>
							<td style="color:#FF3333;font-weight:bold;text-align:center;">此為系統自動通知信，請勿直接回信！</td>
						</tr>
						<tr>
							<td style="color:black;font-weight:bold;">
								<p>親愛的<span style="color:#FF9900;">'.$company_name.' </span> 使用者您好，以下是您的註冊資訊，請妥善保存:</p>
							</td>
						</tr>
						<tr>
							<td style="color:#black;font-weight:bold;text-align:center;background-color:#F5F3F1;">
								<p>專案名稱是：<span style="color:#3333CC">'.$user_name.'</span></p>
								<p>您的使用者帳號是：<span style="color:#3333CC">'.$company_email.'</span></p>
								<p>您的使用者密碼是：<span style="color:#3333CC">'.$user_password.'</span></p>
								<p>您的專案登入方式請依照此網址：<span style="color:#3333CC"><a href="'.INDEX.'sanlien/?login=project_user">點此連結</a></span></p>
							</td>
						</tr>
						<tr>
							<td style="color:#black;font-weight:bold;">
								<p>如還有疑問歡迎隨時跟我們聯絡</p>
								<p>連絡電話 +886 (02)2915-3680</p>
								<p><a href="http://www.sanlien.com/web/homepage.nsf/sanlien-index?openform" target="_blank">問題回報 http://www.sanlien.com</a></p>
							</td>
						</tr>
					</tbody>
				</table>
				';
			$mail->IsHTML(true);
			$mail->AddAddress($company_email, $user_name);
			
			if(!$mail->Send()) {     
				return  $status['error'] = "Mail error: " . $mail->ErrorInfo;     
			}else {     
				return true;     
			} 
	}
}

?>