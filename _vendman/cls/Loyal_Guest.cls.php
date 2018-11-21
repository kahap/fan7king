<?php
	class Loyal_Guest{
		var $db;
		
		//建構函式
		public function Loyal_Guest(){
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
		}
		
		//取得所有老客戶
		public function getAllLoyalGuest(){
			$sql = "select
						*
					from
						`loyal_guest`
					order by
						`lgNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		//取得所有老客戶並GROUP
		public function getAllLoyalGuestGroup(){
			$sql = "select
						*
					from
						`loyal_guest`
					group by
						`lgIdNum`
					order by
						`lgNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//編號取得單一老客戶
		public function getOneRBAByNo($lgNo){
			$sql = "select
						*
					from
						`loyal_guest`
					where
						`lgNo`=".$lgNo;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//根據身分證字號取得老客戶
		public function getRBAByStatus($idNum){
			$sql = "select
						*
					from
						`loyal_guest`
					where
						`lgIdNum`='".$idNum."'
					order by `lgNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		
		//新增
		function insert($lgIdNum){
			$sql = "insert into `loyal_guest`( `lgIdNum` )
					values('".$lgIdNum."')";
			$insert = $this->db->insertRecords($sql);
			return $insert;
		}
		
		//統一編輯
		public function update($lgIdNum,$lgNo){
			$sql = "update
						`loyal_guest`
					set
						`lgIdNum`='".$lgIdNum."'
					where
						`lgNo`='".$lgNo."'";
			
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//刪除
		function delete($lgNo){
			$sql = "delete from `loyal_guest` where `lgNo` = ".$lgNo;
			$delete = $this->db->deleteRecords($sql);
			return $delete;
		}
		
		//刪除
		function deleteByIdNum($lgIdNum){
			$sql = "delete from `loyal_guest` where `lgIdNum` = '".$lgIdNum."'";
			$delete = $this->db->deleteRecords($sql);
			return $delete;
		}
	}
?>