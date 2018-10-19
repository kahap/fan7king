<?php

	class Other_Setting
    {
		var $db;
		
		//建構函式
		public function __construct()
        {
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
		}
		
		
		//取得所有品牌
		public function getAll()
        {
			$sql = "select
						*
					from
						`other_setting`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		
		//編輯簡訊開關
		public function updateTextSwitch($onOrOff)
        {
			$sql = "update
						`other_setting`
					set
						`textSwitch`='".$onOrOff."'";
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯FB
		public function updateFbLink($fbLink)
        {
			$sql = "update
						`other_setting`
					set
						`fbLink`='".$fbLink."'";
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯瀏覽數
		public function updateViewCount($viewCount)
        {
			$sql = "update
						`other_setting`
					set
						`viewCount`='".$viewCount."'";
			$update = $this->db->updateRecords($sql);
			return $update;
		}
	}

?>