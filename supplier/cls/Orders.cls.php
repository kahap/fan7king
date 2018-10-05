<?php
	class Orders{
		var $db;
		var $statusArr = array(0=>"已下單，Email未驗證",110=>"未完成下單",1=>"未進件",2=>"審查中",3=>"核准",4=>"婉拒",
				5=>"待補",6=>"補件",7=>"取消訂單",701=>"客戶自行撤件",8=>"出貨中",9=>"已到貨",10=>"已完成",
				11=>"換貨中",12=>"退貨中",13=>"完成退貨");
		var $statusDirectArr = array(0=>"處理中",1=>"取消訂單",2=>"出貨中",3=>"已到貨",4=>"已完成",
				5=>"換貨中",6=>"退貨中",7=>"完成退貨");
		//補件原因
		var $reasonArr = array(0=>"無",1=>"自訂",3=>"請重新上傳清楚不反光及不切字的【身分證】正反面",
		4=>"請重新上傳清楚不反光及不切字的【學生證】正反面",5=>"請重新補上第二步驟之正楷中文簽名",
		6=>"請補上一親一友之姓名及市內電話",
		7=>"請補上軍人證正反面影本",8=>"請重新上傳提供最新補換發身分證影本"
		,9=>"請補半年薪轉證明，存摺封面加內頁整面照片",
		10=>"請提供您及親友可以接電話的【照會時間】",
		11=>"請提供您登入學校系統的截圖畫面內容需包含學校名稱及您的姓名和學號");
		
		//建構函式
		public function Orders(){
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
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
                    where
                    `supNo`='".$_SESSION['supplieruserdata']['supNo']."'
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
                       `supNo`='".$_SESSION['supplieruserdata']['supNo']."'
                      and
						`orNo`=".$orNo;
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
                     `supNo`='".$_SESSION['supplieruserdata']['supNo']."'
                      and
						`memNo`=".$memNo;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//根據內部訂單編號取得訂單
		public function getOrByInternalCase($orInternalCaseNo){
			$sql = "select
						*
					from
						`orders`
					where
                       `supNo`='".$_SESSION['supplieruserdata']['supNo']."'
                    and
						`orInternalCaseNo`=".$orInternalCaseNo;
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
						`orMethod` = ".$orMethod;
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
                       `supNo`='".$_SESSION['supplieruserdata']['supNo']."'
                      and
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
                       `supNo`='".$_SESSION['supplieruserdata']['supNo']."'
                      and
						`orStatus`=".$orStatus;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//根據進件狀態取得訂單
		public function getOrdersForReceiptSet($ifSet){
			$sql = "select
						*
					from
						`orders`
					where
                       `supNo`='".$_SESSION['supplieruserdata']['supNo']."'
                      and
						`orIfSetReceipt`= '".$ifSet."'
					and
						`orStatus` = 10";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//根據進件狀態取得訂單
		public function getOrdersForSupplierPayment($orHandleGetFromSupDate,$supNo){
			$sql = "select
						*
					from
						`orders`
					where
                       `supNo`='".$_SESSION['supplieruserdata']['supNo']."'
                      and
						`orMethod`= 1
					and
						`orStatus` = 9
					and
						`orHandleGetFromSupDate` = '".$orHandleGetFromSupDate."'
					and
						`orHandleChangeProDate` IS NULL
					and
						`supNo` = '".$supNo."'";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//根據進件狀態取得訂單
		public function getOrdersForSupplierPaymentAfterProChange($orHandleChangeProDate,$supNo){
			$sql = "select
						*
					from
						`orders`
					where
                       `supNo`='".$_SESSION['supplieruserdata']['supNo']."'
                      and
						`orMethod`= 1
					and
						`orStatus` = 9
					and
						`orHandleChangeProDate` = '".$orHandleChangeProDate."'
					and
						`supNo` = '".$supNo."'";
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
                       `supNo`='".$_SESSION['supplieruserdata']['supNo']."'
                      and
						`orStatus`='".$orStatus."'
					and
						`orMethod`='".$orMethod."'";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//根據進件狀態取得訂單
		public function getAllInternalCaseOr(){
			$sql = "select
						*
					from
						`orders`
					where
                       `supNo`='".$_SESSION['supplieruserdata']['supNo']."'
                      and
						`orInternalCaseNo` <> ''";
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
                       `supNo`='".$_SESSION['supplieruserdata']['supNo']."'
                      and
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
                       `supNo`='".$_SESSION['supplieruserdata']['supNo']."'
                      and
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
                       `supNo`='".$_SESSION['supplieruserdata']['supNo']."'
                      and
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
                       `supNo`='".$_SESSION['supplieruserdata']['supNo']."'
                      and
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
                       `supNo`='".$_SESSION['supplieruserdata']['supNo']."'
                      and
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
                       `supNo`='".$_SESSION['supplieruserdata']['supNo']."'
                      and
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
                       `supNo`='".$_SESSION['supplieruserdata']['supNo']."'
                      and
						`orMethod` = '".$orMethod."'";
			if(isset($orDateFrom) && isset($orDateTo)){
				$sql .= " and
							DATE(`orDate`) between '".$orDateFrom."' and '".$orDateTo."'";
			}
			if($orMethod >= '2'){
				
				$str = "orReportPeriod".$orMethod."Date";
				$sql .= "order by ".$str." desc";
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
                       `supNo`='".$_SESSION['supplieruserdata']['supNo']."'
                      and
						`orMethod` = '".$orMethod."'
					and
						`orStatus` = '".$orStatus."'";
			if(isset($orDateFrom) && isset($orDateTo)){
				$sql .= " and
							DATE(`orDate`) between '".$orDateFrom."' and '".$orDateTo."'";
			}
			
			if($orMethod >= '2'){
				
				$str = "orReportPeriod".$orMethod."Date";
				$sql .= "order by ".$str." desc";
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
                       `supNo`='".$_SESSION['supplieruserdata']['supNo']."'
                      and
						`orMethod`=".$orMethod;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		
		//新增
		function insert($array){
			foreach($array as $key =>$value){
				$$key = mysql_real_escape_string($value);
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
						`orInternalCaseNo`='".mysql_real_escape_string($orInternalCaseNo)."'
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
						`orHandleTransportComp`='".mysql_real_escape_string($orHandleTransportComp)."',
						`orHandleTransportSerialNum`='".mysql_real_escape_string($orHandleTransportSerialNum)."',
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
						`orDocProvideReason`= '".mysql_real_escape_string($orDocProvideReason)."',
						`orDocProvideComment`= '".mysql_real_escape_string($orDocProvideComment)."'
					where
						`orNo`='".$orNo."'";
		
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯供貨價
		public function updateOrSupPrice($orSupPrice,$orNo){
			$sql = "update
						`orders`
					set
						`orSupPrice`= '".mysql_real_escape_string($orSupPrice)."'
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
		
		//編輯處理狀態
		public function updateIfSetReceipt($orIfSetReceipt,$orNo){
			date_default_timezone_set('Asia/Taipei');
			$date = date('Y-m-d H:i:s', time());
			$sql = "update
						`orders`
					set
						`orIfSetReceipt`= '".$orIfSetReceipt."',
						`orSetReceiptTime`= '".$date."'
					where
						`orNo` = '".$orNo."'";
		
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯是否可編輯訂單
		public function updateIfEditable($orIfEditable,$orNo){
			$sql = "update
						`orders`
					set
						`orIfEditable`= '".$orIfEditable."'
					where
						`orNo` = '".$orNo."'";
		
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯該會員所有訂單戶籍資料
		public function updateBirth($orAddr,$orPhone,$memNo){
			$sql = "update
						`orders`
					set
						`orAppApplierBirthAddr`= '".mysql_real_escape_string($orAddr)."',
						`orAppApplierBirthPhone` = '".mysql_real_escape_string($orPhone)."'
					where
						`memNo` = '".$memNo."'";
		
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯該會員所有訂單現住資料
		public function updateCurrent($orAddr,$orPhone,$memNo){
			$sql = "update
						`member`
					set
						`memAddr`= '".mysql_real_escape_string($orAddr)."',
						`memPhone` = '".mysql_real_escape_string($orPhone)."'
					where
						`memNo` = '".$memNo."'";
		
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯訂單明細
		public function update($array,$orNo){
			foreach($array as $key =>$value){
				$$key = mysql_real_escape_string($value);
			}
			if(!isset($orInternalCaseNo)){
				$orInternalCaseNo = "";
			}
			$orHandleOrderFromSupDate = !empty($orHandleOrderFromSupDate) ? "'$orHandleOrderFromSupDate'" : "NULL";
			$orHandleSupOutDate = !empty($orHandleSupOutDate) ? "'$orHandleSupOutDate'" : "NULL";
			$orHandleGetFromSupDate = !empty($orHandleGetFromSupDate) ? "'$orHandleGetFromSupDate'" : "NULL";
			$orHandlePaySupDate = !empty($orHandlePaySupDate) ? "'$orHandlePaySupDate'" : "NULL";
			$orHandleChangeProDate = !empty($orHandleChangeProDate) ? "'$orHandleChangeProDate'" : "NULL";
			$orHandleRefundDate = !empty($orHandleRefundDate) ? "'$orHandleRefundDate'" : "NULL";
			$orIfSecret = !empty($orIfSecret) ? '1':"NULL";
			$sql = "update
						`orders`
					set
						`orStatus`='".$orStatus."',
						`orInternalCaseNo` = '".$orInternalCaseNo."',
						`orPeriodAmnt` = '".$orPeriodAmnt."',
						`orDocProvideComment` = '".$orDocProvideComment."',
						`orPeriodTotal` = '".$orPeriodTotal."',
						`orHandleOrderFromSupDate` = $orHandleOrderFromSupDate,
						`orHandleSupOutDate` = $orHandleSupOutDate,
						`orHandleTransportComp` = '".$orHandleTransportComp."',
						`orHandleTransportSerialNum` = '".$orHandleTransportSerialNum."',
						`orHandleGetFromSupDate` = $orHandleGetFromSupDate,
						`orHandlePaySupDate` = $orHandlePaySupDate,
						`orHandleChangeProDate` = $orHandleChangeProDate,
						`orHandleRefundDate` = $orHandleRefundDate,
						`supNo` = '".$supNo."',
						`orSupPrice` = '".$orSupPrice."',
						`orReceiveName` = '".$orReceiveName."',
						`orReceiveAddr` = '".$orReceiveAddr."',
						`orReceivePhone` = '".$orReceivePhone."',
						`orReceiveCell` = '".$orReceiveCell."',
						`orReceiveComment` = '".$orReceiveComment."',
						`orBusinessNumIfNeed` = '".$orBusinessNumIfNeed."',
						`orBusinessNumNumber` = '".$orBusinessNumNumber."',
						`orBusinessNumTitle` = '".$orBusinessNumTitle."',
						`orProSpec` = '".$orProSpec."',
						`orIfSecret` = '".$orIfSecret."'";
			if(isset($orBillAddr)){
				$sql .= ",`orBillAddr` = '".$orBillAddr."'";
			}
			
			$sql .= "where
						`orNo`='".$orNo."'";
			
			$update = $this->db->updateRecords($sql);
			$sql_realcases = "UPDATE  `happyfan_system`.`real_cases` SET  `supNo` =  '".$supNo."' WHERE  `real_cases`.`rcRelateDataNo` ='".$orNo."'";
			$this->db->updateRecords($sql_realcases);
			return $update;
		}
		
		//刪除推薦獎金
		function delete($orNo){
			$sql = "delete from `orders` where `orNo` = ".$orNo;
			$delete = $this->db->deleteRecords($sql);
			return $delete;
		}
		
		//新增列印紀錄
		function addservice_record($orNo,$aauNoService){
			$sql = "INSERT INTO `service_record`(`rcNo`, `aauNoService`,`content`, `time`) VALUES ('".$orNo."','".$aauNoService."','列印','".date('Y-m-d H:i:s',time())."')";
			$this->db->insertRecords($sql);
		}
		
		function updaterealcase($supNo,$orNo){
			$sql = "UPDATE  `happyfan_system`.`real_cases` SET  `supNo` =  '".$supNo."' WHERE  `real_cases`.`rcRelateDataNo` ='".$orNo."'";
			$this->db->updateRecords($sql);
		}
		
		//查尋機車、手機資料
		function getMcoData($mcoNo){
			$sql = "SELECT * FROM `motorbike_cellphone_orders` where mcoNo = '".$mcoNo."'";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		function getrcData($rcNo){
			$sql = "SELECT * FROM `real_cases` where rcNo = '".$rcNo."'";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		//編輯處理狀態
		public function updateMcoIfProcess($mcoIfProcessInCurrentStatus,$mcoNo){
			$sql = "update
						`motorbike_cellphone_orders`
					set
						`mcoIfProcessInCurrentStatus`= '".$mcoIfProcessInCurrentStatus."'
					where
						`mcoNo` = '".$mcoNo."'";
		
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		function getSql($sql){
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
	}
?>