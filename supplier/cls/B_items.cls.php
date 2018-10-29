<?php
	class B_items{
		var $db;
		
		//建構函式
		public function __construct(){
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
		}
		
		
		//取得所有品牌
		public function getAllItems(){
			$sql = "select
						*
					from
						`b_items`
					order by
						`biNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//取得所有品牌
		public function getAllItemsOrder(){
			$sql = "select
						*
					from
						`b_items`
					order by
						`biOrder`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//取得所有品牌(順序反)
		public function getAllItemsDesc(){
			$sql = "select
						*
					from
						`b_items`
					order by
						`biNo`
					desc";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//編號取得單一品牌
		public function getOneItemsByNo($biNo){
			$sql = "select
						*
					from
						`b_items`
					where
						`biNo`='".$biNo."'";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		
		//新增
		function insert($array,$biNo){
			foreach($array as $key =>$value){
				$$key = mysqli_real_escape_string($this->db->oDbLink,$value);
			}
			date_default_timezone_set('Asia/Taipei');
			$date = date('Y-m-d H:i:s', time());
			$sql = "insert into `b_items`(`biNo`,`biName`,`biOrder`,`biShow`,`biDate` )
					values('".$biNo."',
							'".$biName."',
							'".$biOrder."',
							'1',
							'".$date."')";
			$insert = $this->db->insertRecords($sql);
			return $insert;
		}

		//編輯
		public function update($array,$biNo){
			foreach($array as $key =>$value){
				$$key = mysqli_real_escape_string($this->db->oDbLink,$value);
			}
			$sql = "update
						`b_items`
					set
						`biName`='".$biName."',
						`biOrder`='".$biOrder."'
					where
						`biNo`='".$biNo."'";

			$update = $this->db->updateRecords($sql);
			return $update;
		}

		//編輯
		public function updateDisplay($biIfDisplay,$biNo){
			$sql = "update
						`b_items`
					set
						`biShow`='".$biIfDisplay."'
					where
						`biNo`='".$biNo."'";
				
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//刪除
		function delete($biNo){
			$sql = "delete from `b_items` where `biNo` = ".$biNo;
			$delete = $this->db->deleteRecords($sql);
			return $delete;
		}
	}
?>