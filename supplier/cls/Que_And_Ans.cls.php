<?php
	class Que_And_Ans{
		var $db;
		
		//建構函式
		public function Que_And_Ans(){
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
		}
		
		//取得所有常見問題
		public function getAllQA(){
			$sql = "select
						*
					from
						`que_and_ans`
					order by
						`qaOrder`";
			$data = $this->db->selectRecords($sql);
				
			return $data;
		}
		
		//編號取得單一常見問題
		public function getOneQAByNo($qaNo){
			$sql = "select
						*
					from
						`que_and_ans`
					where
						`qaNo`=".$qaNo;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		
		//新增
		function insert($array){
			foreach($array as $key =>$value){
				$$key = mysql_real_escape_string($value);
			}
			date_default_timezone_set('Asia/Taipei');
			$date = date('Y-m-d h:i:s', time());
			$sql = "insert into `que_and_ans`(`qaQues`,`qaOrder`,`qaDate`, `qaAnsw` )
					values('".$qaQues."',
							'".$qaOrder."',
							'".$date."',
							'".$qaAnsw."')";
			$insert = $this->db->insertRecords($sql);
			return $insert;
		}
		
		//編輯是否顯示
		public function updateIfShow($qaIfShow,$qaNo){
			$sql = "update
						`que_and_ans`
					set
						`qaIfShow`='".$qaIfShow."'
					where
						`qaNo`=".$qaNo;
			
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//統一編輯
		public function update($array,$qaNo){
			foreach($array as $key =>$value){
				$$key = mysql_real_escape_string($value);
			}
			$sql = "update
						`que_and_ans`
					set
						`qaQues`='".$qaQues."',
						`qaOrder`='".$qaOrder."',
						`qaAnsw`='".$qaAnsw."',
						`qaIfShow`='".$qaIfShow."'
					where
						`qaNo`=".$qaNo;
			
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//刪除
		function delete($qaNo){
			$sql = "delete from `que_and_ans` where `qaNo` = ".$qaNo;
			$delete = $this->db->deleteRecords($sql);
			return $delete;
		}
	}
?>