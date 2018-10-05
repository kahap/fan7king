<?php
	class Advertise{
		var $db;
		var $areaArr = ["首頁幻燈片","首頁幻燈片旁","首頁最下方","分類左邊下方"];
		var $sizeArr = ["(建議大小666 x 450)","(建議大小234 x 450)","(建議大小570 x 120)","(建議大小270 x 345)"];
		
		//建構函式
		public function Advertise(){
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
		}
		
		
		//取得所有品牌
		public function getAll(){
			$sql = "select
						*
					from
						`advertise`
					order by
						`adNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//取得所有品牌(排序)
		public function getAllOrderBy($adArea,$limit){
			$sql = "select
						*
					from
						`advertise`
					where
						`adArea` = '".$adArea."'
					order by
						`adOrder`";
			if(is_numeric($limit)){
				$sql .= " LIMIT ".$limit;
			}		
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//編號取得單一品牌
		public function getOne($adNo){
			$sql = "select
						*
					from
						`advertise`
					where
						`adNo`=".$adNo;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		
		//新增
		function insert($array){
			foreach($array as $key =>$value){
				$$key = mysql_real_escape_string($value);
			}
			date_default_timezone_set('Asia/Taipei');
			$date = date('Y-m-d H:i:s', time());
			$sql = "insert into `advertise`(`adOrder`,`adImage`,`adArea`,`adLink`,`adDate` )
					values('".$adOrder."',
							'".$adImage."',
							'".$adArea."',
							'".$adLink."',
							'".$date."')";
			$insert = $this->db->insertRecords($sql);
			return $insert;
		}
		
		//編輯是否顯示
		public function updateIfShow($adIfShow,$adNo){
			$sql = "update
						`advertise`
					set
						`adIfShow`='".$adIfShow."'
					where
						`adNo`=".$adNo;
			
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯
		public function update($array,$adNo){
			foreach($array as $key =>$value){
				$$key = mysql_real_escape_string($value);
			}
			$sql = "update
						`advertise`
					set
						`adOrder`='".$adOrder."',
						`adImage`='".$adImage."',
						`adArea`='".$adArea."',
						`adLink`='".$adLink."',
						`adIfShow`='".$adIfShow."'
					where
						`adNo`='".$adNo."'";
			
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		
		//刪除
		function delete($adNo){
			$sql = "delete from `advertise` where `adNo` = ".$adNo;
			$delete = $this->db->deleteRecords($sql);
			return $delete;
		}
	}
?>