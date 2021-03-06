<?php
	class Recomm_Bonus_Success{
		var $db;
		
		//建構函式
		public function Recomm_Bonus_Success(){
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
			if($str["rbsStatus"] != ""){
				switch($str["rbsStatus"]){
					case 0:
						$str["rbsStatus"] = "未撥款";
						break;
					case 1:
						$str["rbsStatus"] = "已撥款";
						break;
				}
			}
		}
		
		
		//取得所有獎金
		public function getAllRBS(){
			$sql = "select
						*
					from
						`recomm_bonus_success`
					order by
						`rbsNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//取得所有獎金
		public function getAllrbano(){
			$sql = "select
						rbaNo
					from
						`recomm_bonus_success`
					order by
						`rbsNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//編號取得單一獎金
		public function getOneRBSByNo($rbsNo,$memNo){
			$sql = "select
						*
					from
						`recomm_bonus_success`
					where
						`rbsNo`='".$rbsNo."'&&
						`memNo`='".$memNo."'";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//根據會員申請讀取
		public function getRBSByMem($memNo){
			$sql = "select
						*
					from
						`recomm_bonus_success`
					where
						`memNo`=".$memNo;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//根據獎金狀態讀取
		public function getRBSByStatus($status){
			$sql = "select
						*
					from
						`recomm_bonus_success`
					where
						`rbsStatus`=".$status."
					order by `rbsNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		
		//新增
		function insert($array,$allRbsData,$user){
			foreach($array as $key =>$value){
				$$key = $value;
			}
			date_default_timezone_set('Asia/Taipei');
			$date = date('Y-m-d H:i:s', time());
			
			$count = json_decode($rbaNo);
			foreach($count as $k => $v){
				foreach($allRbsData as $key => $value){
					if($value['rbaNo'] == $v && $value['rbaRecMemNo'] == $user){
						$sql1 = "UPDATE `recomm_bonus_apply` SET `rbaExtract` = '1' where `rbaNo`='".$v."'";
						$this->db->updateRecords($sql1);
					}
					if($value['rbaNo'] == $v && $value['rbamemNo'] == $user){
						$sql2 = "UPDATE `recomm_bonus_apply` SET `memExtract` = '1' where `rbaNo`='".$v."'";
						$this->db->updateRecords($sql2);
					}
				}
				
			}
			$sql = "insert into `recomm_bonus_success`(`rbaNo`,`memNo`, `rbsTotal`,`rbsIdNum`,`rbsDate`)
					values('".$rbaNo."',
							'".$memNo."',
							'".$rbsTotal."',
							'".$rbsIdNum."',
							'".$date."')";
			$insert = $this->db->insertRecords($sql);
			return $insert;
		}
		
		//更改狀態
		public function updateStatus($rbsStatus,$rbsNo){
			$sql = "update
						`recomm_bonus_success`
					set
						`rbsStatus`='".$rbsStatus."'
					where
						`rbsNo`='".$rbsNo."'";
	
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//上傳
		public function updatDATA($array,$rbsNo){
			foreach($array as $key => $value){
				$$key = $value;
			}
			$sql = "update
						`recomm_bonus_success`
					set
						`rbsBankName` ='".$rbsBankName."',
						`rbsBankBranchName` ='".$rbsBankBranchName."',
						`rbsBankAcc` ='".$rbsBankAcc."',
						`rbsBankAccName` ='".$rbsBankAccName."'
					where
						`rbsNo`='".$rbsNo."'";
	
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		
		
		//上傳
		public function updateImage($name,$image_src,$rbsNo){
			$sql = "update
						`recomm_bonus_success`
					set
						".$name."='".$image_src."'
					where
						`rbsNo`='".$rbsNo."'";
	
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
// 		//統一編輯
// 		public function update($array,$rbaNo){
// 			foreach($array as $key =>$value){
// 				$$key = $value;
// 			}
// 			$sql = "update
// 						`recomm_bonus_success`
// 					set
// 						`orNo`=".$orNo.",
// 						`rbaRecMemNo`='".$rbaRecMemNo."',
// 						`rbsStatus`='".$rbsStatus."',
// 						`rbaTotal`='".$rbaTotal."'
// 					where
// 						`rbaNo`='".$rbaNo."'";
			
// 			$update = $this->db->updateRecords($sql);
// 			return $update;
// 		}
		
		
		//刪除推薦獎金
		function delete($rbaNo){
			$sql = "delete from `recomm_bonus_success` where `rbaNo` = ".$rbaNo;
			$delete = $this->db->deleteRecords($sql);
			return $delete;
		}
	}
?>