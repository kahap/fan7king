<?php
	class Product{
		var $db;
		
		//建構函式
		public function Product(){
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
		}
		
		//資料符號轉換
		public function changeSign($str,$replace){
			if(strrpos($str, "#") !== false){
				$str = str_replace("#", $replace, $str);
			}else{
			}
			return $str;
		}
		
		
		
		//取得所有商品
		public function getAllPro(){
			$sql = "select
						*
					from
						`product`
                 
					order by
						`proNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//取得名稱類似
		public function getAllProByLikeName($proName){
			$sql = "select
						*
					from
						`product`
					where
						`proName`
					like
						'%".$proName."%'";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//取得根據內部訂單編號
		public function getOneByCaseNo($proCaseNo){
			$sql = "select
						*
					from
						`product`
					where
						`proCaseNo` = '".$proCaseNo."'";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//取得所有商品(順序反)
		public function getAllProDescWithCatAndBra($braNo,$catNo){
			$sql = "select
						*
					from
						`product`
					where
						`braNo` = '".$braNo."'
					and
						`catNo` = '".$catNo."'                  
					order by
						`proCaseNo`
					desc";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//編號取得單一商品
		public function getOneProByNo($proNo){
			$sql = "select
						*
					from
						`product`
					where
						`proNo`='".$proNo."'";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		
		//根據種類取得商品
		public function getAllProByCatName($catName){
			$sql = "select
						*
					from
						`product`
					inner join 
						`category`
					on
						`category`.`catNo` = `product`.`catNo`
					where                      
						`catName`='".$catName."'";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//根據品牌取得商品
		public function getAllProByBraName($braName){
			$sql = "select
						*
					from
						`product`
					inner join 
						`brand`
					on
						`brand`.`braNo` = `product`.`braNo`
					where                      
						`braName`='".$braName."'";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//取得還沒選擇之商品
		public function getAllProExcept($proNoArr){
			$sql = "select
						*
					from
						`product`
					where ";
			foreach($proNoArr as $value){
				if($value != end($proNoArr)){
					$sql .= " NOT `proNo` = '".$value."' AND ";
				}else{
					$sql .= " NOT `proNo` = '".$value."'";
				}
			}
			$data = $this->db->selectRecords($sql);
			return $data;
		}
        //取得商品編號
		public function getProNo_Sup($catNo,$braNo,$biNo,$proName){
			$sql = "select
						*
					from
						`product`
					where      
                        `braNo`='".$braNo."' 
                    and    
                        `biNo`='".$biNo."' 
                    and 
                        `catNo`='".$catNo."' 
                    and 
						`proName`='".$proName."'
                    and 
						`bySup`='1'
                    order by proNo desc";
		
			$data = $this->db->selectRecords($sql);
			return $data;
		}

//取得商品編號
		public function getProNo($catNo,$braNo,$proName){
			$sql = "select
						*
					from
						`product`
					where      
                        `braNo`='".$braNo."' 
                    and 
                        `catNo`='".$catNo."' 
                    and 
						`proName`='".$proName."'
                    order by proNo desc";
		
			$data = $this->db->selectRecords($sql);
			return $data;
		}

		//新增
		function insert($array,$newProNo){
            foreach($array as $key =>$value){
                if (is_array($value)){
                    foreach ($value as $v){
                        $v = mysqli_real_escape_string($this->db->oDbLink, $v);
                    }
                    $$key = $value;
                }else {
                    $$key = mysqli_real_escape_string($this->db->oDbLink, $value);
                }
            }
			date_default_timezone_set('Asia/Taipei');
			$date = date('Y-m-d H:i:s', time());
			$sql = "insert into `product`(`proCaseNo`,`catNo`, `braNo`, `biNo`,  `proName`, `proOffer`, `proGift`, `proModelID`,`proSpec`,
					`proDetail`,`proImage`,`bySup`)
					values('".$newProNo."',
							'".$catNo."',
							'".$braNo."',
							'".$biNo."',
							'".$proName."',
							'".$proOffer."',
							'".$proGift."',
							'".$proModelID."',
							'".$proSpec."',
							'".$proDetail."',
							'".$proImage."','1')";
			$insert = $this->db->insertRecords($sql);
			return $insert;
		}
		
		//編輯
		public function update($array,$newProNo,$proNo){
			foreach($array as $key =>$value){
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
			}
            $sql = "update
						`product`
					set
						`proCaseNo`='".$newProNo."',
						`catNo`='".$catNo."',
						`braNo`='".$braNo."',
						`biNo`='".$biNo."',
						`proName`='".$proName."',
						`proOffer`='".$proOffer."',
						`proGift`='".$proGift."',
						`proModelID`='".$proModelID."',
						`proSpec`='".$proSpec."',
						`proDetail`='".$proDetail."',
						`proImage`='".$proImage."'
					where
						`proNo`='".$proNo."'";
//			$sql = "update
//						`product`
//					set
//						`proCaseNo`='".$newProNo."',
//						`catNo`='".$catNo."',
//						`braNo`='".$braNo."',
//						`proName`='".$proName."',
//						`proModelID`='".$proModelID."',
//						`proSpec`='".$proSpec."',
//						`proDetail`='".$proDetail."',
//						`proImage`='".$proImage."'
//					where
//						`proNo`='".$proNo."'";
			
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯圖片
		public function updateImg($proImage,$proNo){
			$sql = "update
						`product`
					set
						`proImage`='".$proImage."'
					where
						`proNo`='".$proNo."'";
				
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//刪除
		function delete($proNo){
			$sql = "delete from `product` where `proNo` = ".$proNo;
			$delete = $this->db->deleteRecords($sql);
			return $delete;
		}



        //取得所有商品(順序反)
        public function getAllProDescWithCatAndBraAndItem($braNo,$catNo, $biNo)
        {
            $sql = "select
                            *
                        from
                            `product`
                        where
                            `braNo` = '".$braNo."'
                        and
                            `catNo` = '".$catNo."'
                        and
                            `biNo` = '".$biNo."'
                        order by
                            `proCaseNo`
                        desc";
            $data = $this->db->selectRecords($sql);
            return $data;
        }
	}
?>