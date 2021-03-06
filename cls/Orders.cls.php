<?php
	class Orders{
		var $db;
		var $statusArr = array(0=>"已下單，Email未驗證",110=>"未完成下單",1=>"審查中",2=>"審查中",3=>"審核通過",4=>"審核未通過",5=>"資料不全需補件",6=>"審查中",7=>"取消訂單",701=>"取消訂單",8=>"出貨中",9=>"已到貨",10=>"我要繳款",
				11=>"換貨中",12=>"退貨中",13=>"完成退貨");
		
		var $statusDirectArr = array(0=>"處理中",1=>"取消訂單",2=>"出貨中",3=>"已收貨",4=>"已完成",
				5=>"換貨中",6=>"退貨中",7=>"完成退貨");
		//補件原因
		var $reasonArr = array(0=>"無",1=>"自訂",3=>"請重新上傳清楚不反光及不切字的【身分證】正反面，補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",4=>"請重新上傳清楚不反光及不切字的【學生證】正反面，補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",5=>"請重新補上第二步驟之正楷中文簽名，補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",6=>"請補上一親一友之姓名及市內電話資料，留言1.親屬:姓名、關係、手機、市內電話；2.朋友:姓名、手機，麻煩用LINE傳給我們，如有疑問請洽LINE客服，補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",7=>"請補上【軍人證】正反面影本，補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",8=>"請重新上傳提供最新補換發【身分證】正反面影本，補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",9=>"請您補上財力證明(近半年存摺[薪轉更加分])，有帳號的封面拍一張，若無帳號之存摺封面請再補拍帳號那一面一張，存摺內頁麻煩刷到最新，內頁上下兩頁合拍在一起(從今天往回推提供近6個月)不要反光不要模糊不要切邊之多張照片上傳用LINE傳遞。補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",10=>"請您確認戶籍地址、收貨地址以及現住地址是否完整，並補上正確資料。補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",11=>"請您補上勞保異動明細表。補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",12=>"請您登入學校網站需要有您學校名稱、姓名、學號的畫面，麻煩截圖上傳LINE客服。補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",13=>"請您提供一位法定代理人(父or母)當保證人(盡量有市內電話以及工作滿半年以上較佳)。您需先加入客服LINE索取保人申請書並詢問填寫及回覆方式，並上傳保人身分證正反面影本到LINE客服。補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",14=>"請您提供一位正職保證人(盡量有市內電話以及工作滿半年以上較佳)。您需先加入LINE客服索取保人申請書並詢問填寫及回覆方式，還要上傳保人身分證正反面影本一併傳到LINE客服。補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",15=>"請您補上可接照會電話時段。如審查時段早上9點~12點，下午13點~18點。您本人可接電話時段:ex:10-14點；您的聯絡人可接電話時段:ex:13-18點；您公司內有人可接照會電話時段：ex:9-12點；保人可接電話時段：ex:9-18點(若無告知補保人則免填)。補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件");
		
		//建構函式
		public function Orders(){
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
		}
		
		public function status($key){
			return $this->statusArr[$key];
		}
		
		//將代號轉換
		public function changeToReadable(&$str,$method){
			foreach($str as $key=>&$value){
// 				if($value == ""){
// 					$value = "無";
// 				}
				if($key == "orStatus"){
					if($method == 1){
						foreach($this->statusArr as $keyIn=>$valueIn){
							if($value == $keyIn){
								$value = $valueIn;
							}
						}
					}else{
						foreach($this->statusDirectArr as $keyIn=>$valueIn){
							if($value == $keyIn){
								$value = $valueIn;
							}
						}
					}
				}
				if($key == "orMethod"){
					if($value == 0){
						$value = "直購";
					}else{
						$value = "分期";
					}
				}
				if($key == "orPaySuccess"){
					if($value == 0){
						$value = "未付款";
					}else{
						$value = "已付款";
					}
				}
			}
		}
		
		//取得欄位中文名
		public function getAllColumnNames($tableName){
			$sql = "select
						COLUMN_COMMENT, COLUMN_NAME 
					from
						information_schema.COLUMNS 
					WHERE 
						TABLE_SCHEMA = '".SYSTEM_DBNAME."' 
					AND 
						TABLE_NAME = '".$tableName."'";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//取得所有訂單
		public function getAllOrders(){
			$sql = "select
						*
					from
						`orders`
					order by
						`orNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//編號取得單一訂單
		public function getOneOrderByNo($orNo){
			$sql = "select
						*
					from
						`orders`
					where
						`orNo`='".$orNo."'";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//根據會員取得訂單
		public function getOrByMember($memNo){
			$sql = "select
						*
					from
						`orders`
					where
						`memNo`=".$memNo;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//根據會員取得訂單
		public function getOrByMemberAndMethod($memNo,$orMethod){
			$sql = "select
						*
					from
						`orders`
					where
						`memNo`=".$memNo."
					and
						`orMethod` = ".$orMethod."
					order by orDate desc";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//根據會員取得一筆訂單
		public function getOnlyOrByMemberAndMethod($memNo,$orMethod){
			$sql = "select
						*
					from
						`orders`
					where
						`memNo`=".$memNo."
					and
						`orMethod` = ".$orMethod."
					order by orDate desc
					limit 1";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		//根據會員取得一筆訂單
		public function getTwinOrByMemberAndMethod($memNo,$orMethod){
			$sql = "select
						*
					from
						`orders`
					where
						`memNo`=".$memNo."
					and
						`orMethod` = ".$orMethod."
					order by orDate desc
					limit 2";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//根據會員完成的訂單
		public function getDealFinish($memNo,$orMethod){
			$sql = "select
						*
					from
						`orders`
					where
						`memNo`=".$memNo."
					and
						`orMethod` = ".$orMethod." &&
						`orIfEditable` = '1' 
					order by orDate desc
					limit 1";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//根據案件編號取得單一訂單
		public function getOneOrderByCaseNo($orCaseNo){
			$sql = "select
						*
					from
						`orders`
					where
						`orCaseNo`='".$orCaseNo."'";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//根據進件狀態取得訂單
		public function getOneOrderByOrStatus($orStatus){
			$sql = "select
						*
					from
						`orders`
					where
						`orStatus`=".$orStatus;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//根據進件狀態取得訂單
		public function getOneOrderByOrStatusAndMethod($orStatus,$orMethod){
			$sql = "select
						*
					from
						`orders`
					where
						`orStatus`='".$orStatus."'
					and
						`orMethod`='".$orMethod."'";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//根據進件狀態取得訂單
		public function getOneOrderByOrStatusAndMethodAndIfProcess($orStatus,$orMethod,$orIfProcessInCurrentStatus){
			$sql = "select
						*
					from
						`orders`
					where
						`orStatus`='".$orStatus."'
					and
						`orMethod`='".$orMethod."'
					and
						`orIfProcessInCurrentStatus` = '".$orIfProcessInCurrentStatus."'";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//根據廠商、日期、狀態取得訂單
		public function getAllOrderOnSupPage($action,$orStatus,$supNo,$orDateFrom,$orDateTo,$orSupDateFrom,$orSupDateTo){
			$sql = "select
						*
					from
						`orders`
					where
						DATE(`orDate`) between '".$orDateFrom."' and '".$orDateTo."'
					and
						DATE(`orHandleOrderFromSupDate`) between '".$orSupDateFrom."' and '".$orSupDateTo."'
					and 
						`orMethod` = '".$action."'";
			if($supNo != "all"){
				$sql .= " and
							`supNo`='".$supNo."'";
			}
			if($orStatus != "all"){
				$sql .= " and
							`orStatus`='".$orStatus."'";
			}
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//根據廠商、日期、狀態取得訂單(沒訂貨日期)
		public function getAllOrderOnSupPageNoSupOrder($action,$orStatus,$supNo,$orDateFrom,$orDateTo){
			$sql = "select
						*
					from
						`orders`
					where
						DATE(`orDate`) between '".$orDateFrom."' and '".$orDateTo."'
					and 
						`orMethod` = '".$action."'";
			if($supNo != "all"){
				$sql .= " and
							`supNo`='".$supNo."'";
			}
			if($orStatus != "all"){
				$sql .= " and
							`orStatus`='".$orStatus."'";
			}
					
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//根據廠商、日期、狀態、付款狀態取得訂單
		public function getAllOrderOnSupPageWithPayStatus($action,$orStatus,$supNo,$orDateFrom,$orDateTo,$orSupDateFrom,$orSupDateTo,$orPaySuccess){
			$sql = "select
						*
					from
						`orders`
					where
						DATE(`orDate`) between '".$orDateFrom."' and '".$orDateTo."'
					and
						DATE(`orHandleOrderFromSupDate`) between '".$orSupDateFrom."' and '".$orSupDateTo."'
					and
						`orPaySuccess`='".$orPaySuccess."'
					and 
						`orMethod` = '".$action."'";
			if($supNo != "all"){
				$sql .= " and
							`supNo`='".$supNo."'";
			}
			if($orStatus != "all"){
				$sql .= " and
							`orStatus`='".$orStatus."'";
			}
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//根據廠商、日期、狀態、付款狀態取得訂單(沒訂貨日期)
		public function getAllOrderOnSupPageWithPayStatusNoSupOrder($action,$orStatus,$supNo,$orDateFrom,$orDateTo,$orPaySuccess){
			$sql = "select
						*
					from
						`orders`
					where
						DATE(`orDate`) between '".$orDateFrom."' and '".$orDateTo."'
					and
						`orPaySuccess`='".$orPaySuccess."'
					and 
						`orMethod` = '".$action."'";
			if($supNo != "all"){
				$sql .= " and
							`supNo`='".$supNo."'";
			}
			if($orStatus != "all"){
				$sql .= " and
							`orStatus`='".$orStatus."'";
			}
				
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//進件管理紀錄(日期range)
		public function getAllOrderForReport($orDateFrom,$orDateTo){
			$sql = "select
						*
					from
						`orders`
					where
						`orMethod` = 1";
			if(isset($orDateFrom) && isset($orDateTo)){
				$sql .= " and
							DATE(`orDate`) between '".$orDateFrom."' and '".$orDateTo."'
						  or
							DATE(`orReportPeriod2Date`) between '".$orDateFrom."' and '".$orDateTo."'
						  or
							DATE(`orReportPeriod3Date`) between '".$orDateFrom."' and '".$orDateTo."'
						  or
							DATE(`orReportPeriod4Date`) between '".$orDateFrom."' and '".$orDateTo."'
						  or
							DATE(`orReportPeriod5Date`) between '".$orDateFrom."' and '".$orDateTo."'";
			}
				
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//訂單查詢根據日期(訂單日期range)
		public function getAllOrderByDateAndMethod($orDateFrom,$orDateTo,$orMethod){
			$sql = "select
						*
					from
						`orders`
					where
						`orMethod` = '".$orMethod."'";
			if(isset($orDateFrom) && isset($orDateTo)){
				$sql .= " and
							DATE(`orDate`) between '".$orDateFrom."' and '".$orDateTo."'";
			}
		
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//訂單查詢根據日期(訂單日期range)
		public function getAllOrderByDateAndMethodAndStatus($orDateFrom,$orDateTo,$orMethod,$orStatus){
			$sql = "select
						*
					from
						`orders`
					where
						`orMethod` = '".$orMethod."'
					and
						`orStatus` = '".$orStatus."'";
			if(isset($orDateFrom) && isset($orDateTo)){
				$sql .= " and
							DATE(`orDate`) between '".$orDateFrom."' and '".$orDateTo."'";
			}
		
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//根據直購/分期取得訂單
		public function getOrderByMethod($orMethod){
			$sql = "select
						*
					from
						`orders`
					where
						`orMethod`=".$orMethod;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		
		//新增
		function insert($array){
			foreach($array as $key =>$value){
				$$key = $value;
			}
			date_default_timezone_set('Asia/Taipei');
			$date = date('Y-m-d H:i:s', time());
			$sql = "insert into `orders`(`orCaseNo`, `memNo` ,`proNo`,`orAmount`,
					`orTotal`,`orMethod`,`orStatus`,`supNo`,`orSupTotal`,`orPaySuccess`,
					`orDate`,`orPeriodAmnt`,`orTotalEachPeriod` )
					values('".$orCaseNo."',
							'".$memNo."',
							'".$proNo."',
							'".$orAmount."',
							'".$orMethod."',
							'".$orStatus."',
							'".$supNo."',
							'".$orSupTotal."',
							'".$orPaySuccess."',
							'".$date."',
							'".$orPeriodAmnt."',
							'".$orTotalEachPeriod."')";
			$insert = $this->db->insertRecords($sql);
			return $insert;
		}
		
		//編輯進件狀態
		public function updateStatus($orStatus,$orNo){
			$sql = "update
						`orders`
					set
						`orStatus`='".$orStatus."'
					where
						`orNo`='".$orNo."'";
			
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯進件時間
		public function updateStatusTime($whichStat,$orNo){
			date_default_timezone_set('Asia/Taipei');
			$date = date('Y-m-d H:i:s', time());
			$sql = "update
						`orders`
					set
						`orReportPeriod".$whichStat."Date`='".$date."'
					where
						`orNo`='".$orNo."'";
		
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯進件時間(直購)
		public function updateStatusTimeDirect($whichStat,$orNo){
			date_default_timezone_set('Asia/Taipei');
			$date = date('Y-m-d H:i:s', time());
			$sql = "update
						`orders`
					set
						`orReportDirect".$whichStat."Date`='".$date."'
					where
						`orNo`='".$orNo."'";
		
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯內部案件編號
		public function updateInternalCaseNo($orInternalCaseNo,$orNo){
			$sql = "update
						`orders`
					set
						`orInternalCaseNo`='".$orInternalCaseNo."'
					where
						`orNo`='".$orNo."'";
				
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯內部訂貨到收貨流程
		public function updateOrderAndGetFromSup($orHandleSupOutDate,$orHandleTransportComp,$orHandleTransportSerialNum,$orHandleGetFromSupDate,$orNo){
			$orHandleSupOutDate = !empty($orHandleSupOutDate) ? "'$orHandleSupOutDate'" : "NULL";
			$orHandleGetFromSupDate = !empty($orHandleGetFromSupDate) ? "'$orHandleGetFromSupDate'" : "NULL";
			
			$sql = "update
						`orders`
					set
						`orHandleSupOutDate`= $orHandleSupOutDate ,
						`orHandleTransportComp`='".$orHandleTransportComp."',
						`orHandleTransportSerialNum`='".$orHandleTransportSerialNum."',
						`orHandleGetFromSupDate`= $orHandleGetFromSupDate 
					where
						`orNo`='".$orNo."'";
		
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯內部撥款日期
		public function updatePaySupDate($orHandlePaySupDate,$orNo){
			$orHandlePaySupDate = !empty($orHandlePaySupDate) ? "'$orHandlePaySupDate'" : "NULL";
			
			$sql = "update
						`orders`
					set
						`orHandlePaySupDate`= $orHandlePaySupDate
					where
						`orNo`='".$orNo."'";
		
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯內部換貨簽收日期
		public function updateChangeProDate($orHandleChangeProDate,$orNo){
			$orHandleChangeProDate = !empty($orHandleChangeProDate) ? "'$orHandleChangeProDate'" : "NULL";
				
			$sql = "update
						`orders`
					set
						`orHandleChangeProDate`= $orHandleChangeProDate
					where
						`orNo`='".$orNo."'";
		
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯內部退貨簽收日期
		public function updateRefundDate($orHandleRefundDate,$orNo){
			$orHandleRefundDate = !empty($orHandleRefundDate) ? "'$orHandleRefundDate'" : "NULL";
		
			$sql = "update
						`orders`
					set
						`orHandleRefundDate`= $orHandleRefundDate
					where
						`orNo`='".$orNo."'";
		
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯完成退貨處理時間
		public function updateProcessTime($orProcessTime,$orNo){
			$orProcessTime = !empty($orProcessTime) ? "'$orProcessTime'" : "NULL";
		
			$sql = "update
						`orders`
					set
						`orProcessTime`= $orProcessTime
					where
						`orNo`='".$orNo."'";
		
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯補件原因
		public function updateDocProvideReason($orDocProvideReason,$orDocProvideComment,$orNo){
			$sql = "update
						`orders`
					set
						`orDocProvideReason`= '".$orDocProvideReason."',
						`orDocProvideComment`= '".$orDocProvideComment."'
					where
						`orNo`='".$orNo."'";
		
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯訂貨日期
		public function updateOrderFromSupDate($orHandleOrderFromSupDate,$orNo){
			$sql = "update
						`orders`
					set
						`orHandleOrderFromSupDate`= '".$orHandleOrderFromSupDate."'
					where
						`orNo` = '".$orNo."'";
		
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯處理狀態
		public function updateIfProcess($orIfProcessInCurrentStatus,$orNo){
			$sql = "update
						`orders`
					set
						`orIfProcessInCurrentStatus`= '".$orIfProcessInCurrentStatus."'
					where
						`orNo` = '".$orNo."'";
		
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯訂單明細
		public function update($array,$orNo){
			foreach($array as $key =>$value){
				$$key = $value;
			}
			$orHandleOrderFromSupDate = !empty($orHandleOrderFromSupDate) ? "'$orHandleOrderFromSupDate'" : "NULL";
			$orHandleSupOutDate = !empty($orHandleSupOutDate) ? "'$orHandleSupOutDate'" : "NULL";
			$orHandleGetFromSupDate = !empty($orHandleGetFromSupDate) ? "'$orHandleGetFromSupDate'" : "NULL";
			$orHandlePaySupDate = !empty($orHandlePaySupDate) ? "'$orHandlePaySupDate'" : "NULL";
			$orHandleChangeProDate = !empty($orHandleChangeProDate) ? "'$orHandleChangeProDate'" : "NULL";
			$orHandleRefundDate = !empty($orHandleRefundDate) ? "'$orHandleRefundDate'" : "NULL";
			
			$sql = "update
						`orders`
					set
						`orStatus`='".$orStatus."',
						`orHandleOrderFromSupDate` = $orHandleOrderFromSupDate,
						`orHandleSupOutDate` = $orHandleSupOutDate,
						`orHandleTransportComp` = '".$orHandleTransportComp."',
						`orHandleTransportSerialNum` = '".$orHandleTransportSerialNum."',
						`orHandleGetFromSupDate` = $orHandleGetFromSupDate,
						`orHandlePaySupDate` = $orHandlePaySupDate,
						`orHandleChangeProDate` = $orHandleChangeProDate,
						`orHandleRefundDate` = $orHandleRefundDate,
						`supNo` = '".$supNo."',
						`orReceiveName` = '".$orReceiveName."',
						`orReceiveAddr` = '".$orReceiveAddr."',
						`orReceivePhone` = '".$orReceivePhone."',
						`orReceiveCell` = '".$orReceiveCell."',
						`orReceiveComment` = '".$orReceiveComment."',
						`orBusinessNumIfNeed` = '".$orBusinessNumIfNeed."',
						`orBusinessNumNumber` = '".$orBusinessNumNumber."',
						`orBusinessNumTitle` = '".$orBusinessNumTitle."'";
			if(isset($orBillAddr)){
				$sql .= "`orBillAddr` = '".$orBillAddr."'";
			}
			
			$sql .= "where
						`orNo`='".$orNo."'";
				
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		public function product_number($status){
			$start = date('Y-m-d',time());
			$end = date('Y-m-d',time()+86400);
			$sql = "SELECT count(*) as number FROM `orders` WHERE `orDate` between '".$start."' and '".$end."'";
			$data = $this->db->selectRecords($sql);
			$str = str_pad($data[0]['number']+1,4,0,STR_PAD_LEFT);
			if($status == 0){
				$product_number = "A".date('Ymd',time()).$str."D";
			}else{
				$orNum = $data[0]['number']+1;
				$loansNum = $this->loans_number();
				$caseNo = str_pad(($orNum + $loansNum),4,0,STR_PAD_LEFT);
				$product_number = "A".date('Ymd',time()).$caseNo;
			}
			return $product_number;
		}
		
		public function loans_number(){
			$start = date('Y-m-d',time());
			$end = date('Y-m-d',time()+86400);
			$sql = "SELECT count(*) as number FROM `motorbike_cellphone_orders` WHERE `mcoDate` between '".$start."' and '".$end."'";
			$data = $this->db->selectRecords($sql);
			return $data[0]['number'];
		}
		
		
		public function getRealCaseNo(){
			$start = date('Y-m-d',time());
			$end = date('Y-m-d',time()+86400);
			$sql = "SELECT count(*) as number FROM `orders` WHERE `orDate` between '".$start."' and '".$end."'";
			$data = $this->db->selectRecords($sql);
			$curNo = $data[0]['number']+5001;
			$product_number = date('Ymd',time()).$curNo;
			return $product_number;
		}
		
		
		//刪除推薦獎金
		public function delete($orNo){
			$sql = "delete from `orders` where `orNo` = ".$orNo;
			$delete = $this->db->deleteRecords($sql);
			return $delete;
		}
		
		//jimmy edit
		public function insertorder($array){
			foreach($array as $key => $value){
			    if (is_array($value)){
			        foreach ($value as $v){
			            $v = mysqli_real_escape_string($this->db->oDbLink, $v);
                    }
                    $$key = $value;
                }else {
                    $$key = mysqli_real_escape_string($this->db->oDbLink, $value);
                }
			}
            $sql = "INSERT INTO `orders`(`orSupPrice`,`orCaseNo`,`memNo`,`memClass`, `pmNo`, `orProSpec`, `orAmount`, `orMethod`, `orStatus`, `supNo`,`orDate`,  `orPeriodAmnt`, `orPeriodTotal`,`orPayBy`,`orChosemethod`, `orIpAddress`, `orReceiveName`, `orReceiveAddr`, `orReceivePhone`, `orReceiveCell`, `orReceiveComment`, `orAppApplierBirthPhone`,`orAppApplierBirthAddrPostCode`, `orAppApplierBirthAddr`, `orAppApplierLivingOwnership`, `orAppApplierCompanystatus`, `orAppApplierCompanyName`, `orAppApplierYearExperience`, `orAppApplierMonthSalary`, `orAppApplierCompanyPhone`, `orAppApplierCompanyPhoneExt`, `orAppApplierCreditNum`, `orAppApplierCreditSecurityNum`, `orAppApplierCreditIssueBank`, `orAppApplierCreditDueDate`, `orBillAddr`, `orBusinessNumIfNeed`, `orBusinessNumNumber`, `orBusinessNumTitle`, `orAppContactRelaName`, `orAppContactRelaRelation`, `orAppContactRelaPhone`, `orAppContactRelaCell`, `orAppContactFrdName`, `orAppContactFrdRelation`, `orAppContactFrdPhone`, `orAppContactFrdCell`, `orAppAssureName`, `orAppAssureRelation`, `orAppAssureIdNum`, `orAppAssureBday`, `orAppAssureBirthPhone`, `orAppAssureAddr`, `orAppAssureCurPhone`, `orAppAssureCompName`, `orAppAssureCompPhone`, `orAppAssureCell`, `orAppExtraAvailTime`, `orAppExtraInfo`,`orReportPeriod110Date`, `pmPeriodAmnt`) VALUES ('".$orSupPrice."','".$orCaseNo."','".$memNo."','".$memClass."','".$pmNo."','".$orProSpec."','".$orAmount."','".$orMethod."','".$orStatus."','".$supNo."','".$orDate."','".$orPeriodAmnt."','".$orPeriodTotal."','".$orPayBy."','".$orChosemethod."','".$orIpAddress."','".$orReceiveName."','".$orReceiveAddr."','".$orReceivePhone."','".$orReceiveCell."','".$orReceiveComment."','".$orAppApplierBirthPhone."','".$orAppApplierBirthAddrPostCode."','".$orAppApplierBirthAddr."','".$orAppApplierLivingOwnership."','".$orAppApplierCompanystatus."','".$orAppApplierCompanyName."','".$orAppApplierYearExperience."','".$orAppApplierMonthSalary."','".$orAppApplierCompanyPhone."','".$orAppApplierCompanyPhoneExt."','".$orAppApplierCreditNum."','".$orAppApplierCreditSecurityNum."','".$orAppApplierCreditIssueBank."','".$orAppApplierCreditDueDate."','".$orBillAddr."','".$orBusinessNumIfNeed."','".$orBusinessNumNumber."','".$orBusinessNumTitle."','".$orAppContactRelaName."','".$orAppContactRelaRelation."','".$orAppContactRelaPhone."','".$orAppContactRelaCell."','".$orAppContactFrdName."','".$orAppContactFrdRelation."','".$orAppContactFrdPhone."','".$orAppContactFrdCell."','".$orAppAssureName."','".$orAppAssureRelation."','".$orAppAssureIdNum."','".$orAppAssureBday."','".$orAppAssureBirthPhone."','".$orAppAssureAddr."','".$orAppAssureCurPhone."','".$orAppAssureCompName."','".$orAppAssureCompPhone."','".$orAppAssureCell."','".$orAppExtraAvailTime."','".$orAppExtraInfo."','".$orReportPeriod110Date."','".$pmPeriodAmnt."')";
            $aa = $this->db->insertRecords($sql);
			return $aa;
		}
		
		public function updateorder($array,$orNo){
			foreach($array as $key => $value){
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
			}
			$sql = "update `orders` set
				`memClass` = '".$memClass."',
				`orPayBy` = '".$orPayBy."',
				`orIpAddress`='".$orIpAddress."',
				 `orReceiveName`='".$orReceiveName."',
				 `orReceiveAddr`='".$orReceiveAddr."',
				 `orReceivePhone`='".$orReceivePhone."',
				 `orReceiveCell`='".$orReceiveCell."',
				 `orReceiveComment`='".$orReceiveComment."',
				 `orAppApplierBirthPhone`='".$orAppApplierBirthPhone."',
				 `orAppApplierBirthAddrPostCode` = '".$orAppApplierBirthAddrPostCode."',
				 `orAppApplierBirthAddr`='".$orAppApplierBirthAddr."',
				 `orAppApplierLivingOwnership`='".$orAppApplierLivingOwnership."',
				 `orAppApplierCompanystatus`='".$orAppApplierCompanystatus."',
				 `orAppApplierCompanyName`='".$orAppApplierCompanyName."',
				 `orAppApplierYearExperience`='".$orAppApplierYearExperience."',
				 `orAppApplierMonthSalary`='".$orAppApplierMonthSalary."',
				 `orAppApplierCompanyPhone`='".$orAppApplierCompanyPhone."',
				 `orAppApplierCompanyPhoneExt`='".$orAppApplierCompanyPhoneExt."',
				 `orAppApplierCreditNum`='".$orAppApplierCreditNum."',
				 `orAppApplierCreditSecurityNum`='".$orAppApplierCreditSecurityNum."',
				 `orAppApplierCreditIssueBank`='".$orAppApplierCreditIssueBank."',
				 `orAppApplierCreditDueDate`='".$orAppApplierCreditDueDate."',
				 `orBillAddr`='".$orBillAddr."',
				 `orBusinessNumIfNeed`='".$orBusinessNumIfNeed."',
				 `orBusinessNumNumber`='".$orBusinessNumNumber."',
				 `orBusinessNumTitle`='".$orBusinessNumTitle."',
				 `orAppContactRelaName`='".$orAppContactRelaName."',
				 `orAppContactRelaRelation`='".$orAppContactRelaRelation."',
				 `orAppContactRelaPhone`='".$orAppContactRelaPhone."',
				 `orAppContactRelaCell`='".$orAppContactRelaCell."',
				 `orAppContactFrdName`='".$orAppContactFrdName."',
				 `orAppContactFrdRelation`='".$orAppContactFrdRelation."',
				 `orAppContactFrdPhone`='".$orAppContactFrdPhone."',
				 `orAppContactFrdCell`='".$orAppContactFrdCell."',
				 `orAppAssureName`='".$orAppAssureName."',
				 `orAppAssureRelation`='".$orAppAssureRelation."',
				 `orAppAssureIdNum`='".$orAppAssureIdNum."',
				 `orAppAssureBday`='".$orAppAssureBday."',
				 `orAppAssureBirthPhone`='".$orAppAssureBirthPhone."',
				 `orAppAssureAddr`='".$orAppAssureAddr."',
				 `orAppAssureCurPhone`='".$orAppAssureCurPhone."',
				 `orAppAssureCompName`='".$orAppAssureCompName."',
				 `orAppAssureCompPhone`='".$orAppAssureCompPhone."',
				 `orAppAssureCell`='".$orAppAssureCell."',
				 `orAppExtraAvailTime`='".$orAppExtraAvailTime."',
				 `orAppExtraInfo`='".$orAppExtraInfo."'
				 where orNo = '".$orNo."'";
			$aa = $this->db->updateRecords($sql);
			return $aa;
		}
		
		//編輯內部案件編號
		public function updateorAppAuthenIdImgTop($orAppAuthenIdImgTop,$orNo){
			$sql = "update
						`orders`
					set
						`orAppAuthenIdImgTop`='".$orAppAuthenIdImgTop."'
					where
						`orNo`='".$orNo."'";
				
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		//編輯內部案件編號
		public function updateorAppAuthenIdImgBot($orAppAuthenIdImgBot,$orNo){
			$sql = "update
						`orders`
					set
						`orAppAuthenIdImgBot`='".$orAppAuthenIdImgBot."'
					where
						`orNo`='".$orNo."'";
				
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		//學生證件
		public function updateorAppAuthenStudentIdImgTop($orAppAuthenStudentIdImgTop,$orNo){
			$sql = "update
						`orders`
					set
						`orAppAuthenStudentIdImgTop`='".$orAppAuthenStudentIdImgTop."'
					where
						`orNo`='".$orNo."'";
				
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		public function updateorAppAuthenStudentIdImgBot($orAppAuthenStudentIdImgBot,$orNo){
			$sql = "update
						`orders`
					set
						`orAppAuthenStudentIdImgBot`='".$orAppAuthenStudentIdImgBot."'
					where
						`orNo`='".$orNo."'";
				
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		public function updateorAppAuthenExtraInfo($orAppAuthenExtraInfo,$orNo){
			$sql = "update
						`orders`
					set
						`orAppAuthenExtraInfo`='".$orAppAuthenExtraInfo."'
					where
						`orNo`='".$orNo."'";
				
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		public function updateorAppAuthenProvement($orAppAuthenProvement,$orNo){
			$sql = "update
						`orders`
					set
						`orAppAuthenProvement`='".$orAppAuthenProvement."'
					where
						`orNo`='".$orNo."'";
				
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		public function updateorAppAuthenPromiseLetter($orAppAuthenPromiseLetter,$orNo){
			$sql = "update
						`orders`
					set
						`orAppAuthenPromiseLetter`='".$orAppAuthenPromiseLetter."'
					where
						`orNo`='".$orNo."'";
				
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		public function updateorAppAuthenSignature($orAppAuthenSignature,$orNo){
			$sql = "update
						`orders`
					set
						`orAppAuthenSignature`='".$orAppAuthenSignature."'
					where
						`orNo`='".$orNo."'";
				
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		public function updateorIfProcessInCurrentStatus($orIfProcessInCurrentStatus,$orNo){
			$sql = "update
						`orders`
					set
						`orIfProcessInCurrentStatus`='".$orIfProcessInCurrentStatus."'
					where
						`orNo`='".$orNo."'";
				
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		public function updateorIfSecret($orIfSecret,$orNo){
			$sql = "update
						`orders`
					set
						`orIfSecret`='".$orIfSecret."'
					where
						`orNo`='".$orNo."'";
				
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		public function updateorIfEditable($orIfEditable,$orNo){
			$sql = "update
						`orders`
					set
						`orIfEditable`='".$orIfEditable."'
					where
						`orNo`='".$orNo."'";
				
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		//編輯內部案件編號
		public function updateorPaySuccess($orNo){
			$sql = "update
						`orders`
					set
						`orPaySuccess`='1',
						`orIfEditable`='1'
						
					where
						`orNo`='".$orNo."'";
				
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		//取得歷史訂單紀錄是否有到核准訂單
		public function getOrderhistory($memNo){
			$sql = "select
						*
					from
						`orders`
					where
						`memNo`='".$memNo."' &&
						`orReportPeriod1Date` != ''";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//拒絕下單的客戶清單
		public function reject_custom($memNo){
			$sql = "SELECT memNo,orStatus,orDate FROM  `orders` WHERE  `orStatus` =  '4' && `memNo` not in (SELECT memNo FROM  `orders` WHERE  `orStatus` =  '10') && DATE_SUB( CURDATE( ) , INTERVAL 6 MONTH ) <= DATE( orReportPeriod4Date ) && memNo = '".$memNo."' group by memNo";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//拒絕下單的客戶清單(不GROUP)
		public function reject_custom_nogroup($memNo){
			$sql = "SELECT memNo,orStatus,orDate,DATE_FORMAT(orReportPeriod4Date + INTERVAL 6 MONTH,'%Y-%m-%d') as dueDate FROM  `orders` WHERE  `orStatus` =  '4' && `memNo` not in (SELECT memNo FROM  `orders` WHERE  `orStatus` =  '10') && DATE_SUB( CURDATE( ) , INTERVAL 6 MONTH ) <= DATE( orReportPeriod4Date ) && memNo = '".$memNo."' order by orReportPeriod4Date desc";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//更新身分證內容
		public function updateForId($array,$orNo){
			foreach($array as $key => $value){
				$str .= $key."='".$value."',";
			}
			$sql = "update
						`orders`
					set
						".substr($str,0,-1)."						
					where
						`orNo`='".$orNo."'";
				
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		public function customSql($sql){
			$data = $this->db->selectRecords($sql);
			$this->data = $data;

			return $data;
		}
		
	}
?>