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
						`proNo`
					desc";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//取得所有商品(順序反)
		public function getAllProGroupbyCatNo($catNo){
			$sql = "SELECT p.catNo, p.braNo
						FROM  `product` AS p
						INNER JOIN product_manage AS pm ON p.`proNo` = pm.`proNo` 
						WHERE p.`catNo` =  '".$catNo."' && pm.pmStatus =  '1'
						GROUP BY  `braNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		//取得所有商品+品牌分類(順序反)
		public function getAllProGroupbyCatNobraNo($catNo,$brand){
			$sql = "select
						catNo,braNo
					from
						`product`
					where
						`catNo` = '".$catNo."' &&
						`braNo` = '".$braNo."'
					group by `braNo`";
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
		
		//編號取得單一商品(列表)
		public function getOneProByNoWithoutImage($proNo){
			$sql = "select
						proNo,proImage,proName
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
		
		//根據搜尋取得商品
		public function getSearchProduct($array){
			foreach($array as $key => $value){
				$$key= mysqli_real_escape_string($this->db->oDbLink, $value);
			}
			$str .= ($category != "0") ? "catNo = '".$category."' &&":'';
			$str .= "proName like '%".$search."%'";
			$sql = "select
						*
					from
						`product`
					where
						".$str;
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
		
		//新增
		function insert($array,$newProNo){
			foreach($array as $key =>$value){
				$$key = $value;
			}
			date_default_timezone_set('Asia/Taipei');
			$date = date('Y-m-d h:i:s', time());
			$sql = "insert into `product`(`proNo`,`catNo`, `braNo`, `proName` ,`proModelID`,`proSpec`,
					`proDetail`,`proImage`)
					values('".$newProNo."',
							'".$catNo."',
							'".$braNo."',
							'".$proName."',
							'".$proModelID."',
							'".$proSpec."',
							'".$proDetail."',
							'".$proImage."')";
			$insert = $this->db->insertRecords($sql);
			return $insert;
		}
		
		//編輯
		public function update($array,$proNo){
			foreach($array as $key =>$value){
				$$key = $value;
			}
			$sql = "update
						`product`
					set
						`catNo`='".$catNo."',
						`braNo`='".$braNo."',
						`proName`='".$proName."',
						`proModelID`='".$proModelID."',
						`proSpec`='".$proSpec."',
						`proDetail`='".$proDetail."',
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