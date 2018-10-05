<?php
	class Hotkeys{
		var $db;
		
		//建構函式
		public function Hotkeys(){
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
		}
		
		
		//取得所有熱門字
		public function getAllHK(){
			$sql = "select
						*
					from
						`hotkeys`
					order by
						`hkNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//編號取得單一熱門字
		public function getOneHKByNo($hkNo){
			$sql = "select
						*
					from
						`hotkeys`
					where
						`hkNo`=".$hkNo;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		
		//新增
		function insert($array){
			foreach($array as $key =>$value){
				$$key = mysql_real_escape_string($value);
			}
			$sql = "insert into `hotkeys`(`hkKey`,`hkEnable` )
					values('".$hkKey."', 1)";
			$insert = $this->db->insertRecords($sql);
			return $insert;
		}
		
		//編輯進件狀態
		public function updateEnable($hkEnable,$hkNo){
			$sql = "update
						`hotkeys`
					set
						`hkEnable`='".$hkEnable."'
					where
						`hkNo`='".$hkNo."'";
				
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//刪除
		function delete($hkNo){
			$sql = "delete from `hotkeys` where `hkNo` = ".$hkNo;
			$delete = $this->db->deleteRecords($sql);
			return $delete;
		}
	}
?>