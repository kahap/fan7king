<?php
	class School{
		var $db;
		
		//建構函式
		public function School(){
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
		}
		
		
		//取得所有熱門字
		public function getAll(){
			$sql = "select
						*
					from
						`school`
					order by
						`schNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//編號取得單一熱門字
		public function getOne($schNo){
			$sql = "select
						*
					from
						`school`
					where
						`schNo`=".$schNo;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//編號取得單一熱門字
		public function getWithName($schName){
			$sql = "select
						*
					from
						`school`
					where
						`schName`= '".$schName."'";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		
		//新增
		function insert($array){
			foreach($array as $key =>$value){
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
			}
			$sql = "insert into `school`(`schName` )
					values('".$schName."')";
			$insert = $this->db->insertRecords($sql);
			return $insert;
		}
		
		
		//刪除
		function delete($schNo){
			$sql = "delete from `school` where `schNo` = ".$schNo;
			$delete = $this->db->deleteRecords($sql);
			return $delete;
		}
	}
?>