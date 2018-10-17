<?php
	class Admin_Group
    {
		var $db;
		var $rightArr = array(
		    0=>"摘要資訊",
            10=>"會員管理-所有會員資訊",
            11=>"會員管理-機車老客戶查詢",
            12=>"會員管理-全體簡訊/Email發送",
            13=>"會員管理-推薦碼獎金撥款",
			14=>"會員資訊-可修改會員",
            15=>"會員資訊-推薦人清單",
            20=>"常見問題",
            30=>"供應商管理",
            40=>"商品管理",
            50=>"案件審查時間報表",
			60=>"分期進件:所有狀態",
            61=>"分期進件:未進件、審查中、補件狀態，客服訂單狀態僅能設【未進件、審查中、待補】",
            70=>"直購進件",
            80=>"權限管理",
            90=>"前台頁面編輯",
			100=>"發票號碼串接",
            110=>"其餘功能",
            111=>"更改版本號"
        );


		//建構函式
		public function Admin_Group()
        {
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
		}
		
		
		//取得所有分類
		public function getAllAG()
        {
			$sql = "select
						*
					from
						`admin_group`
					order by
						`agNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}


		//編號取得單一分類
		public function getOneAGByNo($agNo)
        {
			$sql = "select
						*
					from
						`admin_group`
					where
						`agNo`=".$agNo;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		
		//新增
		function insert($array)
        {
			foreach($array as $key =>$value){
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
			}
			date_default_timezone_set('Asia/Taipei');
			$date = date('Y-m-d H:i:s', time());
			$sql = "insert into `admin_group`(`agName`,`agRight`,`agDate` )
					values('".$agName."',
							'".$agRight."',
							'".$date."')";
			$insert = $this->db->insertRecords($sql);
			return $insert;
		}


		//編輯
		public function update($array,$agNo)
        {
			foreach($array as $key =>$value){
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
			}
			$sql = "update
						`admin_group`
					set
						`agName`='".$agName."',
						`agRight`='".$agRight."'
					where
						`agNo`='".$agNo."'";
			
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		
		//刪除
		function delete($agNo)
        {
			$sql = "delete from `admin_group` where `agNo` = ".$agNo;
			$delete = $this->db->deleteRecords($sql);
			return $delete;
		}
	}
?>