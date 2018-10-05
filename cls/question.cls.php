<?
class question
{
	/**	建構函式

	**/
	public function __construct(){
		$this->db = new WADB(SYSTEM_DBHOST,SYSTEM_DBNAME,SYSTEM_DBUSER,SYSTEM_DBPWD);
		$this->System = new System();
		$this->Img	= new Img();
		$this->Email = new Email();
		$this->m_member = new m_member();
		$this->g_member = new g_member();
		$this->t_member = new t_member();
		
	}

	
	/**	select question
	**/
	public function select_question(){	
		$sql = "select
					*
				from
					related_questions
				";
	 	$data = $this->db->selectRecords($sql);
		return $data;
		
		
	}
	/**	insert contact
	**/
	public function contact($data){	
		$sql = "Insert into 
						 contact 
						set
						 Name 		= '" . $data['Name'] . "',
						 Subject 		= '" . $data['Subject'] . "',
						 Message 		= '" . $data['Message'] . "',
						 Email 		= '" . $data['Email'] . "',
						time 		= '" . time() . "'
				";
	 	$data = $this->db->insertRecords($sql);
		
		
	}
	/**	新增旅遊行程
	
	**/
	public function add_travel($data){	
		$data['photo1']= $this->Img->ImgUpdate($_FILES['photo1'],Travel_IMAGE_PATH);
		$data['photo2']= $this->Img->ImgUpdate($_FILES['photo2'],Travel_IMAGE_PATH);
		$data['photo3']= $this->Img->ImgUpdate($_FILES['photo3'],Travel_IMAGE_PATH);
		$data['photo4']= $this->Img->ImgUpdate($_FILES['photo4'],Travel_IMAGE_PATH);
		$data['photo5']= $this->Img->ImgUpdate($_FILES['photo5'],Travel_IMAGE_PATH);
		$sql = "Insert into 
							travels 
						set
							account_id 			= '" . $_SESSION['user']['info']['account_id'] . "',
							member 				= '" . $_SESSION['user']['info']['member'] . "',
							title 				= '" . $data['title'] . "',
							travel_time 		= '" . $data['travel_time'] . "',
							travel_end 			= '" . $data['travel_end'] . "',
							area 				= '" . $data['area'] . "',
							class 				= '" . $data['class'] . "',
							content1 			= '" . $data['content1'] . "',
							content2 			= '" . $data['content2'] . "',
							content3 			= '" . $data['content3'] . "',
							content4 			= '" . $data['content4'] . "',
							content5 			= '" . $data['content5'] . "',
							photo1 				= '" . $data['photo1'] . "',
							photo2 				= '" . $data['photo2'] . "',
							photo3 				= '" . $data['photo3'] . "',
							photo4 				= '" . $data['photo4'] . "',
							photo5 				= '" . $data['photo5'] . "',
							all_people 			= '" . $data['all_people'] . "',
							main_money 				= '" . $data['money'] . "',
							main_people 		= '" . $data['main_people'] . "',
							join_people 		= '" . $data['join_people'] . "',
							transport 			= '" . $data['transport'] . "',
							other 				= '" . $data['other'] . "',
							up_time 			= '" . time() . "',
							save_time 			= '" . time() . "'
				";
	 	$data = $this->db->insertRecords($sql);
		
		
	}
	
	/**	旅遊行程清單
	**/
	public function travel($id,$member){	
		$sql = "select
					*
				from
					travels
				where
					account_id 	='".$id."'
				&&
					member 	='".$member."'
				&&
					yesno 	='1'
				order by 'save_time' desc
				";
	 	$data = $this->db->selectRecords($sql);
		return $data;
	}
	
