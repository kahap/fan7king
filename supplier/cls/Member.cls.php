<?php
	class Member{
		var $db;
		
		//建構函式
		public function Member(){
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
			if(empty($str["memClass"])){
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
					case 2:
						$str["memGender"] = "無";
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
					case 0:
						$str["memClass"] = "0";
						break;
					case 1:
						$str["memClass"] = "1";
						break;
					case 2:
						$str["memClass"] = "2";
						break;
					case 3:
						$str["memClass"] = "3";
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
					case "無":
						$str["memGender"] = 2;
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
		
		//取得所有會員
		public function getAllMember(){
			$sql = "select
						*
					from
						member
					order by
						`memNo` desc
					limit 10000";
			$data = $this->db->selectRecords($sql);
				
			return $data;
		}
		
		//取得所有會員
		public function getMemberRecommCode($memRecommCode){
			$sql = "select
						`memNo`,`memName`,`memAccount`
					from
						member
					where
						`memRecommCode` = '".$memRecommCode."'";
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
		
		//取得系統內有推薦碼的會員
		public function getRecommMemberList(){
			$sql = "SELECT count(`memRecommCode`) as total,`memRecommCode` FROM `member` where `memRecommCode` != '' && `memRecommCode` > 1 && CHARACTER_LENGTH(`memRecommCode`) = 7 group by `memRecommCode`";
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
						`memNo`= '".$memNo."'";
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
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
			}
			date_default_timezone_set('Asia/Taipei');
			$date = date('Y-m-d H:i:s', time());
			$sql = "insert into `member`(`memClass`, `memSchool`,`memSubEmail` ,`memOther` ,`memAccount`,
					`memPwd`,`memName`,`memGender`,`memBday`,`memIdNum`,`memAddr`,`memPhone`,`memCell`,
					`memLineId`,`memRegistMethod`,`memFBtoken`,`memRecommCode`,`memAllowLogin`,`memEmailAuthen`,
					`memRegistDate` )
					values('".$memClass."',
							'".$memSchool."',
							'".$memSubEmail."',
							'".$memOther."',
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
							".$date.")";
			$insert = $this->db->insertRecords($sql);
			return $insert;
		}
		
		//統一編輯
		public function updateMember($array,$memNo){
			foreach($array as $key =>$value){
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
			}
			$memberClass_array = array('0'=>'學生','1'=>'上班族','2'=>'家管','3'=>'其他','NULL'=>'無');
			$flipped = array_flip($memberClass_array);
			$sql = "update
						`member`
					set
						`memClass`=".$memClass.",
						`memSchool`='".$memSchool."',
						`memSubEmail`='".$memSubEmail."',
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
		
		
		//刪除會員
		function delete($memNo){
			$sql = "delete from `member` where `memNo` = ".$memNo;
			$delete = $this->db->deleteRecords($sql);
			return $delete;
		}
		
		//判斷是否由APP登入
		function loginfromAPP($memNo){
			$sql = "SELECT * FROM `app_data` WHERE `memNo`= '".$memNo."'";
			$data = $this->db->selectRecords($sql);
			if(count($data)>0){
				$status = true;
			}else{
				$status = false;
			}
			return $status;
		}
	}
?>