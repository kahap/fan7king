<?php
	class Brand{
		var $db;
		
		//建構函式
		public function Brand(){
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
		}
		
		
		//取得所有品牌
		public function getAllBrand(){
			$sql = "select
						*
					from
						`brand`
					order by
						`braNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//取得所有品牌
		public function getAllBrandOrder(){
			$sql = "select
						*
					from
						`brand`
					order by
						`braOrder`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//取得所有品牌(順序反)
		public function getAllBrandDesc(){
			$sql = "select
						*
					from
						`brand`
					order by
						`braNo`
					desc";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//編號取得單一品牌
		public function getOneBrandByNo($braNo){
			$sql = "select
						*
					from
						`brand`
					where
						`braNo`='".$braNo."'";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		
		//新增
		function insert($array,$braNo){
			foreach($array as $key =>$value){
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
			}
			date_default_timezone_set('Asia/Taipei');
			$date = date('Y-m-d H:i:s', time());
			$sql = "insert into `brand`(`braNo`,`braName`,`braOrder`,`braIfDisplay`,`braDate` )
					values('".$braNo."',
							'".$braName."',
							'".$braOrder."',
							'1',
							'".$date."')";
			$insert = $this->db->insertRecords($sql);
			return $insert;
		}
		
		//編輯
		public function update($array,$braNo){
			foreach($array as $key =>$value){
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
			}
			$sql = "update
						`brand`
					set
						`braName`='".$braName."',
						`braOrder`='".$braOrder."'
					where
						`braNo`='".$braNo."'";
			
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯
		public function updateDisplay($braIfDisplay,$braNo){
			$sql = "update
						`brand`
					set
						`braIfDisplay`='".$braIfDisplay."'
					where
						`braNo`='".$braNo."'";
				
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//刪除
		function delete($braNo){
			$sql = "delete from `brand` where `braNo` = ".$braNo;
			$delete = $this->db->deleteRecords($sql);
			return $delete;
		}
	}
?>