	/**	旅遊行程清單
	**/
	public function edit_travel($account_id,$member,$id){	
		$sql = "select
					*
				from
					travels
				where
					account_id 	=	'".$account_id."'
				&&
					member 		=	'".$member."'
				&& 
					id			=	'".$id."'
				&&
					yesno 	='1'
				";
	 	$data = $this->db->selectRecords($sql);
		return $data[0];
	}
	/**	修改旅遊行程
	**/
	public function edit_travel_finish($data,$id){	
		$data['photo1']= $this->Img->ImgUpdate($_FILES['photo1'],Travel_IMAGE_PATH,$data['old_photo1']);
		$data['photo2']= $this->Img->ImgUpdate($_FILES['photo2'],Travel_IMAGE_PATH,$data['old_photo2']);
		$data['photo3']= $this->Img->ImgUpdate($_FILES['photo3'],Travel_IMAGE_PATH,$data['old_photo3']);
		$data['photo4']= $this->Img->ImgUpdate($_FILES['photo4'],Travel_IMAGE_PATH,$data['old_photo4']);
		$data['photo5']= $this->Img->ImgUpdate($_FILES['photo5'],Travel_IMAGE_PATH,$data['old_photo5']);
		$sql = "update 
							travels 
						set
							title 				= '" . $data['title'] . "',
							travel_time 		= '" . $data['travel_time'] . "',
							area 				= '" . $data['area'] . "',
							class 				= '" . $data['class'] . "',
							content1 			= '" . $data['content1'] . "',
							content2 			= '" . $data['content2'] . "',
							content3 			= '" . $data['content3'] . "',
							content4 			= '" . $data['content4'] . "',
							content5 			= '" . $data['content5'] . "',
							photo1 				= '" . $data['photo1'] . "',
							photo2 				= '" . $data['photo2'] . "',
							photo3 				= '" . $data['photo3'] . "',
							photo4 				= '" . $data['photo4'] . "',
							photo5 				= '" . $data['photo5'] . "',
							all_people 			= '" . $data['all_people'] . "',
							main_money 			= '" . $data['main_money'] . "',
							main_people 		= '" . $data['main_people'] . "',
							join_people 		= '" . $data['join_people'] . "',
							transport 			= '" . $data['transport'] . "',
							other 				= '" . $data['other'] . "',
							up_time 			= '" . time() . "',
							state				= '0'
						where
							id					= '" . $id . "'
						&&
							account_id			=  '" .$_SESSION['user']['info']['account_id']. "'
						&&
							member				=  '" .$_SESSION['user']['info']['member']. "'
				";
	 	$data = $this->db->updateRecords($sql);
		return $data[0];
		
	}
	/**	刪除旅遊行程
	**/
	public function travel_del($id){
		$sql = "update 
					travels 
				set
					yesno	=	'0'
				where
					id				= '".$id."'
				&&
					account_id		=	'".$_SESSION['user']['info']['account_id']."'
				&&
					member			=	'".$_SESSION['user']['info']['member']."'";
		$this->db->deleteRecords($sql);	
	}	
	
	/**	旅遊清單
	**/
	public function travel_list($s){	
		if($s != null){
			$sql = "select
						*
					from
						travels
					where
						state = '1'
						&&
						".$s." = '1'
						&&
						yesno 	='1'
					order by 'save_time' desc
					";
		}else{
			$sql = "select
						*
					from
						travels
					where
						state = '1'
						&&
						yesno 	='1'
					order by 'save_time' desc
					";
		}
	 	$data = $this->db->selectRecords($sql);
		return $data;
		
		
	}
	/**	旅遊行程
	**/
	public function select_travel($id){	
		$sql = "select
					*
				from
					travels
				where
					id		= '".$id."'
				";
	 	$data = $this->db->selectRecords($sql);
		return $data[0];
	}
	/**	隨機產生亂數字串
		$len 產生的字串長度
	**/
	public function GetRandString($len){
		$laborid_len = $len;
		$laborid_str = '';
		$number = '_1234567890';
		$len = strlen($number);
		for ($i = 0; $i < $laborid_len; $i++) {
			$code .= $number[rand() % $len];
		}
		return $code ;
	}
	
