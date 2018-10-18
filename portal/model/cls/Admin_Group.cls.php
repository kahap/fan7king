<?php
	class Admin_Group{
		var $db;
		var $rightArr = array("摘要資訊","所有會員資訊","機車老客戶查詢","全體簡訊/Email發送","會員管理","常見問題",
				"供應商管理","商品管理","進件管理","權限管理","前台頁面編輯","其他管理功能");
		
		//建構函式
		public function Admin_Group(){
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
		}
		
		
		//取得所有分類
		public function getAllAG(){
			$sql = "select
						*
					from
						`admin_group`
					order by
						`agNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//編號取得單一分類
		public function getOneAGByNo($agNo){
			$sql = "select
						*
					from
						`admin_group`
					where
						`agNo`=".$agNo;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		
		//新增
		function insert($array){
			foreach($array as $key =>$value){
				$$key = $value;
			}
			date_default_timezone_set('Asia/Taipei');
			$date = date('Y-m-d h:i:s', time());
			$sql = "insert into `admin_group`(`agName`,`agRight`,`agDate` )
					values('".$agName."',
							'".$agRight."',
							'".$date."')";
			$insert = $this->db->insertRecords($sql);
			return $insert;
		}
		
		//編輯
		public function update($array,$agNo){
			foreach($array as $key =>$value){
				$$key = $value;
			}
			$sql = "update
						`admin_group`
					set
						`agName`='".$agName."',
						`agRight`='".$agRight."'
					where
						`agNo`='".$agNo."'";
			
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		
		//刪除
		function delete($agNo){
			$sql = "delete from `admin_group` where `agNo` = ".$agNo;
			$delete = $this->db->deleteRecords($sql);
			return $delete;
		}
	}
?>