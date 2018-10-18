<?

class travel_information extends Img{

	var $db;
	var	$Img;
	//建構函式
	public function __construct(){
		$this->db	= new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
		//$this->Email = new Email();
		return true;
	}
	/**	旅遊資訊清單
	**/
	public function email_subject($data){
		if($data['subject']==null)
		{
			$data['subject']="問題回報";
		}
		$Email->SendEmail_smtp($data['email'],$data['name'],"ss910316@gmail.com","管理者",$data['subject'],$data['message']);
	}
	
	/**	旅遊資訊清單
	**/
	public function travel_information_list(){
		$sql = "select
					*
				from
					travels
				ORDER BY time DESC";
		$data = $this->db->selectRecords($sql);
		return $data;
	}
	/**	推薦旅遊資訊清單
	**/
	public function travel_information_recommend(){
		$sql = "select
					*
				from
					travels
				where
					recommend	= '1'
				ORDER BY time DESC";
		$data = $this->db->selectRecords($sql);
		return $data;
	}
	/**	精選旅遊資訊清單
	**/
	public function travel_information_choice(){
		$sql = "select
					*
				from
					travels
				where
					choice	= '1'
				ORDER BY time DESC";
		$data = $this->db->selectRecords($sql);
		return $data;
	}
	/**	推薦旅遊資訊清單取(前8筆)
	**/
	public function travel_information_recommend_s(){
		$sql = "select
					*
				from
					travels
				where
					recommend	= '1'
				ORDER BY time DESC LIMIT 8";
		$data = $this->db->selectRecords($sql);
		return $data;
	}
	/**	精選旅遊資訊清單取(前8筆)
	**/
	public function travel_information_choice_s(){
		$sql = "select
					*
				from
					travels
				where
					choice	= '1'
				ORDER BY time DESC LIMIT 8";
		$data = $this->db->selectRecords($sql);
		return $data;
	}
	/**	旅遊資訊清單
	**/
	public function travel_information_sel($id){
		$sql = "select
					*
				from
					travels
				where
					id	= '".$id."'";
		$data = $this->db->selectRecords($sql);
		return $data[0];
	}
	/**	新增旅遊資訊
	**/
	public function add_travel_information($data){
		$data['photo1']= $this->Img->ImgUpdate($_FILES['photo1'],Travel_IMAGE_PATH);
		$data['photo2']= $this->Img->ImgUpdate($_FILES['photo2'],Travel_IMAGE_PATH);
		$data['photo3']= $this->Img->ImgUpdate($_FILES['photo3'],Travel_IMAGE_PATH);
		$data['photo4']= $this->Img->ImgUpdate($_FILES['photo4'],Travel_IMAGE_PATH);
		$data['photo5']= $this->Img->ImgUpdate($_FILES['photo5'],Travel_IMAGE_PATH);
		$data['photo6']= $this->Img->ImgUpdate($_FILES['photo6'],Travel_IMAGE_PATH);


		
		$sql = "insert into travels
				(	
					title,
					address,
					all_people,
					content,
					photo1,
					photo2,
					photo3,
					photo4,
					photo5,
					photo6,
					days,
					money,
					time
				)
				value
				(
					'".$data['title']."',
					'".$data['title']."',
					'".$data['all_people']."',
					'".$data['content']."',
					'".$data['photo1']."',
					'".$data['photo2']."',
					'".$data['photo3']."',
					'".$data['photo4']."',
					'".$data['photo5']."',
					'".$data['photo6']."',
					'".$data['days']."',
					'".$data['money']."',
					'".time()."'	
				)";
		$data = $this->db->insertRecords($sql);		
	}
	
	/**	編輯旅遊資訊
	**/		
	public function edit_travel_information_show($id){
		$sql = "select
					*
				from
					travels
				where
					id  ='".$id."'";
		$data = $this->db->selectRecords($sql);
		return $data[0];
	}
	
	/**	修改旅遊行程
	**/
	public function edit_travel_finish($data){	
		$data['photo1']= $this->Img->ImgUpdate($_FILES['photo1'],Travel_IMAGE_PATH,$data['old_photo1']);
		$data['photo2']= $this->Img->ImgUpdate($_FILES['photo2'],Travel_IMAGE_PATH,$data['old_photo2']);
		$data['photo3']= $this->Img->ImgUpdate($_FILES['photo3'],Travel_IMAGE_PATH,$data['old_photo3']);
		$data['photo4']= $this->Img->ImgUpdate($_FILES['photo4'],Travel_IMAGE_PATH,$data['old_photo4']);
		$data['photo5']= $this->Img->ImgUpdate($_FILES['photo5'],Travel_IMAGE_PATH,$data['old_photo5']);
		$data['photo6']= $this->Img->ImgUpdate($_FILES['photo6'],Travel_IMAGE_PATH,$data['old_photo6']);
		$sql = "update 
							travels 
						set
							title 				= '" . $data['title'] . "',
							address 			= '" . $data['address'] . "',
							all_people 			= '" . $data['all_people'] . "',
							content 			= '" . $data['content'] . "',
							photo1 				= '" . $data['photo1'] . "',
							photo2 				= '" . $data['photo2'] . "',
							photo3 				= '" . $data['photo3'] . "',
							photo4 				= '" . $data['photo4'] . "',
							photo5 				= '" . $data['photo5'] . "',
							photo6 				= '" . $data['photo6'] . "',
							days 				= '" . $data['days'] . "',
							money 				= '" . $data['money'] . "',							
							time 			= '" . time() . "'
						where
							id					= '" . $data['id'] . "'						
				";
	 	$data = $this->db->updateRecords($sql);
		return $data[0];
		
	}
	
	/**	刪除會員資料
	**/
	public function del_travel_information($id){
		$sql = "delete from
					travels 
				where
					id = '".$id."'";
		$this->db->deleteRecords($sql);	
	}		
	
		/**	旅遊資訊顯示
	**/
	public function travel_information_show($data){
		$id		= mysqli_real_escape_string($this->db->oDbLink, $data['id']);
		$type		= mysqli_real_escape_string($this->db->oDbLink, $data['type']);
		$stop_use		= mysqli_real_escape_string($this->db->oDbLink, $data['stop_use']);
		
		$sql = "update 
					travels 
				set				
					".$type."	='".$stop_use."'
				where 
					id			='".$id."'";
		$this->db->deleteRecords($sql);	
	}	
	
	/**	給予旅遊資訊權限
	**/
	public function audit_travel_information($id,$audit){
		$aduit		= mysqli_real_escape_string($this->db->oDbLink, $audit);
		
		$sql = "update 
					travels 
				set				
					state	='".$aduit."'
				where 
					id			='".$id."'";
		$this->db->deleteRecords($sql);	
	}	
	
	
	/**	查詢權限清單
	**/
	public function travel_information_audit_list($audit){
		$aduit		= mysqli_real_escape_string($this->db->oDbLink, $audit);
		
		$sql = "select
					*
				from
					travels
				where
					state  ='".$aduit."'";
		$data = $this->db->selectRecords($sql);
		return $data;
	}	
	
	/**	旅遊資訊查詢
	**/
	public function sel_travel_information($id){
		$sql = "select
					*
				from
					travels
				where
					id	=	'".$id."'				
				";
		$data = $this->db->selectRecords($sql);
		return $data[0]['title'];
	}

}
?>