	/**	購買
	**/
	public function shoping($post,$id){	
		$data = $this->select_travel($id);
		$num=$this->GetRandString(8);
		$m=$this->m_member->edit_m_member_show($_SESSION['user']['info']['account_id']);
		if($data['member'] == "g_member"){
			$gt=$this->g_member->edit_g_member_show($_SESSION['user']['info']['account_id']);
		}
		if($data['member'] == "t_member"){
			$gt=$this->t_member->edit_t_member_show($_SESSION['user']['info']['account_id']);
		}
		
		$sql = "Insert into 
							pay_list 
						set
							num				= '".$num."',
							account_id 		= '".$data['account_id']."',
							member		 	= '".$data['member']."',
							travel_id 		= '".$data['id']."',
							name 			= '".$data['title']."',
							buy_id 			= '".$_SESSION['user']['info']['account_id']."',
							people 			= '".$post['people']."',
							travel_time 			= '".$post['travel_time']."',
							money 			= '".$data['main_money']."',
							up_time 		= '" . time() . "',
							save_time 		= '" . time() . "'";
	 	$data = $this->db->insertRecords($sql);
		return $data;
		$this->gt_group_email($gt,$data['member']);
	}
	public function gt_group_email($data,$member){
		if($member == "t_member"){
		$sql="select
					s_t_title,s_t_content
				from
					group_email
				where
					id	=	'1'";
			$group = $this->db->selectRecords($sql);
			$content = $group['s_t_content'];
			$subject =	$group['s_t_title'].$data['travel_name'];
		}else{
			
			$sql="select
					s_g_title,s_g_content
				from
					group_email
				where
					id	=	'1'";
			$group = $this->db->selectRecords($sql);
			$content = $group['s_g_content'];
			$subject =	$group['s_g_title'].$data['contact_name'];
		}
		
		$receive_name =	"親愛的顧客";
		$sender_email =	'doujian0924@gmail.com';
		$sender_name =	"xxx旅遊網";
		
		
		$data = $this->Email->SendEmail_smtp($data['email'],$receive_name,$sender_email,$sender_name,$subject,$content);
	}
			
	public function m_group_email($data){
		$sql="select
					s_m_title,s_m_content
				from
					group_email
				where
					id	=	'1'";
		$group = $this->db->selectRecords($sql);
		$receive_name =	"親愛的顧客";
		$sender_email =	'doujian0924@gmail.com';
		$sender_name =	"xxx旅遊網";
		$subject =	$group['s_m_title'].$data['name'];
		$cotent = $group['s_m_content'];
		$data = $this->Email->SendEmail_smtp($data['email'],$receive_name,$sender_email,$sender_name,$subject,$content);
	}
	
	/**	查看我的最愛
	**/
	public function like(){	
		$sql ="select
					*
				from 
					love 
				where
					account_id 		= '".$_SESSION['user']['info']['account_id']."'
					&&
					member		 	= 'm_member'
				order by 'save_time' desc";
	 	$data = $this->db->selectRecords($sql);
		return $data;
	}
	/**	確認我的最愛是否重覆
	**/
	public function check_like($id){	
		$sql ="select
					*
				from 
					love 
				where
					account_id 		= '".$_SESSION['user']['info']['account_id']."'
					&&
					travel_id		 	= '".$id."'";
	 	$data = $this->db->selectRecords($sql);
		if(count($data) == 0){
			return true;
		}else{
			return false;
		}
	}
	
	public function like_travel($id){	
		$sql = "select
					*
				from
					travels
				where
					id 	='".$id."'
				&&
					yesno 	='1'
				
				";
	 	$data = $this->db->selectRecords($sql);
		return $data[0];
	}
	
