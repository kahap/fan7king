<?
class Allpay{
	private $_MerchantID;
	private $_HashKey;
	private $_HashIV;
	private $ALLSubPayment = array();
	private $auto_submit = false;
	
	function __construct($MerchantID,$HashKey,$HashIV){
		if (isset($MerchantID) && $MerchantID != '') $this->_MerchantID = $MerchantID;
		if (isset($HashKey) && $HashKey != '') $this->_HashKey = $HashKey;
		if (isset($HashIV) && $HashIV != '') $this->_HashIV = $HashIV;
		/*	
		WebATM
			TAISHIN WebATM_台新
			ESUN WebATM_玉山
			HUANAN WebATM_華南
			BOT WebATM_台灣銀行
			FUBON WebATM_台北富邦
			CHINATRUST WebATM_中國信託
			FIRST WebATM_第一銀行
			CATHAY WebATM_國泰世華
			MEGA WebATM_兆豐銀行
			YUANTA WebATM_元大銀行
			LAND WebATM_土地銀行
		ATM
			TAISHIN ATM_台新
			ESUN ATM_玉山
			HUANAN ATM_華南
			BOT ATM_台灣銀行
			FUBON ATM_台北富邦
			CHINATRUST ATM_中國信託
			FIRST ATM_第一銀行
		CVS
			CVS 超商代碼繳款
			OK OK 超商代碼繳款
			FAMILY 全家超商代碼繳款
			HILIFE 萊爾富超商代碼繳款
			IBON 7-11 ibon 代碼繳款
		BARCODE
			BARCODE 超商條碼繳款
		*/
		$this->ALLSubPayment['WebATM']['TAISHIN'] = '台新銀行';
		$this->ALLSubPayment['WebATM']['ESUN'] = '玉山銀行';
		$this->ALLSubPayment['WebATM']['HUANAN'] = '華南銀行';
		$this->ALLSubPayment['WebATM']['BOT'] = '台灣銀行';
		$this->ALLSubPayment['WebATM']['FUBON'] = '台北富邦';
		$this->ALLSubPayment['WebATM']['CHINATRUST'] = '中國信託';
		$this->ALLSubPayment['WebATM']['FIRST'] = '第一銀行';
		$this->ALLSubPayment['WebATM']['CATHAY'] = '國泰銀行';
		$this->ALLSubPayment['WebATM']['MEGA'] = '兆豐銀行';
		$this->ALLSubPayment['WebATM']['YUANTA'] = '元大銀行';
		$this->ALLSubPayment['WebATM']['LAND'] = '土地銀行';
		$this->ALLSubPayment['ATM']['TAISHIN'] = '台新銀行';
		$this->ALLSubPayment['ATM']['ESUN'] = '玉山銀行';
		$this->ALLSubPayment['ATM']['HUANAN'] = '華南銀行';
		$this->ALLSubPayment['ATM']['BOT'] = '台灣銀行';
		$this->ALLSubPayment['ATM']['FUBON'] = '富邦銀行';
		$this->ALLSubPayment['ATM']['CHINATRUST'] = '中國信託';
		$this->ALLSubPayment['ATM']['FIRST'] = '第一銀行';
		$this->ALLSubPayment['CVS']['CVS'] = '超商代碼繳款';
		$this->ALLSubPayment['CVS']['OK'] = 'OK 超商代碼繳款';
		$this->ALLSubPayment['CVS']['FAMILY'] = '全家超商代碼繳款';
		$this->ALLSubPayment['CVS']['HILIFE'] = '萊爾富超商代碼繳款';
		$this->ALLSubPayment['CVS']['IBON'] = '7-11 ibon 代碼繳款';
		$this->ALLSubPayment['BARCODE']['BARCODE'] = '超商條碼繳款';
	}
	
	
	
	/*	建立收款連結(傳入值是陣列參數如下)
		param array
		 key => MerchantTradeNo		訂單標號
		 	ChoosePayment		付款金流 如 銀行,ATM,便利超商
		 	ChooseSubPayment	付款金流商 如 玉山,全家,萊爾富
		 	TotalAmount		付款金額
			TradeDesc		交易描述
			ItemName		商品明細
			ReturnURL		付款完成回傳通知網址
			ClientBackURL		在建立訂單完成時回到商場的結連結
			OrderResultURL 		銀行付款完成結果頁 (WEB ATM)
			DeviceSource		顯示電腦版OR手機版的頁面 P = PC , M = MOBILE
			PaymentInfoURL		訂單建立完成時回傳訂單資料
		當 ChoosePayment = ATM 時
			ExpireDate		允許繳費期限 1~60  為空則3天
		當 ChoosePayment = CVS 或 BARCODE 時
			Desc_1			顯示在繳款聯上的交易備註
			Desc_2			顯示在繳款聯上的交易備註
			Desc_3			顯示在繳款聯上的交易備註
			Desc_4			顯示在繳款聯上的交易備註
		當 ChoosePayment = Credit 時
			PeriodReturnURL		訂單建立完成時回傳訂單資料
	*/
	public function getPayLink($param){
		if(!is_array($param)) return;
		$def_param = array();
		$def_param['MerchantID'] = $this->_MerchantID;
		$def_param['MerchantTradeNo'] = 'abcdeabcde' . time();
		$def_param['MerchantTradeDate'] = date('Y/m/d H:i:s',time());
		$def_param['PaymentType'] = 'aio';
		
		$def_param= $this->_extends($def_param,$param);
		$def_param['CheckMacValue'] = $this->checkCode($def_param);
		
		$output_html = $this->output($def_param);

		//echo $output_html;
		return $output_html;
	}
	
