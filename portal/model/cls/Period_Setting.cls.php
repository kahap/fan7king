<?php

	class Period_Setting
    {
		var $db;
		
		//建構函式
		public function __construct()
        {
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
		}
		
		
		//取得所有熱門字
		public function getAllPS(){
			$sql = "select
						*
					from
						`period_setting`
					order by
						`psNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//編號取得單一熱門字
		public function getOnePSByNo($psNo){
			$sql = "select
						*
					from
						`period_setting`
					where
						`psNo`=".$psNo;
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
			$sql = "insert into `period_setting`(`psMonthNum`,`psRatio`,`psOrder`,`psDate` )
					values('".$psMonthNum."',
							'".$psRatio."',
							'".$psOrder."',
							'".$date."')";
			$insert = $this->db->insertRecords($sql);
			return $insert;
		}
		
		//編輯
		public function update($array,$psNo){
			foreach($array as $key =>$value){
				$$key = $value;
			}
			$sql = "update
						`period_setting`
					set
						`psMonthNum`='".$psMonthNum."',
						`psRatio`='".$psRatio."',
						`psOrder`='".$psOrder."'
					where
						`psNo`='".$psNo."'";
				
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//刪除
		public function delete($psNo){
			$sql = "delete from `period_setting` where `psNo` = ".$psNo;
			$delete = $this->db->deleteRecords($sql);
			return $delete;
		}

		//取得特定的期數及利率 jimmy
		public function getSpecRatio($string) {
			$sql = "select
						`psMonthNum`, `psRatio`
					from
						`period_setting`
					where
						`psMonthNum` in (" . $string . ")
					order by `psMonthNum` asc";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
	}

?>