	/**	加入我的最愛(旅遊清單)
	**/
	public function add_like_travel($id){	
	
		
		$sql = "INSERT INTO  `love` (
					`account_id` ,
					`travel_id` ,
					`member` ,
					`save_time`)
				VALUES (
					'".$_SESSION['user']['info']['account_id']."',  '".$id."', 'm_member',  '".time()."'
					)";
	 	$this->db->insertRecords($sql);
	}
	
	
	
	/**	刪除我的最愛(旅遊清單)
	**/
	public function del_like_travel($id){	
		$sql = "delete from 
							love 
						where
							travel_id 		= '".$id."'
							&&
							account_id 		= '".$_SESSION['user']['info']['account_id']."'
							&&
							member		 	= '".$_SESSION['user']['info']['member']."'
							";
	 	$this->db->deleteRecords($sql);
	}
	
	
	
	/**	加入我的最愛
	**/
	public function add_like($id){	
	
		
		$sql = "INSERT INTO  `love` (
					`account_id` ,
					`travel_id` ,
					`member` ,
					`save_time`)
				VALUES (
					'".$_SESSION['user']['info']['account_id']."',  '".$id."', 'm_member',  '".time()."'
					)";
	 	$this->db->insertRecords($sql);
	}
	
	
	
	/**	刪除我的最愛
	**/
	public function del_like($id){	
		$sql = "delete from 
							love 
						where
							id 		= '".$id."'
							&&
							account_id 		= '".$_SESSION['user']['info']['account_id']."'
							&&
							member		 	= '".$_SESSION['user']['info']['member']."'
							";
	 	$this->db->deleteRecords($sql);
	}
	
	/**	成團通知旅行團&導遊
	**/
	public function gt_group(){	
		$sql = "select
					*
				from 
					pay_list 
				where
					account_id 		= '".$_SESSION['user']['info']['account_id']."'
					&&
					member		 	= '".$_SESSION['user']['info']['member']."'
				order by 'save_time' desc";
	 	$data = $this->db->selectRecords($sql);
		
		return $data;
	}
	
	
	/**	成團通知會員
	**/
	public function m_group(){	
		$sql = "select
					*
				from 
					pay_list 
				where
					buy_id 			= '".$_SESSION['user']['info']['account_id']."'
					&&
					state			='1'
					&&
					pay_finish		='1'
				order by 'save_time' desc";
	 	$data = $this->db->selectRecords($sql);
		return $data;
	}
	
	/**	歷史交易紀錄-旅行社及導遊
	**/
	public function gt_pay(){	
		$sql = "select
					*
				from 
					pay_list 
				where
					account_id 		= '".$_SESSION['user']['info']['account_id']."'
					&&
					member		 	= '".$_SESSION['user']['info']['member']."'
				order by 'save_time' desc";
	 	$data = $this->db->selectRecords($sql);
		return $data;
	}
	
	public function gt_pay_name($id){	
		$sql = "select
					*
				from 
					m_member 
				where
					id 			= '".$id."'";
	 	$data = $this->db->selectRecords($sql);
		return $data[0];
	}
	
	/**	歷史交易紀錄-會員
	**/
	public function m_pay(){	
		$sql = "SELECT * 
				FROM  `pay_list` 
				WHERE 
				buy_id 	= '".$_SESSION['user']['info']['account_id']."'
				order by 'save_time' desc";
	 	$data = $this->db->selectRecords($sql);
		return $data;
	}
	
	public function m_pay_name($id,$member){	
		$sql = "SELECT * 
				FROM  ".$member."
				WHERE
					id 			= '".$id."'";
	 	$data = $this->db->selectRecords($sql);
		return $data[0];
	}
	/**搜尋付款清單單項
	**/
	public function group($id){	
		$sql = "SELECT * 
				FROM  pay_list
				WHERE
					id 			= '".$id."'";
	 	$data = $this->db->selectRecords($sql);
		return $data[0];
	}
	
	
	/**	確認成團
	**/
	public function check_group($id){	
		$group = $this -> group($id);
		$travel= $this->select_travel($group['travel_id']);
		$m=$this->m_member->edit_m_member_show($group['buy_id']);
		if($travel['member'] == "g_member"){
			$gt=$this->g_member->edit_g_member_show($travel['account_id']);
		}
		if($travel['member'] == "t_member"){
			$gt=$this->t_member->edit_t_member_show($travel['account_id']);
		}
		$sql = "update  
					pay_list 
				set
					state			='1'
				where
					id 				= '".$id."'
				&&
					account_id 		= '".$_SESSION['user']['info']['account_id']."'
				&&
					member		 	= '".$_SESSION['user']['info']['member']."'";
	 	$data = $this->db->updateRecords($sql);
		$this->gt_group_email($gt,$travel['member']);
		$this->m_group_email($m);
		
	}
	/**	取消成團
	**/
	public function del_group($id){	
		$group = $this -> group($id);
		$travel= $this->select_travel($group['travel_id']);
		$m=$this->m_member->edit_m_member_show($group['buy_id']);
		if($travel['member'] == "g_member"){
			$gt=$this->g_member->edit_g_member_show($travel['account_id']);
		}
		if($travel['member'] == "t_member"){
			$gt=$this->t_member->edit_t_member_show($travel['account_id']);
		}
		$sql = "update  
					pay_list 
				set
					state			='0'
				where
					id 				= '".$id."'
				&&
					account_id 		= '".$_SESSION['user']['info']['account_id']."'
				&&
					member		 	= '".$_SESSION['user']['info']['member']."'";
	 	$data = $this->db->updateRecords($sql);
		$this->m_del_group_eamail($m);
		$this->gt_del_group_eamail($gt,$travel['member']);
	}
	
	public function m_del_group_eamail($data){
		$sql="select
					d_g_title,d_g_content
				from
					group_email
				where
					id	=	'1'";
		$group = $this->db->selectRecords($sql);
		$receive_name =	"親愛的顧客";
		$sender_email =	'doujian0924@gmail.com';
		$sender_name =	"xxx旅遊網";
		$subject =	"xxx旅遊網-取消成團通知";
		$cotent = '';
		$data = $this->Email->SendEmail_smtp($data['email'],$receive_name,$sender_email,$sender_name,$subject,$content);
	}
	
	public function gt_del_group_eamail($data,$member){
		if($member == "t_member"){
		$sql="select
					d_t_title,d_t_content
				from
					group_email
				where
					id	=	'1'";
			$group = $this->db->selectRecords($sql);
			$content = $group['d_t_content'];
			$subject =	$group['d_t_title'].$data['travel_name'];
		}else{
			$sql="select
					d_g_title,d_g_content
				from
					group_email
				where
					id	=	'1'";
				$group = $this->db->selectRecords($sql);
			$content = $group['d_g_content'];
			$subject =	$group['d_g_title'].$data['contact_name'];
		}
		$group = $this->db->selectRecords($sql);
		$receive_name =	"親愛的顧客";
		$sender_email =	'doujian0924@gmail.com';
		$sender_name =	"xxx旅遊網";
		$subject =	"xxx旅遊網-取消成團通知";
		$cotent = '';
		$data = $this->Email->SendEmail_smtp($data['email'],$receive_name,$sender_email,$sender_name,$subject,$content);
	}
	
	/**	首頁旅遊最新三筆
	**/
	public function home_travel($data){	
		$sql = "SELECT * 
				FROM
					travels
				WHERE 
					".$data." =  '1'
				&&
					yesno 	='1'
				ORDER BY  'save_time' DESC 
				LIMIT 3
				";
	 	$data = $this->db->selectRecords($sql);
		return $data;
	}
	
	/**	旅遊地區
	**/
	public function area(){	
		$sql = "select
					*
				from
					travel_area
				";
	 	$data = $this->db->selectRecords($sql);
		return $data;
	}
	/**	填寫保險單
	**/
	public function add_safety($data,$id){
		$data['name']=implode(",",$data['name']);
		$data['id_number']=implode(",",$data['id_number']);
		$data['birthday']=implode(",",$data['birthday']);
		$sql = "Insert into 
							safety 
						set
							pay_id				= '".$data['id']."',
							travel_id 			= '".$id."',
							buy_id 				= '".$_SESSION['user']['info']['account_id']."',
							name 				= '".$data['name']."',
							id_number 			= '".$data['id_number']."',
							birthday 			= '".$data['birthday']."',
							save_time 			= '" . time() . "'";
	 	$this->db->insertRecords($sql);
	}
	
	/**	查看保險清單
	**/
	public function safety($id){	
		$sql = "select
					*
				from
					safety
				where
					pay_id ='".$id."'
				
				";
	 	$data = $this->db->selectRecords($sql);
		return $data[0];
	}
	
	/**	查看歷史交易紀錄旅遊名稱
	**/
	
	public function yesno_travel($id){
		$sql = "select
					*
				from
					travels
				where
					id = '".$id."'
				
				";
	 	$data = $this->db->selectRecords($sql);
		return $data[0];
	}
}
?>