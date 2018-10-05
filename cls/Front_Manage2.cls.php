<?php
	class Front_Manage2{
		var $db;
		
		//建構函式
		public function Front_Manage2(){
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
		}
		
		
		//取得所有分類
		public function getAllFM($column){
			$sql = "select
						".$column."
					from
						`front_manage2`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//編輯
		public function update($key,$str){
			$sql = "update
						`front_manage2`
					set
						`".$key."`='".mysql_real_escape_string($str)."'";
			
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
	}
?>