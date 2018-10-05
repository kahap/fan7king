<?php
	class Supplier{
		var $db;
		
		//建構函式
		public function Supplier(){
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
		}
		
		//取得所有供應商
		public function getAllSupplier(){
			$sql = "select
						*
					from
						`supplier`
					order by
						`supNo`";
			$data = $this->db->selectRecords($sql);
				
			return $data;
		}
		
		//編號取得單一供應商
		public function getOneSupplierByNo($supNo){
			$sql = "select
						*
					from
						`supplier`
					where
						`supNo`=".$supNo;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//取得還沒選擇之供應商
		public function getAllSupExcept($supNoArr){
			$sql = "select
						*
					from
						`supplier`
					where ";
			foreach($supNoArr as $value){
				if($value != end($supNoArr)){
					$sql .= " NOT `supNo` = ".$value." AND ";
				}else{
					$sql .= " NOT `supNo` = ".$value;
				}
			}
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		
		//新增
		function insert($array){
			foreach($array as $key =>$value){
				$$key = mysql_real_escape_string($value);
			}
			$sql = "insert into `supplier`(`supName`, `supPhone` ,`supCell` ,`supAddr`,
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
			$branch = new SupplierBranch();
			$branch->insertPartial($insert,$supName.'001',$insert,"1234");
			return $insert;
		}
		
		//統一編輯
		public function update($array,$supNo){
			foreach($array as $key =>$value){
				$$key = mysql_real_escape_string($value);
			}
			$sql = "update
						`supplier`
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
						`supNo`=".$supNo;
			
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//刪除
		function delete($supNo){
			$sql = "delete from `supplier` where `supNo` = ".$supNo;
			$delete = $this->db->deleteRecords($sql);
			return $delete;
		}
	}
?>