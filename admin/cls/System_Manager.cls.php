<?php
	class System_Manager{
		var $db;
		var $rightArr = array(
		    "摘要資訊",
            "會員管理",
            "常見問題",
            "供應商管理",
            "商品管理"
				,"進件管理",
            "權限管理",
            "前台頁面編輯",
            "其他管理功能"
        );
		
		//建構函式
		public function System_Manager(){
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
		}
		
		
		//取得所有分類
		public function getAllSM(){
			$sql = "select
						*
					from
						`system_manager`
					order by
						`smNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//編號取得單一分類
		public function getOneSMByNo($smNo){
			$sql = "select
						*
					from
						`system_manager`
					where
						`smNo`=".$smNo;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		
		//新增
		function insert($array){
			foreach($array as $key =>$value){
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
			}
			date_default_timezone_set('Asia/Taipei');
			$date = date('Y-m-d H:i:s', time());
			$sql = "insert into `system_manager`(`agNo`,`smAccount`,`smPwd`,
					`smName`,`smPhone`,`smEmail`,`smComment`,`smDate` )
					values('".$agNo."',
							'".$smAccount."',
							'".$smPwd."',
							'".$smName."',
							'".$smPhone."',
							'".$smEmail."',
							'".$smComment."',
							'".$date."')";
			$insert = $this->db->insertRecords($sql);
			return $insert;
		}
		
		//編輯
		public function update($array,$smNo){
			foreach($array as $key =>$value){
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
			}
			$sql = "update
						`system_manager`
					set
						`agNo`='".$agNo."',
						`smAccount`='".$smAccount."',
						`smPwd`='".$smPwd."',
						`smName`='".$smName."',
						`smPhone`='".$smPhone."',
						`smEmail`='".$smEmail."',
						`smComment`='".$smComment."'
					where
						`smNo`=".$smNo;
			
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯最後登入時間+IP
		public function updateIpAndTime($smLastIp,$smLastTime,$smNo){
			$sql = "update
						`system_manager`
					set
						`smLastIp`='".$smLastIp."',
						`smLastTime`='".$smLastTime."'
					where
						`smNo`='".$smNo."'";
				
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		
		//刪除
		function delete($smNo){
			$sql = "delete from `system_manager` where `smNo` = ".$smNo;
			$delete = $this->db->deleteRecords($sql);
			return $delete;
		}
	}
?>