<?php
	class Major{
		var $db;
		
		//建構函式
		public function Major(){
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
		}
		
		
		//取得所有熱門字
		public function getAll(){
			$sql = "select
						*
					from
						`major`
					order by
						`majNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//編號取得單一熱門字
		public function getOne($majNo){
			$sql = "select
						*
					from
						`major`
					where
						`majNo`=".$majNo;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		
		//新增
		function insert($array){
			foreach($array as $key =>$value){
				$$key = mysql_real_escape_string($value);
			}
			$sql = "insert into `major`(`schNo`,`majName` )
					values('".$schNo."',
							'".$majName."')";
			$insert = $this->db->insertRecords($sql);
			return $insert;
		}
		
		
		//刪除
		function delete($majNo){
			$sql = "delete from `major` where `majNo` = ".$majNo;
			$delete = $this->db->deleteRecords($sql);
			return $delete;
		}
	}
?>