	/*	快速取單WEBATM (網路ATM)
		參數1-訂單參數
		參數2-子付款方式
	*/
	public function getWEBATM($param,$subPayment = ''){
		if(isset($this->ALLSubPayment['WebATM'][$subPayment])) $param['ChooseSubPayment'] = $subPayment;
		$param['ChoosePayment'] = 'WebATM';
		$html = $this->getPayLink($param);
		//echo $html;
		return $html;
	}
	
	
	/*	快速取單ATM (實體ATM,虛擬帳戶)
		參數1-訂單參數
		參數2-子付款方式
	*/
	public function getATM($param,$subPayment = ''){
		if(isset($this->ALLSubPayment['ATM'][$subPayment])) $param['ChooseSubPayment'] = $subPayment;
		$param['ChoosePayment'] = 'ATM';
		$html = $this->getPayLink($param);
		//echo $html;
		return $html;
	}
	
	
	/*	快速取單CVS (超商代碼)
		參數1-訂單參數
		參數2-子付款方式
	*/
	public function getCVS($param,$subPayment = ''){
		if(isset($this->ALLSubPayment['CVS'][$subPayment])) $param['ChooseSubPayment'] = $subPayment;
		$param['ChoosePayment'] = 'CVS';
		$html = $this->getPayLink($param);
		//echo $html;
		return $html;
	}
	
	
	/*	快速取單BARCODE (超商條碼)
		參數1-訂單參數
		參數2-子付款方式
	*/
	public function getBARCODE($param,$subPayment = ''){
		if(isset($this->ALLSubPayment['BARCODE'][$subPayment])) $param['ChooseSubPayment'] = $subPayment;
		$param['ChoosePayment'] = 'BARCODE';
		$html = $this->getPayLink($param);
		//echo $html;
		return $html;
	}
	
	/*	快速取單CREDIT (信用卡)
		參數1-訂單參數
		參數2-子付款方式
	*/
	public function getCREDIT($param){
		$param['ChoosePayment'] = 'Credit';
		$html = $this->getPayLink($param);
		//echo $html;
		return $html;
	}
	
	/*
		判斷伺服器回傳的確認碼，是否正確
	*/
	public function CheckConnent(){
		$check = false;
		if(isset($_SERVER['REMOTE_ADDR'])){
			$_CheckMacValue = strtolower($this->checkCode($_POST));
			if(strtolower($_POST['CheckMacValue']) == $_CheckMacValue){
				$check = true;
			}
		}
		return $check;
	}

	
	//因為allpay提交必須使用表單傳送所以這邊要建立表單自動傳送
	private function output($param){
		$szHtml = '';
		$szHtml = '<form id="__allpayForm" method="post" target="_self" action="http://payment-stage.allpay.com.tw/Cashier/AioCheckOu" style="text-align:center;">';
		foreach ($param as $keys => $value){
			$szHtml .="<input type='hidden' name='$keys' value='$value' />\n";
		}		
		if($this->auto_submit){
			$szHtml .= '<script type="text/javascript">document.getElementById("__allpayForm").submit();</script>';
		}else{
			$szHtml .= "<input type='submit' id='__paymentButton' class='next-btn' value='完成訂單' style='float: right;background: #ff3366;color: #fff;border: 1px solid #ff3366;padding: 10px 20px;' />";
		}
		$szHtml .= '</form>';
		return $szHtml;
	}
	
	//在測試的時候可以把這個功能打開，就不會自動提交form了配合上方output function使用
	public function isAutosubmit(){
		$this->auto_submit = true;
	}
	
	//建立確認碼
	public function checkCode($param){
		uksort($form_array, 'merchantSort');
		$keystr .= "HashKey=".$this->_HashKey;
		
		foreach($param as $k => $v){
			$keystr .= "&" . $k . "=" . $v;
		}
		$keystr .= "&HashIV=".$this->_HashIV;
		
		$encode_str = strtolower(urlencode($keystr));
		$encode_str = $this->_replaceChar($encode_str);


		return md5($encode_str);
	}
	
	
	private function _extends($param1,$param2){
		if(count($param2) > 0){
			foreach($param2 as $k => $v){
				$param1[$k] = $v;
			}
		}
		return $param1;
	}
	
	//特殊字元置換
	public function _replaceChar($value)
	{
		$search_list = array('%2d', '%5f', '%2e', '%21', '%2a', '%28', '%29');
		$replace_list = array('-', '_', '.', '!', '*', '(', ')');
		$value = str_replace($search_list, $replace_list ,$value);
		
		return $value;
	}
	
	//產生檢查碼
	public function _getMacValue($hash_key, $hash_iv, $form_array)
	{
		
		$encode_str = "HashKey=" . $hash_key;
		foreach ($form_array as $key => $value)
		{
			$encode_str .= "&" . $key . "=" . $value;
		}
		$encode_str .= "&HashIV=" . $hash_iv;
		$encode_str = strtolower(urlencode($encode_str));
		$encode_str = $this->_replaceChar($encode_str);
		return md5($encode_str);
	}
    //仿自然排序法
    public function merchantSort($a,$b)
	{
		return strcasecmp($a, $b);
	}
}
?>