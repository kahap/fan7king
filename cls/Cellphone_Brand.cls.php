<?php
	class Cellphone_Brand {
		var $db;
		
		//建構函式
		public function Cellphone_Brand() {
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return true;
		}
		
		public function getAllCbName() {
			$sql = "select
						`cbName`
					from
						`cellphone_brand`;";
			$data = $this->db->selectRecords($sql);
			return $data;
		}

	}
?>