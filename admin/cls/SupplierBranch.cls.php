<?php
	class SupplierBranch{
		var $db;
		
		//建構函式
		public function SupplierBranch(){
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
		}
		
		//取得所有供應商
		public function getAllBranchBySupplier($no){
			$sql = "select
						*
					from
						`supplier_sales`
					LEFT JOIN admin_advanced_user ON supplier_sales.aauNo = admin_advanced_user.aauNo
					where supNo='".$no."'
					order by
						`supNo`";
			$data = $this->db->selectRecords($sql);
				
			return $data;
		}
		
		public function getAllSales(){
			$sql = "select
						*
					from
						admin_advanced_user
					where aarNo LIKE '%12%'
					order by aauNo";
			$data = $this->db->selectRecords($sql);
				
			return $data;
		}
		
		
		//編號取得單一供應商
		public function getOneBranchByNo($no){
			$sql = "select
						*
					from
						`supplier_sales`
					where
						`ssNo`=".$no;
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
		function insert($array,$supNo){
			foreach($array as $key =>$value){
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
			}
			$sql = "insert into `supplier_sales`(`supNo`, `ssName` ,`ssLogId` ,`ssPwd`,`aauNo`)
					values('".$supNo."',
							'".$ssName."',
							'".$ssLogId."',
							'".$ssPwd."',
							'".$aauNo."')";
			$insert = $this->db->insertRecords($sql);
			return $insert;
		}
		function insertPartial($supNo,$ssName,$ssLogId,$ssPwd){
			$sql = "insert into `supplier_sales`(`supNo`, `ssName` ,`ssLogId` ,`ssPwd`)
					values('".$supNo."',
							'".$ssName."',
							'".$ssLogId."',
							'".$ssPwd."')";
			$insert = $this->db->insertRecords($sql);
			return $insert;
		}
				
		//統一編輯
		public function update($array,$ssNo){
			foreach($array as $key =>$value){
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
			}
			$sql = "update
						`supplier_sales`
					set
						`ssNo`='".$ssNo."',
						`ssName`='".$ssName."',
						`ssPwd`='".$ssPwd."',
						`aauNo` ='".$aauNo."'
					where
						`ssNo`=".$ssNo;
			
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