<?php
	class Co_Company{
		var $db;
		
		//建構函式
		public function Co_Company(){
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
		}
		
		//取得所有供應商
		public function getAll(){
			$sql = "select
						*
					from
						`co_company`
					order by
						`id`";
			$data = $this->db->selectRecords($sql);
				
			return $data;
		}
		
		
		//取得所有vip貸款
		public function get_loanAll(){
			$sql = "select
						*
					from
						`loan_vip`
					order by
						`id`";
			$data = $this->db->selectRecords($sql);
				
			return $data;
		}
		
		//編號取得單一vip貸款
		public function get_loanOne($id){
			$sql = "select
						*
					from
						`loan_vip`
					where
						`id`=".$id;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//編號取得單一供應商
		public function getOne($id){
			$sql = "select
						*
					from
						`co_company`
					where
						`id`=".$id;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//取得還沒選擇之供應商
		public function getAllSupExcept($idArr){
			$sql = "select
						*
					from
						`co_company`
					where ";
			foreach($idArr as $value){
				if($value != end($idArr)){
					$sql .= " NOT `id` = ".$value." AND ";
				}else{
					$sql .= " NOT `id` = ".$value;
				}
			}
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		
		//新增
		function insert($array){
			foreach($array as $key =>$value){
				$$key = $value;
			}
			$sql = "insert into `co_company`(`supName`, `supPhone` ,`supCell` ,`supAddr`,
					`supContactName`,`supFax`,`supStampImg`,`supEmail` )
					values('".$supName."',
							'".$supPhone."',
							'".$supCell."',
							'".$supAddr."',
							'".$supContactName."',
							'".$supFax."',
							'".$supStampImg."',
							'".$supEmail."')";
			$insert = $this->db->insertRecords($sql);
			return $insert;
		}
		
		//統一編輯
		public function update($array,$id){
			foreach($array as $key =>$value){
				$$key = $value;
			}
			$sql = "update
						`co_company`
					set
						`supName`='".$supName."',
						`supPhone`='".$supPhone."',
						`supCell`='".$supCell."',
						`supAddr`='".$supAddr."',
						`supContactName`='".$supContactName."',
						`supFax`='".$supFax."',
						`supStampImg`='".$supStampImg."',
						`supEmail`='".$supEmail."'
					where
						`id`=".$id;
			
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//刪除
		function delete($id){
			$sql = "delete from `co_company` where `id` = ".$id;
			$delete = $this->db->deleteRecords($sql);
			return $delete;
		}
	}
?>