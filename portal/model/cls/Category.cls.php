<?php

	class Category
    {
		var $db;
		
		//建構函式
		public function __construct()
        {
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
		}
		
		
		//取得所有分類
		public function getAllCat(){
			$sql = "select
						*
					from
						`category`
					order by
						`catNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//取得所有分類(依照是否顯示 排序)
		public function getAllCatDisplayAndOrder(){
			$sql = "select
						*
					from
						`category`
					where
						`catIfDisplay` = 1
					order by
						`catOrder`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//取得所有分類(順序反)
		public function getAllCatDesc(){
			$sql = "select
						*
					from
						`category`
					order by
						`catNo`
					desc";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//編號取得單一分類
		public function getOneCatByNo($catNo){
			$sql = "select
						*
					from
						`category`
					where
						`catNo`='".$catNo."'";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		
		//新增
		function insert($array,$catNo){
			foreach($array as $key =>$value){
				$$key = $value;
			}
			date_default_timezone_set('Asia/Taipei');
			$date = date('Y-m-d h:i:s', time());
			$sql = "insert into `category`(`catNo`,`catName`,`catImage`,`catIcon`,`catOrder`,`catIfDisplay`,`catDate` )
					values('".$catNo."',
							'".$catName."',
							'".$catImage."',
							'".$catIcon."',
							'".$catOrder."',
							'1',
							'".$date."')";
			$insert = $this->db->insertRecords($sql);
			return $insert;
		}
		
		//編輯
		public function update($array,$catNo){
			foreach($array as $key =>$value){
				$$key = $value;
			}
			$sql = "update
						`category`
					set
						`catName`='".$catName."',
						`catOrder`='".$catOrder."',
						`catIfDisplay`='".$catIfDisplay."',
						`catImage`='".$catImage."',
						`catIcon`='".$catIcon."'
					where
						`catNo`='".$catNo."'";
			
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯
		public function updateDisplay($catIfDisplay,$catNo){
			$sql = "update
						`category`
					set
						`catIfDisplay`='".$catIfDisplay."'
					where
						`catNo`='".$catNo."'";
				
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//刪除
		function delete($catNo){
			$sql = "delete from `category` where `catNo` = ".$catNo;
			$delete = $this->db->deleteRecords($sql);
			return $delete;
		}
	}

?>