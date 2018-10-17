<?php
	class Qa_App{
		var $db;
		
		//建構函式
		public function Qa_App(){
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
		}
		
		//取得所有常見問題
		public function getAll(){
			$sql = "select
						*
					from
						`qa_app`
					order by
						`qaaOrder` asc";
			$data = $this->db->selectRecords($sql);
				
			return $data;
		}
		
		//編號取得單一常見問題
		public function getOne($qaaNo){
			$sql = "select
						*
					from
						`qa_app`
					where
						`qaaNo`=".$qaaNo;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		
		//新增
		function insert($array){
			foreach($array as $key =>$value){
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
			}
			date_default_timezone_set('Asia/Taipei');
			$date = date('Y-m-d h:i:s', time());
			$sql = "insert into `qa_app`(`qaaQues`,`qaaOrder`,`qaaDate`, `qaaAnsw`,`qaaIfShow` )
					values('".$qaaQues."',
							'".$qaaOrder."',
							'".$date."',
							'".$qaaAnsw."',
							'".$qaaIfShow."')";
			$insert = $this->db->insertRecords($sql);
			return $insert;
		}
		
		//編輯是否顯示
		public function updateIfShow($qaaIfShow,$qaaNo){
			$sql = "update
						`qa_app`
					set
						`qaaIfShow`='".$qaaIfShow."'
					where
						`qaaNo`=".$qaaNo;
			
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//統一編輯
		public function update($array,$qaaNo){
			foreach($array as $key =>$value){
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
			}
			$sql = "update
						`qa_app`
					set
						`qaaQues`='".$qaaQues."',
						`qaaOrder`='".$qaaOrder."',
						`qaaAnsw`='".$qaaAnsw."',
						`qaaIfShow`='".$qaaIfShow."'
					where
						`qaaNo`=".$qaaNo;
			
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//刪除
		function delete($qaaNo){
			$sql = "delete from `qa_app` where `qaaNo` = ".$qaaNo;
			$delete = $this->db->deleteRecords($sql);
			return $delete;
		}
	}
?>