<?php

	class Member
    {
		var $db;

		//建構函式
		public function __construct()
        {
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
		}

		//將代號轉換
		public function changeToReadable(&$str)
        {
			foreach($str as $key=>&$value){
				if($value == ""){
					$value = "無";
				}
			}
			if($str["memClass"] != ""){
				switch($str["memClass"]){
					case 0:
						$str["memClass"] = "學生";
						break;
					case 1:
						$str["memClass"] = "上班族";
						break;
					case 2:
						$str["memClass"] = "家管";
						break;
					case 3:
						$str["memClass"] = "其他";
						break;
					case 4:
						$str["memClass"] = "非學生";
						break;
				}
			}
			if($str["memGender"] != ""){
				switch($str["memGender"]){
					case 0:
						$str["memGender"] = "女";
						break;
					case 1:
						$str["memGender"] = "男";
						break;
				}
			}
			if($str["memRegistMethod"] != ""){
				switch($str["memRegistMethod"]){
					case 0:
						$str["memRegistMethod"] = "FB連結";
						break;
					case 1:
						$str["memRegistMethod"] = "一般";
						break;
				}
			}
			if($str["memAllowLogin"] != ""){
				switch($str["memAllowLogin"]){
					case 0:
						$str["memAllowLogin"] = "停權";
						break;
					case 1:
						$str["memAllowLogin"] = "允許";
						break;
				}
			}
			if($str["memEmailAuthen"] != ""){
				switch($str["memEmailAuthen"]){
					case 0:
						$str["memEmailAuthen"] = "尚未";
						break;
					case 1:
						$str["memEmailAuthen"] = "通過";
						break;
				}
			}
		}


		//將文字轉換代號
		public function changeToData(&$str){
			foreach($str as $key=>&$value){
				if($value == "無"){
					$value = "";
				}
			}
			if($str["memClass"] != ""){
				switch($str["memClass"]){
					case "學生":
						$str["memClass"] = 0;
						break;
					case "上班族":
						$str["memClass"] = 1;
						break;
					case "家管":
						$str["memClass"] = 2;
						break;
					case "其他":
						$str["memClass"] = 3;
						break;
				}
			}
			if($str["memGender"] != ""){
				switch($str["memGender"]){
					case "女":
						$str["memGender"] = 0;
						break;
					case "男":
						$str["memGender"] = 1;
						break;
				}
			}
			if($str["memRegistMethod"] != ""){
				switch($str["memRegistMethod"]){
					case "FB連結":
						$str["memRegistMethod"] = 0;
						break;
					case "一般":
						$str["memRegistMethod"] = 1;
						break;
				}
			}
			if($str["memAllowLogin"] != ""){
				switch($str["memAllowLogin"]){
					case "停權":
						$str["memAllowLogin"] = 0;
						break;
					case "允許":
						$str["memAllowLogin"] = 1;
						break;
				}
			}
			if($str["memEmailAuthen"] != ""){
				switch($str["memEmailAuthen"]){
					case "尚未":
						$str["memEmailAuthen"] = 0;
						break;
					case "通過":
						$str["memEmailAuthen"] = 1;
						break;
				}
			}
		}

		//取得推薦該會員人數
		public function getRecommTotalForMember($memNo){
			$sql = "select
						*
					from
						member
					where
						`memRecommCode`= '".$memNo."'";
			$data = $this->db->selectRecords($sql);

			return $data;
		}

		//取得所有會員
		public function getAllMember(){
			$sql = "select
						*
					from
						member
					order by
						`memNo`";
			$data = $this->db->selectRecords($sql);

			return $data;
		}

		//取得所有會員
		public function getAllMemberMonthly($dateStart,$dateEnd){
			$sql = "select
						*
					from
						member
					where
						DATE(`memRegistDate`) between '".$dateStart."' and '".$dateEnd."'
					order by
						`memNo`";
			$data = $this->db->selectRecords($sql);

			return $data;
		}

		//取得FB/一半會員
		public function getFbOrNormalMember($memRegistMethod){
			$sql = "select
						*
					from
						member
					where
						`memRegistMethod`=".$memRegistMethod;
			$data = $this->db->selectRecords($sql);

			return $data;
		}


		//編號取得單一會員
		public function getOneMemberByNo($memNo){
			$sql = "select
						*
					from
						`member`
					where
						`memNo`='".$memNo."'";
			$data = $this->db->selectRecords($sql);
			return $data;
		}

		//停權or啟用 會員
		public function changeMemberLoginable($disOrEn,$memNo){
			$sql = "update
						`member`
					set
						`memAllowLogin`=".$disOrEn."
					where
						`memNo`=".$memNo;
			$update = $this->db->updateRecords($sql);
			return $update;
		}

		//新增
		function insert($array){
			foreach($array as $key =>$value){
				$$key = $value;
			}
			date_default_timezone_set('Asia/Taipei');
			$date = date('Y-m-d H:i:s', time());
			$sql = "insert into `member`(`memClass`, `memSchool` ,`memOther`,`memSubEmail` ,`memAccount`,
					`memPwd`,`memName`,`memGender`,`memBday`,`memIdNum`,`memAddr`,`memPhone`,`memCell`,
					`memLineId`,`memRegistMethod`,`memFBtoken`,`memRecommCode`,`memAllowLogin`,`memEmailAuthen`,
					`memRegistDate`,`pass_number` )
					values('".$memClass."',
							'".$memSchool."',
							'".$memOther."',
							'".$memSubEmail."',
							'".$memAccount."',
							'".$memPwd."',
							'".$memName."',
							'".$memGender."',
							'".$memBday."',
							'".$memIdNum."',
							'".$memAddr."',
							'".$memPhone."',
							'".$memCell."',
							'".$memLineId."',
							'".$memRegistMethod."',
							'".$memFBtoken."',
							'".$memRecommCode."',
							'".$memAllowLogin."',
							'".$memEmailAuthen."',
							'".$date."',
							'".$pass_number."')";
			$insert = $this->db->insertRecords($sql);
			return mysqli_insert_id($this->db->oDbLink );
		}

		//統一編輯
		public function updateMember($array,$memNo){
			foreach($array as $key =>$value){
				$$key = $value;
			}
			$sql = "update
						`member`
					set
						`memClass`=".$memClass.",
						`memSchool`='".$memSchool."',
						`memOther`='".$memOther."',
						`memAccount`='".$memAccount."',
						`memPwd`='".$memPwd."',
						`memName`='".$memName."',
						`memGender`=".$memGender.",
						`memBday`='".$memBday."',
						`memIdNum`='".$memIdNum."',
						`memAddr`='".$memAddr."',
						`memPhone`='".$memPhone."',
						`memCell`='".$memCell."',
						`memLineId`='".$memLineId."',
						`memRegistMethod`=".$memRegistMethod.",
						`memFBtoken`='".$memFBtoken."',
						`memRecommCode`='".$memRecommCode."',
						`memAllowLogin`=".$memAllowLogin.",
						`memEmailAuthen`=".$memEmailAuthen."
					where
						`memNo`='".$memNo."'";

			$update = $this->db->updateRecords($sql);
			return $update;
		}

		//統一編輯
		public function updateforOrdMember($array,$memNo){
			foreach($array as $key =>$value){
				$$key = $value;
			}
			$sql = "update
						`member`
					set
						`memClass`=".$memClass.",
						`memSchool`='".$memSchool."',
						`memOther`='".$memOther."',
						`memAccount`='".$memAccount."',
						`memName`='".$memName."',
						`memGender`=".$memGender.",
						`memBday`='".$memBday."',
						`memIdNum`='".$memIdNum."',
						`memAddr`='".$memAddr."',
						`memPhone`='".$memPhone."',
						`memCell`='".$memCell."',
						`memSubEmail` ='".$memSubEmail."'
					where
						`memNo`='".$memNo."'";

			$update = $this->db->updateRecords($sql);
			return $update;
		}

		//刪除會員
		function delete($memNo){
			$sql = "delete from `member` where `memNo` = ".$memNo;
			$delete = $this->db->deleteRecords($sql);
			return $delete;
		}

		//編輯EMAIL
		public function updateMemberEmail($fbEmail,$memNo){
			$sql = "update
						`member`
					set
						`memSubEmail`=".$fbEmail."
					where
						`memNo`='".$memNo."'";

			$update = $this->db->updateRecords($sql);
			return $update;
		}

		//編輯gender
		public function updateMemberGender($gender,$memNo){
			$sql = "update
						`member`
					set
						`memGender`=".$gender."
					where
						`memNo`='".$memNo."'";

			$update = $this->db->updateRecords($sql);
			return $update;
		}

		//判斷是否是會員
		public function getMemberinformation($array){
			foreach($array as $key => $value){
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
			}
			$sql = "select
						*
					from
						`member`
					where
						`memAccount`='".$memAccount."'&&
						`memPwd` = '".$memPwd."' &&
						`memFBtoken` = ''";

			$data = $this->db->selectRecords($sql);
			return $data[0];
		}

        // jimmy 新版前台用手機號碼登入
        public function getMemberinformationWithCell($array){
            foreach ($array as $key => $value) {
                $$key = mysqli_real_escape_string($this->db->oDbLink, $value);
            }
            $sql = "select
                            *
                        from
                            `member`
                        where
                            `memCell` = '" . $memCell . "'&&
                            `memPwd` = '" . $memPwd . "'";

            $data = $this->db->selectRecords($sql);
            return $data[0];
        }

		// jimmy
		public function getMemberinformationNew($array){
			foreach ($array as $key => $value) {
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
			}
			$sql = "select
						*
					from
						`member`
					where
						`memIdNum` = '" . $memIdNum . "'&&
						`memPwd` = '" . $memPwd . "'";

			$data = $this->db->selectRecords($sql);
			return $data[0];
		}

		// sander
		public function getMemberinformationNewApp($array){
			foreach ($array as $key => $value) {
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
			}
			$sql = "select
						*
					from
						`member`
					where
						`memIdNum` = '" . $memAccount . "'&&
						`memPwd` = '" . $memPwd . "'";

			$data = $this->db->selectRecords($sql);
			return $data[0];
		}

		//判斷是否認證
		public function getMembermemEmailAuthen($array){
			foreach($array as $key => $value){
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
			}
			$sql = "select
						*
					from
						`member`
					where
						`pass_number`='".$pass_number."'&&
						memNo = '".$memNo."'";
			$data = $this->db->selectRecords($sql);
			return $data[0];
		}

		//前台編輯
		public function update_memEmailAuthen($memNo){
			$date = date('Y-m-d H:i:s',time());
			$sql = "update
						`member`
					set
						`memEmailAuthen`= '1',
						`identify_time` = '".$date."'
					where
						`memNo`='".$memNo."'";

			$update = $this->db->updateRecords($sql);
			return $update;
		}

		//前台編輯
		public function update_fornMember($array,$memNo){
			foreach($array as $key =>$value){
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
			}
			if($memName != ''){
			$sql = "update
						`member`
					set
						`memClass`=".$memClass.",
						`memSchool`='".$memSchool."',
						`memOther`='".$memOther."',
						`memAccount`='".$memAccount."',
						`memName`='".$memName."',
						`memGender`=".$memGender.",
						`memBday`='".$memBday."',
						`memIdNum`='".$memIdNum."',
						`memAddr`='".$memAddr."',
						`memPhone`='".$memPhone."',
						`memCell`='".$memCell."',
						`memSubEmail` ='".$memSubEmail."'
					where
						`memNo`='".$memNo."'";
			}else{
				$sql = "update
						`member`
							set
								`memClass`=".$memClass.",
								`memSchool`='".$memSchool."',
								`memOther`='".$memOther."',
								`memAccount`='".$memAccount."',
								`memGender`=".$memGender.",
								`memAddr`='".$memAddr."',
								`memPhone`='".$memPhone."',
								`memCell`='".$memCell."',
								`memSubEmail` ='".$memSubEmail."'
							where
								`memNo`='".$memNo."'";
			}
			$update = $this->db->updateRecords($sql);
			return $update;
		}

        //編輯身份證號
		public function update_Member_IdNum($array,$memNo){
			foreach($array as $key =>$value){
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
			}

            $sql = "update
						`member`
					set
						`memName`='".$memName."',
						`memIdNum` ='".$memIdNum."'
					where
						`memNo`='".$memNo."'";

			$update = $this->db->updateRecords($sql);
			return $update;
		}
		//前台編輯
		public function update_fornMember_FB($array,$memNo){
			foreach($array as $key =>$value){
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
			}
			if($memName != ''){
				$sql = "update
						`member`
					set
						`memName` = '".$memName."',
						`memClass`=".$memClass.",
						`memSchool`='".$memSchool."',
						`memOther`='".$memOther."',
						`memAccount`='".$memAccount."',
						`memSubEmail` = '".$memSubEmail."',
						`memGender`=".$memGender.",
						`memBday`='".$memBday."',
						`memIdNum`='".$memIdNum."',
						`memAddr`='".$memAddr."',
						`memPhone`='".$memPhone."',
						`memCell`='".$memCell."',
						`memLineId`='".$memLineId."'
					where
						`memNo`='".$memNo."'";
			}else{
				$sql = "update
						`member`
					set
						`memClass`=".$memClass.",
						`memSchool`='".$memSchool."',
						`memOther`='".$memOther."',
						`memAccount`='".$memAccount."',
						`memSubEmail` = '".$memSubEmail."',
						`memGender`=".$memGender.",
						`memAddr`='".$memAddr."',
						`memPhone`='".$memPhone."',
						`memCell`='".$memCell."',
						`memLineId`='".$memLineId."'
					where
						`memNo`='".$memNo."'";
			}
			$update = $this->db->updateRecords($sql);
			return $update;
		}

		//忘記密碼使用account判斷
		public function getMemberforgetdata($memAccount){
			$memAccount = mysqli_real_escape_string($this->db->oDbLink, $memAccount);
			$sql = "select
						*
					from
						`member`
					where
						`memAccount`='".$memAccount."' &&
						`memFBtoken` = ''";
			$data = $this->db->selectRecords($sql);
			return $data[0];
		}

		public function check_id($memIdNum){
			$memIdNum = mysqli_real_escape_string($this->db->oDbLink, $memIdNum);
			$sql = "select
						count(*) as count
					from
						`member`
					where
						`memIdNum`='".$memIdNum."'";
			$data = $this->db->selectRecords($sql);
			if($data[0]['count'] > 0) return "1";
		}

		public function check_account($memAccount){
			$memAccount = mysqli_real_escape_string($this->db->oDbLink, $memAccount);
			$sql = "select
						count(*) as count
					from
						`member`
					where
						`memAccount`='".$memAccount."'";
			$data = $this->db->selectRecords($sql);
			if($data[0]['count'] > 0) return "1";
		}
		public function check_FBtoken($memFBtoken){
			$memAccount = mysqli_real_escape_string($this->db->oDbLink, $memAccount);
			$sql = "select
						*
					from
						`member`
					where
						`memFBtoken`='".$memFBtoken."'";
			$data = $this->db->selectRecords($sql);
			return $data[0];
		}

		public function memRecommCode($memRecommCode){
			$memRecommCode = mysqli_real_escape_string($this->db->oDbLink, $memRecommCode);
			$sql = "SELECT `memNo` FROM `member` WHERE `memNo` = '".$memRecommCode."'";
			$data = $this->db->selectRecords($sql);
			if($data[0]['count'] > 0) return "1";
		}
		public function GetmemRecommCodeList($memRecommCode){
			$memRecommCode = mysqli_real_escape_string($this->db->oDbLink, $memRecommCode);
			$sql = "SELECT * FROM `member` WHERE `memRecommCode` = '".$memRecommCode."'";
			$data = $this->db->selectRecords($sql);
			return $data;
		}


		public function insert_FBtoken($array){
			foreach($array as $key => $value){
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
			}
			date_default_timezone_set('Asia/Taipei');
			$date = date('Y-m-d H:i:s', time());
			$sql = "insert into `member`(`memGender`,`memName`,`memRegistMethod`,`memSubEmail`,`memFBtoken`,`memAllowLogin`,
					`memRegistDate`,`memRecommCode`,`memEmailAuthen`,`pass_number`)
					values(	'".$memGender."',
							'".$memName."',
							'0',
							'".$memSubEmail."',
							'".$memFBtoken."',
							'1',
							'".$date."',
							'".$memRecommCode."',
							'0',
							'".$pass_number."')";
			$insert = $this->db->insertRecords($sql);
			return mysqli_insert_id($this->db->oDbLink );
		}

		//前台編輯
		public function update_fornMember_password($array,$memNo){
			foreach($array as $key =>$value){
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
			}
			$sql = "update
						`member`
					set
						`memPwd`='".$NewmemPwd."'
					where
						`memNo`='".$memNo."'";

			$update = $this->db->updateRecords($sql);
			return $update;
		}
		//前台編輯
		public function updatememAddr($str,$memNo){
			foreach($str as $k =>$v){
				$name = $k;
				$value = mysqli_real_escape_string($this->db->oDbLink, $v);
			}
			$sql = "update
						`member`
					set
						`".$name."`='".$value."'
					where
						`memNo`='".$memNo."'";

			$update = $this->db->updateRecords($sql);
			return $update;
		}

		public function update_information_stu($array,$memNo){
			foreach($array as $key =>$value){
				if($key != "memSchool"){
					$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
				}
			}

			$sql = "update
						`member`
					set
						`memClass`='0',
						`edit` = '1',
						`edit_time` = '".date("Y-m-d H:i:s",time())."'

					where
						`memNo`='".$memNo."'";

			$update = $this->db->updateRecords($sql);
			return $update;
		}

		public function updaterealcasePostcode($str){
			$update = $this->db->updateRecords($str);
			return $update;
		}

		public function update_information_emy($array,$memNo){
			foreach($array as $key =>$value){
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
			}

			$sql = "update
						`member`
					set
						`memClass`='4',
						`edit` = '1',
						`edit_time` = '".date("Y-m-d H:i:s",time())."'

					where
						`memNo`='".$memNo."'";

			$update = $this->db->updateRecords($sql);
			return $update;
		}

	}
?>