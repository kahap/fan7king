<?php
	class Product
    {
		var $db;


		//建構函式
		public function Product()
        {
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
		}


		//資料符號轉換
		public function changeSign($str,$replace)
        {
			if(strrpos($str, "#") !== false){
				$str = str_replace("#", $replace, $str);
			}else{
			}
			return $str;
		}


        //取得所有商品
        public function getAllPMByStatus0(){
                $key = ' and `product_manage`.`pmStatus` = 0 ';
            $sql = "select
						`product`.* 
					from
						`product` join `product_manage` on `product`.`proNo` = `product_manage`.`proNo` 
                    where 
                        1 
					".$key." 
					order by
						`product`.`proNo` desc
                    limit 0,100 ";
            $data = $this->db->selectRecords($sql);
            return $data;
        }

		
		//取得所有商品
		public function getAllPro($p,$a , $search){
            $key = '';
            if ($search){
                $key = ' and `product`.`proName` LIKE "%'.$search.'%" ';
            }
			$sql = "select
						proNo,proCaseNo,proName,catNo,braNo,biNo
					from
						`product` 
                    where 
                        1 
					".$key." 
					order by
						`proNo` desc 
					limit " .$p. " , " .$a ;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
        //取得所有商品的總數
        public function getAllProCount($search)
        {
            $key = '';
            if ($search){
                $key = ' and `product`.`proName` LIKE "%'.$search.'%" ';
            }
            $sql = "select
						count(`product`.`proNo`) as `count` 
					from
						`product` 
                    where 
                        1 
					".$key." 
					order by
						`proNo` desc ";
					//limit " .$p. " , " .$a ;
            $q=mysqli_query($this->db->oDbLink,$sql);
            $a=mysqli_fetch_array($q,MYSQLI_ASSOC);
            return $a["count"];
        }


        //取得所有商品 datatable
        public function getAllProWhitDT($sort_arr,$search_word ,$sort_name='`proNo`', $sort_dir='desc' ,$iDisplayStart,$iDisplayLength)
        {
            if ($sort_arr) {
                $query = ' where ';
                foreach ($sort_arr as $item) {
                    $query .= $item . ' like ' . '%' . $search_word . '%' . ' OR ' ;
                }
                $query .= ' 1 ';
            } else {
                $query = '';
            }

            $sql = "select
						proNo,proCaseNo,proName,catNo,braNo,biNo
					from
						`product`                     
                      " .$query. " 
					order by
						".$sort_name." ".$sort_dir." 
                     limit ".$iDisplayStart." , ".$iDisplayLength;

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
                    join `product_manage`.`proNo` on `product_manage`.`proNo`=`product`.`proNo` 
					where
						`proCaseNo` = '".$proCaseNo."'";
			$data = $this->db->selectRecords($sql);
			return $data;
		}

        //取得所有商品(順序反)
        public function getAllProDescWithCatAndBra($braNo,$catNo)
        {
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

        //取得所有商品(順序反) 品項
        public function getAllProDescWithCatAndItem($biNo,$catNo)
        {
            $sql = "select
						*
					from
						`product`
					where
						`biNo` = '".$biNo."'
					and
						`catNo` = '".$catNo."'
					order by
						`proCaseNo`
					desc";
            $data = $this->db->selectRecords($sql);
            return $data;
        }
		
		//編號取得單一商品
		public function getOneProByNo($proNo, $search=0){
			$sql = "select
						proNo,proCaseNo,proName,catNo,braNo,biNo,proModelID,proSpec,proImage
					from
						`product`
					where
						`proNo`='".$proNo."' ";
			if ($search){
			    $sql.=' and `proName` LIKE "%'.$search.'%" ';
            }
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		
		//編號取得單一商品
		public function getOneProByNo_view($proNo){
			$sql = "select
						proNo,proCaseNo,proName,catNo,braNo,biNo,proModelID,proSpec,proDetail,proImage
					from
						`product`
					where
						`proNo`='".$proNo."'";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		
		
		//根據種類取得商品
		public function getAllProByCatName($catName,$p=1,$a=30 ,$search=0)
        {
            $key = '';
            if ($search){
                $key = ' and `product`.`proName` LIKE "%'.$search.'%" ';
            }
			$sql = "select
						*
					from
						`product`
					inner join 
						`category`
					on
						`category`.`catNo` = `product`.`catNo`
					where
						`catName`='".$catName."' 
					".$key." 
					limit " .$p. " , " .$a ;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		//根據種類取得商品總數
        public function getAllProByCatNameCount($catName , $search=0)
        {
            $key = '';
            if ($search){
                $key = ' and `product`.`proName` LIKE "%'.$search.'%" ';
            }
            $sql = "select
						count(`product`.`proNo`) as `count` 
					from
						`product`
					inner join 
						`category`
					on
						`category`.`catNo` = `product`.`catNo`
					where
						`catName`='".$catName."' 
					".$key." ";
			$q=mysqli_query($this->db->oDbLink,$sql);
			$a=mysqli_fetch_array($q,MYSQLI_ASSOC);
            return $a["count"];
        }
		
		//根據品牌取得商品
		public function getAllProByBraName($braName,$p=0,$a=30 ,$search=0)
        {
            $key = '';
            if ($search){
                $key = ' and `product`.`proName` LIKE "%'.$search.'%" ';
            }
			$sql = "select
						*
					from
						`product`
					inner join 
						`brand`
					on
						`brand`.`braNo` = `product`.`braNo`
					where
						`braName`='".$braName."' 
					".$key." 
					limit " .$p. " , " .$a ;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
        //根據品牌取得商品總數
        public function getAllProByBraNameCount($braName ,$search=0)
        {
            $key = '';
            if ($search){
                $key = ' and `product`.`proName` LIKE "%'.$search.'%" ';
            }
            $sql = "select
						count(`product`.`proNo`) as `count` 
					from
						`product`
					inner join 
						`brand`
					on
						`brand`.`braNo` = `product`.`braNo`
					where
						`braName`='".$braName."' 
					".$key." ";
			$q=mysqli_query($this->db->oDbLink,$sql);
			$a=mysqli_fetch_array($q,MYSQLI_ASSOC);
            return $a["count"];
        }


        //根據品項取得商品
        public function getAllProByItemName($biName){
            $sql = "select
						*
					from
						`product`
					inner join 
						`b_items`
					on
						`b_items`.`biNo` = `product`.`biNo`
					where
						`biName`='".$biName."'";
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
			return $sql;
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
			$sql = "insert into `product`(`proCaseNo`,`catNo`, `braNo`, `biNo`, `proName`, `proOffer`, `proGift`, `proModelID`, `proSpec`,
					`proDetail`,`proImage`)
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
							'".$proImage."')";
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
	}
?>