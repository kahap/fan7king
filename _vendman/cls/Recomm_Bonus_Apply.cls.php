<?php
	class Recomm_Bonus_Apply{
		var $db;
		
		//建構函式
		public function Recomm_Bonus_Apply(){
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
		}
		
		//將代號轉換
		public function changeToReadable(&$str){
			foreach($str as $key=>&$value){
				if($value == ""){
					$value = "無";
				}
			}
			if($str["rbaStatus"] != ""){
				switch($str["rbaStatus"]){
					case 0:
						$str["rbaStatus"] = "審核中";
						break;
					case 1:
						$str["rbaStatus"] = "可累計";
						break;
					case 2:
						$str["rbaStatus"] = "婉拒";
						break;
					case 3:
						$str["rbaStatus"] = "已領取";
						break;
				}
			}
		}
		
		
		//取得所有獎金
		public function getAllRBA(){
			$sql = "select
						*
					from
						`recomm_bonus_apply`
					order by
						`rbaNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//編號取得單一獎金
		public function getOneRBAByNo($rbaNo){
			$sql = "select
						*
					from
						`recomm_bonus_apply`
					where
						`rbaNo`=".$rbaNo;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//訂單編號取得單一獎金
		public function getOneRBAByOrNo($orNo){
			$sql = "select
						*
					from
						`recomm_bonus_apply`
					where
						`orNo`=".$orNo;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//被推薦之會員之會員編號
		public function getRBAByMemNo($rbaRecMemNo){
			$sql = "select
						*
					from
						`recomm_bonus_apply`
					inner join 
						`orders`
					on
						`recomm_bonus_apply`.`orNo` = `orders`.`orNo`
					where
						`recomm_bonus_apply`.`rbaRecMemNo`='".$rbaRecMemNo."'
					or
						`orders`.`memNo` = '".$rbaRecMemNo."'";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//根據獎金狀態讀取
		public function getRBAByStatus($status){
			$sql = "select
						*
					from
						`recomm_bonus_apply`
					where
						`rbaStatus`=".$status."
					order by `rbaNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		
		//新增
		function insert($array){
			foreach($array as $key =>$value){
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
			}
			date_default_timezone_set('Asia/Taipei');
			$date = date('Y-m-d H:i:s', time());
			$sql = "insert into `recomm_bonus_apply`(`orNo`, `rbaRecMemNo` ,`rbaStatus`,`rbaTotal`,
					`rbaDate` )
					values('".$orNo."',
							'".$rbaRecMemNo."',
							'".$rbaStatus."',
							'".$rbaTotal."',
							".$date.")";
			$insert = $this->db->insertRecords($sql);
			return $insert;
		}
		
		//更改狀態
		public function updateStatus($rbaStatus,$rbaNo){
			$sql = "update
						`recomm_bonus_apply`
					set
						`rbaStatus`='".$rbaStatus."'
					where
						`rbaNo`='".$rbaNo."'";
	
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
// 		//統一編輯
// 		public function update($array,$rbaNo){
// 			foreach($array as $key =>$value){
// 				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
// 			}
// 			$sql = "update
// 						`recomm_bonus_apply`
// 					set
// 						`orNo`=".$orNo.",
// 						`rbaRecMemNo`='".$rbaRecMemNo."',
// 						`rbaStatus`='".$rbaStatus."',
// 						`rbaTotal`='".$rbaTotal."'
// 					where
// 						`rbaNo`='".$rbaNo."'";
			
// 			$update = $this->db->updateRecords($sql);
// 			return $update;
// 		}
		
		
		//刪除推薦獎金
		function delete($rbaNo){
			$sql = "delete from `recomm_bonus_apply` where `rbaNo` = ".$rbaNo;
			$delete = $this->db->deleteRecords($sql);
			return $delete;
		}
	}
?>