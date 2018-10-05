<?php
	class Status_Record{
		var $db;
		var $statusArr = array("已下單，Email未驗證","未進件","審查中","核准","婉拒",
				"補件","取消訂單","出貨中","已出貨","已完成",
				"換貨中","退貨中","完成退貨");
		var $statusDirectArr = array("處理中","取消訂單","出貨中","已收貨","已完成",
				"換貨中","退貨中","完成退貨");
		
		//建構函式
		public function Status_Record(){
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
		}
		
		//將代號轉換
		public function changeToReadable(&$str){
			foreach($str as $key=>&$value){
				if($value == ""){
					$value = "無";
				}
				if($key == "srOrStatus"){
					foreach($this->statusArr as $keyIn=>$valueIn){
						if($value == $keyIn){
							$value = $valueIn;
						}
					}
				}
			}
		}
		
		
		//取得所有紀錄
		public function getAllSR(){
			$sql = "select
						*
					from
						`status_record`
					order by
						`srNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//編號取得單一紀錄
		public function getOneSRByNo($srNo){
			$sql = "select
						*
					from
						`status_record`
					where
						`srNo`=".$srNo;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//進件狀態取得紀錄
		public function getSRByStatus($srOrStatus){
			$sql = "select
						*
					from
						`status_record`
					where
						`srOrStatus`=".$srOrStatus;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//是否維持此狀態取得紀錄
		public function getSRByIfStay($srIfStay){
			$sql = "select
						*
					from
						`status_record`
					where
						`srIfStay`=".$srIfStay;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//進件狀態AND是否維持取得紀錄
		public function getSRByStatusAndIfStay($srOrStatus,$srIfStay){
			$sql = "select
						*
					from
						`status_record`
					where
						`srOrStatus`=".$srOrStatus."
					and
						`srIfStay`=".$srIfStay;
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
			$sql = "insert into `status_record`(`orNo`, `srOrStatus`, `srProcessTime`, `srIfStay`  )
					values('".$orNo."',
							'".$srOrStatus."',
							'".$date."',
							'1')";
			$insert = $this->db->insertRecords($sql);
			return $insert;
		}
		
		//編輯
		public function update($array,$srNo){
			foreach($array as $key =>$value){
				$$key = $value;
			}
			$sql = "update
						`status_record`
					set
						`orNo`='".$orNo."',
						`srOrStatus`='".$srOrStatus."',
						`srProcessTime`='".$srProcessTime."',
						`srIfStay`='".$srIfStay."'
					where
						`srNo`='".$srNo."'";
			
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//刪除
		function delete($srNo){
			$sql = "delete from `status_record` where `srNo` = ".$srNo;
			$delete = $this->db->deleteRecords($sql);
			return $delete;
		}
	}
?>