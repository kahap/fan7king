<?php
	class Other_Setting{
		var $db;
		
		//建構函式
		public function Other_Setting(){
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
		}
		
		
		//取得所有品牌
		public function getAll(){
			$sql = "select
						*
					from
						`other_setting`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		
		//編輯簡訊開關
		public function updateTextSwitch($onOrOff){
			$sql = "update
						`other_setting`
					set
						`textSwitch`='".$onOrOff."'";
			
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯FB
		public function updateFbLink($fbLink){
			$sql = "update
						`other_setting`
					set
						`fbLink`='".$fbLink."'";
				
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯訂單天數
		public function updateOrdersDays($orderDays){
			$sql = "update
						`other_setting`
					set
						`orderLimitDays`='".$orderDays."'";
				
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯發票開立天數
		public function updateReceiptDays($receiptDays){
			$sql = "update
						`other_setting`
					set
						`receiptDays`='".$receiptDays."'";
		
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯發票開立天數
		public function updateVersion($ios,$android){
			$sql = "update
						`other_setting`
					set
						`iosVersion`='".$ios."',
						`androidVersion`='".$android."'";
		
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
	}
?>