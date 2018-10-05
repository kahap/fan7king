<?php
	class Product_Manage{
		var $db;
		
		//建構函式
		public function Product_Manage(){
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
		}
		
		//0/1轉成是/否
		public function changeNumberOrBack($ifNumber, &$str){
			if($ifNumber){
				switch($str){
					case 0:
						$str = "否";
						break;
					case 1:
						$str = "是";
						break;
				}
			}else{
				switch($str){
					case "否":
						$str = 0;
						break;
					case "是":
						$str = 1;
						break;
				}
			}
		}
		
		//將代號轉換
		public function changeToReadable(&$str){
			foreach($str as $key=>&$value){
				if($value == ""){
					$value = "無";
				}
			}
			$this->changeNumberOrBack(true, $str["pmMainSup"]);
			$this->changeNumberOrBack(true, $str["pmIfDirect"]);
			$this->changeNumberOrBack(true, $str["pmNewest"]);
			$this->changeNumberOrBack(true, $str["pmHot"]);
			$this->changeNumberOrBack(true, $str["pmSpecial"]);
			if($str["pmStatus"] != ""){
				switch($str["pmStatus"]){
					case 0:
						$str["pmStatus"] = "下架中";
						break;
					case 1:
						$str["pmStatus"] = "上架中";
						break;
					case 2:
						$str["pmStatus"] = "缺貨中";
						break;
				}
			}
		}
		
		
		
		//取得所有商品上架
		public function getAllPM(){
			$sql = "select
						*
					from
						`product_manage`
					inner join
						`supplier`
					on
						`supplier`.`supNo` = `product_manage`.`supNo`
					inner join
						`product`
					on
						`product`.`proNo` = `product_manage`.`proNo`
					where
						`product_manage`.`pmStatus` != '0'
					group by
						`product`.`proNo`
					order by
						`product_manage`.`pmNewestOrder` asc";
						
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//取得所有商品上架
		public function getAllPM_forCategory(){
			$sql = "select a.pmNo,a.supNo, a.proNo, a.supNo, a.pmPeriodAmnt,a.pmPeriodAmnt2, c.catNo,c.braNo,c.proName, c.proImage
					from 
						`product_manage` a, product c
					where
						a.`pmStatus` = '1' &&
						a.pmMainSup = '1' &&
						a.proNo = c.proNo 
					group by 
						c.proNo
					order by
						a.pmUpDate desc";
						
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//取得所有商品上架
		public function getAllPM_forSup($supNo){
			$supNo = mysql_real_escape_string($supNo);
			$sql = "select a.pmNo,a.supNo, a.proNo, a.supNo, a.pmPeriodAmnt,a.pmPeriodAmnt2, c.catNo,c.braNo,c.proName, c.proImage
					from 
						`product_manage` a, product c
					where
						a.`pmStatus` = '1' &&
						a.supNo = '".$supNo."' &&
						a.proNo = c.proNo 
					group by 
						c.proNo
					order by
						a.pmNo desc";
						
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//取得所有搜尋商品上架
		public function getSearchPM($array){
			foreach($array as $key => $value){
				$$key= mysqli_real_escape_string($this->db->oDbLink, $value);
			}
			echo $search;
			
			if($search != ''){
				$str = ($category != "0") ? " `product`.catNo = '".$category."' ":'';
				
				// $str .= " `product`.proName like '%".$search."%' ";
				
				$searchArray = explode(' ',$search);			
				foreach($searchArray as $searchKeyWord){
					if ($str !=""){
						$str .= " && ";
					}
					$str .= " `product`.proName like '%".$searchKeyWord."%' ";					
				}
				
			}else{
				$str = ($category != "0") ? " `product`.catNo = '".$category."'":' 1 ';
			}
			$sql = "select
						*
					from
						`product_manage`
					inner join
						`supplier`
					on
						`supplier`.`supNo` = `product_manage`.`supNo`
					inner join
						`product`
					on
						`product`.`proNo` = `product_manage`.`proNo`
					where
						`product_manage`.`pmStatus` != '0'  &&
						`product_manage`.`pmMainSup` = '1' &&
					".$str."
					order by
						`product`.`proName` asc";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//取得所有商品上架
		public function getAllCatPM($cat){
			$sql = "select
						*
					from
						`product_manage`
					inner join
						`supplier`
					on
						`supplier`.`supNo` = `product_manage`.`supNo`
					inner join
						`product`
					on
						`product`.`proNo` = `product_manage`.`proNo` 
					where
						`product`.`catNo` = '".$cat."' &&
						`product_manage`.`pmStatus` != '0' 
					group by
						`product`.`proNo`
					order by
						`product_manage`.`pmUpDate` desc,
						`product`.`proName` asc";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		public function getAllCatPMNew($cat){
			$sql = "select
						`product`.`proNo`,
						`product`.`proName`,
						`product`.`proImage`,
						`product_manage`.`pmPeriodAmnt`
					from
						`product_manage`
					inner join
						`supplier`
					on
						`supplier`.`supNo` = `product_manage`.`supNo`
					inner join
						`product`
					on
						`product`.`proNo` = `product_manage`.`proNo`
					where
						`product`.`catNo` = '".$cat."' &&
						`product_manage`.`pmStatus` != '0' &&
						`product_manage`.`pmMainSup` = '1'
					group by
						`product`.`proNo`
					order by
						`product_manage`.`pmUpDate` desc,
						`product`.`proName` asc
					limit 4";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//取得所有商品上架(依照商品名稱)
		public function getAllPMGroupByProName(){
			$sql = "select
						*
					from
						`product_manage`
					group by
						`proNo`
					order by
						`pmNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//編號取得單一商品上架
		public function getOnePMByNo($pmNo){
			$sql = "select
						*
					from
						`product_manage`
					where
						`pmNo`='".$pmNo."'";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		
		public function updatepmClickNum($proNo,$pmClickNum){
			$pmClickNum +=1;
			$sql = "update `product_manage` 
						set `pmClickNum` = '".$pmClickNum."'
					where
						`proNo`='".$proNo."'";
			$update = $this->db->updateRecords($sql);			
		}
		
		public function updatepmBuyAmnt($pmNo,$pmBuyAmnt){
			$pmBuyAmnt +=1;
			$sql = "update `product_manage` 
						set `pmBuyAmnt` = '".$pmBuyAmnt."'
					where
						`pmNo`='".$pmNo."'";
			$update = $this->db->updateRecords($sql);			
		}
		
		
		//取得商品在該供應商的上架資訊
		public function getOnePMBySupAndPro($proNo,$supNo){
			$sql = "select
						*
					from
						`product_manage`
					where
						`proNo`='".$proNo."'
					and
						`supNo`='".$supNo."'";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//依據商品取得該商品
		public function getAllByProName($proNo){
			$sql = "select
						*
					from
						`product_manage`
					inner join
						`supplier`
					on
						`supplier`.`supNo` = `product_manage`.`supNo`
					inner join
						`product`
					on
						`product`.`proNo` = `product_manage`.`proNo`
					where
						`product`.`proNo`='".$proNo."'
					and
						`product_manage`.`pmMainSup` = 1 &&
						`product_manage`.`pmStatus` = 1";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//依據商品取得該商品_供應商
		public function getAllByProName_Sup($proNo){
			$sql = "select
						*
					from
						`product_manage`
					inner join
						`supplier`
					on
						`supplier`.`supNo` = `product_manage`.`supNo`
					inner join
						`product`
					on
						`product`.`proNo` = `product_manage`.`proNo`
					where
						`product`.`proNo`='".$proNo."'
					and
						`product_manage`.`pmStatus` = 1";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//依據商品取得該商品並且group
		public function getAllByProNameAndGroup($proNo){
			$sql = "select
						*
					from
						`product_manage`
					inner join
						`supplier`
					on
						`supplier`.`supNo` = `product_manage`.`supNo`
					inner join
						`product`
					on
						`product`.`proNo` = `product_manage`.`proNo`
					where
						`product`.`proNo`='".$proNo."'
					group by
						`product`.`proNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//依據最新取得該商品並且group
		public function getAllNew(){
			$sql = "select
						*
					from
						`product_manage`
					inner join
						`product`
					on
						`product`.`proNo` = `product_manage`.`proNo`
					where
						`product_manage`.`pmNewest`= '1' &&
						`product_manage`.`pmStatus`!= '0' && 
						`product_manage`.`pmMainSup`= '1'
					group by
						`product`.`proNo`
					order by
						`product_manage`.`pmUpDate` desc
					limit 14	";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//依據精選取得該商品並且group
		public function getAllSpecial(){
			$sql = "select
						*
					from
						`product_manage`
					inner join
						`supplier`
					on
						`supplier`.`supNo` = `product_manage`.`supNo`
					inner join
						`product`
					on
						`product`.`proNo` = `product_manage`.`proNo`
					where
						`product_manage`.`pmSpecial`= '1' &&
						`product_manage`.`pmStatus`!= '0' && 
						`product_manage`.`pmMainSup`= '1'
					group by
						`product`.`proNo`
					order by
						`product_manage`.`pmSpecialOrder` asc
					limit 14	";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//依據限時取得該商品並且group
		public function getAllHot(){
			$sql = "select
						*
					from
						`product_manage`
					inner join
						`supplier`
					on
						`supplier`.`supNo` = `product_manage`.`supNo`
					inner join
						`product`
					on
						`product`.`proNo` = `product_manage`.`proNo`
					where
						`product_manage`.`pmHot`= '1' &&
						`product_manage`.`pmStatus`!= '0' && 
						`product_manage`.`pmMainSup`= '1'
					group by
						`product`.`proNo`
					order by
						`product_manage`.`pmHotOrder` asc
					limit 14";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//新增
		function insert($array){
			foreach($array as $key =>$value){
				$$key = $value;
			}
			$sql = "insert into `product_manage`(`proNo`, `supNo` ,`pmSupPrice`,`pmMainSup`,
					`pmPeriodAmnt`,`pmUpDate`,`pmIfDirect`,`pmDirectAmnt`,`pmStatus`,`pmNewest`,
					`pmHot`,`pmSpecial`,`pmPopular`,`pmNewestOrder`,`pmHotOrder`,`pmSpecialOrder`,
					`pmBuyAmnt`,`pmClickNum`)
					values('".$proNo."',
							'".$supNo."',
							'".$pmSupPrice."',
							'".$pmMainSup."',
							'".$pmPeriodAmnt."',
							'".$pmUpDate."',
							'".$pmIfDirect."',
							'".$pmDirectAmnt."',
							'0',
							'".$pmNewest."',
							'".$pmHot."',
							'".$pmSpecial."',
							'".$pmPopular."',
							'".$pmNewestOrder."',
							'".$pmHotOrder."',
							'".$pmSpecialOrder."',
							'0',
							'0')";
			$insert = $this->db->insertRecords($sql);
			return $insert;
		}
		
		//編輯主要供應商
		public function updateMainSup($pmMainSup,$pmNo,$supNo){
			$sql = "update
						`product_manage`
					set
						`pmMainSup`='".$pmMainSup."'
					where
						`pmNo`='".$pmNo."'
					and
						`supNo` = '".$supNo."'";
		
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯上架狀態
		public function updateStatus($pmStatus,$pmNo){
			$sql = "update
						`product_manage`
					set
						`pmStatus`='".$pmStatus."'
					where
						`pmNo`='".$pmNo."'";
		
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯供應價
		public function updateSupPrice($pmSupPrice,$pmNo){
			$sql = "update
						`product_manage`
					set
						`pmSupPrice`='".$pmSupPrice."'
					where
						`pmNo`='".$pmNo."'";
		
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯最新
		public function updateNewest($pmNewest,$pmNo){
			$sql = "update
						`product_manage`
					set
						`pmNewest`='".$pmNewest."'
					where
						`pmNo`='".$pmNo."'";
		
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯排序
		public function updateOrder($inputCol,$order,$pmNo){
			
			$sql = "update
						`product_manage`
					set
						`$inputCol`='".$order."'
					where
						`pmNo`='".$pmNo."'";
		
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		
		//編輯熱門
		public function updateHot($pmHot,$pmNo){
			$sql = "update
						`product_manage`
					set
						`pmHot`='".$pmHot."'
					where
						`pmNo`='".$pmNo."'";
		
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯精選
		public function updateSpecial($pmSpecial,$pmNo){
			$sql = "update
						`product_manage`
					set
						`pmSpecial`='".$pmSpecial."'
					where
						`pmNo`='".$pmNo."'";
		
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯灌水數
		public function updatePopular($pmPopular,$pmNo){
			$sql = "update
						`product_manage`
					set
						`pmPopular`='".$pmPopular."'
					where
						`pmNo`='".$pmNo."'";
		
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//編輯
		public function update($array,$proNo){
			foreach($array as $key =>$value){
				$$key = $value;
			}
			//若要統一更改上架狀態
// 			$sql = "update
// 						`product_manage`
// 					set
// 						`proNo`='".$proNo."',
// 						`pmPeriodAmnt`='".$pmPeriodAmnt."',
// 						`pmUpDate`='".$pmUpDate."',
// 						`pmIfDirect`='".$pmIfDirect."',
// 						`pmDirectAmnt`='".$pmDirectAmnt."',
// 						`pmNewest`='".$pmNewest."',
// 						`pmHot`='".$pmHot."',
// 						`pmSpecial`='".$pmSpecial."',
// 						`pmPopular`='".$pmPopular."',
// 						`pmStatus` = '".$pmStatus."'
// 					where
// 						`proNo`='".$proNo."'";
			$sql = "update
						`product_manage`
					set
						`proNo`='".$proNo."',
						`pmPeriodAmnt`='".$pmPeriodAmnt."',
						`pmUpDate`='".$pmUpDate."',
						`pmIfDirect`='".$pmIfDirect."',
						`pmDirectAmnt`='".$pmDirectAmnt."',
						`pmNewestOrder`='".$pmNewestOrder."',
						`pmHotOrder`='".$pmHotOrder."',
						`pmSpecialOrder`='".$pmSpecialOrder."',
						`pmNewest`='".$pmNewest."',
						`pmHot`='".$pmHot."',
						`pmSpecial`='".$pmSpecial."',
						`pmPopular`='".$pmPopular."'
					where
						`proNo`='".$proNo."'";
			
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//刪除
		function delete($pmNo){
			$sql = "delete from `product_manage` where `pmNo` = ".$pmNo;
			$delete = $this->db->deleteRecords($sql);
			return $delete;
		}
	}
?>