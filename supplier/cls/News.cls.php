<?php
	class News{
		var $db;
		
		//建構函式
		public function News(){
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
		}
		
		//取得所有最新消息
		public function getAllNews(){
			$sql = "select
						*
					from
						`news`
					order by
						`newsNo`";
			$data = $this->db->selectRecords($sql);
				
			return $data;
		}
		
		//編號取得單一最新消息
		public function getOneNewsByNo($newsNo){
			$sql = "select
						*
					from
						`news`
					where
						`newsNo`=".$newsNo;
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
			$sql = "insert into `news`(`newsTopic`, `newsDetails` ,`newsDate` )
					values('".$newsTopic."',
							'".$newsDetails."',
							'".$date."')";
			$insert = $this->db->insertRecords($sql);
			return $insert;
		}
		
		//統一編輯
		public function update($array,$newsNo){
			foreach($array as $key =>$value){
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
			}
			$sql = "update
						`news`
					set
						`newsTopic`='".$newsTopic."',
						`newsDetails`='".$newsDetails."',
						`newsDate`='".$newsDate."'
					where
						`newsNo`=".$newsNo;
			
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//刪除
		function delete($newsNo){
			$sql = "delete from `news` where `newsNo` = ".$newsNo;
			$delete = $this->db->deleteRecords($sql);
			return $delete;
		}
	}
?>