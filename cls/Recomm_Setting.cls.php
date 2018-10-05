<?php
	class Recomm_Setting{
		var $db;
		
		//建構函式
		public function Recomm_Setting(){
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
		}
		
		//取得設定
		public function getSetting(){
			$sql = "select
						*
					from
						`recomm_setting`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//編輯
		public function update($array){
			foreach($array as $key =>$value){
				$$key = $value;
			}
			$sql = "update
						`recomm_setting`
					set
						`rsYearOption`='".$rsYearOption."',
						`rsTotalPerOrder`='".$rsTotalPerOrder."',
						`rsDaysLimit`='".$rsDaysLimit."',
						`rsMinimum`='".$rsMinimum."',
						`rsCharge`='".$rsCharge."',
						`rsShareText`='".$rsShareText."'";
			
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		
	}
?>