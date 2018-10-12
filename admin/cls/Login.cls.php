<?php

class Login
{
	private  $userdata;		//使用者資料
	private	 $errormessage;	//錯誤訊息
	private  $stop_use;		//停權狀態		
		
	var $db;			//db
	var $array_value;	//陣列值
	var $array_key;		//陣列key值
	
	
	//建構函式
	public function Login(){
		$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
		return TRUE;			
	}
	
	
	//判斷是否停權
	public function stop_use($company_code,$username,$titles)
    {
		$company_code 	= 	mysqli_real_escape_string($this->db->oDbLink, $company_code);
		$username		=	mysqli_real_escape_string($this->db->oDbLink, $username);
		$titles 		= 	mysqli_real_escape_string($this->db->oDbLink, $titles);
		if($titles == 1){
			$sql = "select	   
						*
					from
						".USER_ADMIN."
					where
						user_name = '".mysqli_real_escape_string($this->db->oDbLink, $username)."'
					limit 0,1 ";			
		}
		if($titles == 2){
			$sql = "select	   
						*
					from
						".USER_COMPANY."
					where
						company_code = '".mysqli_real_escape_string($this->db->oDbLink, $company_code)."'
						&&
						user_name = '".mysqli_real_escape_string($this->db->oDbLink, $username)."'
					limit 0,1 ";			
		}else if($titles == 3){
			$sql = "select	   
						*
					from
						".USER_STAFF."
					where
						company_code = '".mysqli_real_escape_string($this->db->oDbLink, $company_code)."'
						&&
						user_name = '".mysqli_real_escape_string($this->db->oDbLink, $username)."'
					limit 0,1 ";				
		}

        $this->stop_use= $this->db->selectRecords($sql);
        return $this->stop_use[0]['stop_use'] ;
	}

	
	/*檢查公司是否被停權*/
	public function Check_Stop_Use_Company($company_code)
    {
		$company_code 	= 	mysqli_real_escape_string($this->db->oDbLink, $company_code);
		$sql = "select	   
					*
				from
					".USER_COMPANY."
				where
					company_code = '".mysqli_real_escape_string($this->db->oDbLink, $company_code)."'
				limit 0,1 ";	
		$company_stop_use= $this->db->selectRecords($sql);
		return $company_stop_use[0]['stop_use'] ;	
	}
	
	
	//取得公司工種授權
	private function Get_Company_Worker_Type($company_code)
    {
		$company_code 	= 	mysqli_real_escape_string($this->db->oDbLink, $company_code);
		$sql = "select	   
					worker_type
				from
					".USER_COMPANY."
				where
					company_code = '".mysqli_real_escape_string($this->db->oDbLink, $company_code)."'
				limit 0,1 ";	
		$worker_type= $this->db->selectRecords($sql);
		return $worker_type[0]['worker_type'] ;			
				
	}

	
	//確認公司是否有授權工種
	private function Check_Authorize_Company($Authorize_Company_Worker_Type, $worker_kind)
    {
		parse_str($Authorize_Company_Worker_Type,$worker_type_array);	
		if(array_key_exists('worker_type'.$worker_kind, $worker_type_array)){
			return true;
		}else{
			return false;
		}		
	}
	
	
	//判斷是否有值
	public function validate($username,$password)
    {
		if($this->user_admin_exit($username,$password)){
			return true;
		}else{				
			return false;
		}
	}
	
	
	//判斷管理者帳號密碼是否存在,並抓出系統管理者相關資料
	private function user_admin_exit($username,$password)
    {
		$sql = "select	   
					*
				from
					system_manager
				where
					smAccount = '".mysqli_real_escape_string($this->db->oDbLink, $username)."'
					&&
					smPwd = '".mysqli_real_escape_string($this->db->oDbLink, $password)."'
				limit 0,1 ";
	 	$this->userdata = $this->db->selectRecords($sql);
		$record =  $this->db->getNumberOfRecords();
		if($record == 1){
			return true;
		}else{
			return false;
		}		
	}			

	
	//抓取成功時登入的資料
	public function getuserdata()
    {
		return $this->userdata[0] ;
	}
	
	
	//goURL
	public function goURL()
    {
		
	}
	
	
	/* Get errormessage  */
	public function getErrorMessage()
	{
		return $this->errormessage;
	}
	
	
	/*專案管理原登入*/
	//判斷是否有值
	public function Projectvalidate($username,$password)
    {
		if($this->Projec_user_exit($username,$password)){
			return true;
		}else{				
			return false;
		}
	}
	
	
	//判斷專案者帳號密碼是否存在,並抓出專案管理員相關資料
	private function Projec_user_exit($username,$password)
    {
		$sql = "select	   
					*
				from
					".PROJECT_LIST."
				where
					manager_acount = '".mysqli_real_escape_string($this->db->oDbLink, $username)."'
					&&
					manager_passwd = '".mysqli_real_escape_string($this->db->oDbLink, $password)."'
				limit 0,1 ";
	 	$this->userdata = $this->db->selectRecords($sql);
		$record =  $this->db->getNumberOfRecords();
		if($record == 1){
			return true;
		}else{
			return false;
		}
		
	}
	
	
	public function loginlog($sql)
    {
		$this->db->insertRecords($sql);
	}	
}

?>