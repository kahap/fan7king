<?php
	class Orders_Other{
		var $db;
		var $statusArr = array(0=>"已下單，Email未驗證",110=>"未完成下單",1=>"審查中",2=>"審查中",3=>"審核通過",4=>"審核未通過",5=>"資料不全需補件",6=>"審查中",7=>"取消訂單",701=>"取消訂單",8=>"出貨中",9=>"已到貨",10=>"我要繳款",
				11=>"換貨中",12=>"退貨中",13=>"完成退貨");

		var $statusDirectArr = array(0=>"處理中",1=>"取消訂單",2=>"出貨中",3=>"已收貨",4=>"已完成",
				5=>"換貨中",6=>"退貨中",7=>"完成退貨");
		//補件原因
		var $reasonArr = array(0=>"無",1=>"自訂",3=>"請重新上傳清楚不反光及不切字的【身分證】正反面，補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",4=>"請重新上傳清楚不反光及不切字的【學生證】正反面，補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",5=>"請重新補上第二步驟之正楷中文簽名，補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",6=>"請補上一親一友之姓名及市內電話資料，留言1.親屬:姓名、關係、手機、市內電話；2.朋友:姓名、手機，麻煩用LINE傳給我們，如有疑問請洽LINE客服，補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",7=>"請補上【軍人證】正反面影本，補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",8=>"請重新上傳提供最新補換發【身分證】正反面影本，補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",9=>"請您補上財力證明(近半年存摺[薪轉更加分])，有帳號的封面拍一張，若無帳號之存摺封面請再補拍帳號那一面一張，存摺內頁麻煩刷到最新，內頁上下兩頁合拍在一起(從今天往回推提供近6個月)不要反光不要模糊不要切邊之多張照片上傳用LINE傳遞。補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",10=>"請您確認戶籍地址、收貨地址以及現住地址是否完整，並補上正確資料。補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",11=>"請您補上勞保異動明細表。補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",12=>"請您登入學校網站需要有您學校名稱、姓名、學號的畫面，麻煩截圖上傳LINE客服。補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",13=>"請您提供一位法定代理人(父or母)當保證人(盡量有市內電話以及工作滿半年以上較佳)。您需先加入客服LINE索取保人申請書並詢問填寫及回覆方式，並上傳保人身分證正反面影本到LINE客服。補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",14=>"請您提供一位正職保證人(盡量有市內電話以及工作滿半年以上較佳)。您需先加入LINE客服索取保人申請書並詢問填寫及回覆方式，還要上傳保人身分證正反面影本一併傳到LINE客服。補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",15=>"請您補上可接照會電話時段。如審查時段早上9點~12點，下午13點~18點。您本人可接電話時段:ex:10-14點；您的聯絡人可接電話時段:ex:13-18點；您公司內有人可接照會電話時段：ex:9-12點；保人可接電話時段：ex:9-18點(若無告知補保人則免填)。補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件");

		//建構函式
		public function Orders_Other(){
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
						`motorbike_cellphone_orders`
					order by
						`orNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}

		//編號取得單一訂單
		public function getOneOrderByNo($mcoCaseNo){
			$sql = "select
						*
					from
						`motorbike_cellphone_orders`
					where
						`mcoCaseNo`='".$mcoCaseNo."'";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
        //編號取得單一訂單
		public function getMcoByNo($mcoNo){
			$sql = "select
						*
					from
						`motorbike_cellphone_orders`
					where
						`mcoNo`='".$mcoNo."'"     ;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		//根據會員取得訂單
		public function getOrByMember($memNo){
			$sql = "select
						*
					from
						`motorbike_cellphone_orders`
					where
						`memNo`=".$memNo;

			$data = $this->db->selectRecords($sql);
			return $data;
		}

        //根據會員取得訂單
		public function getOrByMemberByType($memNo,$mcoType){
			$sql = "select
						*
					from
						`motorbike_cellphone_orders`
					where
						`memNo` = '".$memNo."'
					and
						`mcoType` = '".$mcoType."'";

			$data = $this->db->selectRecords($sql);
			return $data;
		}
        
        public function getRealCase($mcoNo,$mcoType){
			$sql = "select
						*
					from
						`real_cases`
					where
						`rcRelateDataNo` = '".$mcoNo."'
					and
						`rcType` = '".$mcoType."'";

			$data = $this->db->selectRecords($sql);

			return $data;
		}
	}
?>