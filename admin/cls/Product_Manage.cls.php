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
					order by
						`pmNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//取得所有商品上架(主要供應商)
		public function getAllPMMainSup(){
			$sql = "select
						*
					from
						`product_manage`
					where
						`pmMainSup` = '1'
					order by
						`pmNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//取得所有商品上架(依照商品名稱)
		public function getAllPMGroupByProName($p,$a, $search=0){
            $key = '';
            if ($search){
                $key = ' and `product`.`proName` LIKE "%'.$search.'%" ';
            }
			$sql = "select
						`product_manage`.proNo,pmMainSup,pmIfDirect,pmNewest,pmHot,pmSpecial,pmStatus,pmUpDate,pmPopular,pmBuyAmnt,pmClickNum
					from
						`product_manage`
                    join
                        `product` on `product_manage`.`proNo`=`product`.`proNo`
					where 
					`pmMainSup` = '1' 
					".$key." 
					group by
						`product_manage`.`proNo`
					order by
						`product_manage`.`pmUpDate` desc 
					limit " .$p. " , " .$a ;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		//取得所有商品上架(依照商品名稱) 總數
        public function getAllPMGroupByProNameCount($search=0){
            $key = '';
            if ($search){
                $key = ' and `product`.`proName` LIKE "%'.$search.'%" ';
            }
            $sql = "select
						`product_manage`.proNo,pmMainSup,pmIfDirect,pmNewest,pmHot,pmSpecial,pmStatus,pmUpDate,pmPopular,pmBuyAmnt,pmClickNum
					from
						`product_manage`
                    join
                        `product` on `product_manage`.`proNo`=`product`.`proNo`
					where 
					`pmMainSup` = '1' 
					".$key." 
					group by
						`product_manage`.`proNo`
					order by
						`product_manage`.`pmUpDate` desc " ;
            $data = $this->db->selectRecords($sql);
            return $this->db->iNoOfRecords;
        }
		
		//取得該供應商所有商品上架
		public function getAllPMBySupNo($supNo){
			$sql = "select
						*
					from
						`product_manage`
					where
						`supNo` = '".$supNo."'";
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
		public function getAllByProName($proNo,$p=1,$a=30){
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
					limit " .$p. " , " .$a ;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
        //依據商品取得該商品總數
        public function getAllByProNameCount($proNo){
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
						`product`.`proNo`='".$proNo."'";
            $data = $this->db->selectRecords($sql);
            return $this->db->iNoOfRecords;
        }
		
		//依據商品取得該商品並且group
		public function getAllByProNameAndGroup($proNo,$p,$a){
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
						`product`.`proNo` 
					limit " .$p. " , " .$a ;
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		public function getAllOrderByPm($proNo){
			$sql = "select
						*
					from
						`orders`
					inner join
						`product_manage`
					on
						`product_manage`.`pmNo` = `orders`.`pmNo`
					where
						`product_manage`.`proNo`='".$proNo."'";
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
						`supplier`
					on
						`supplier`.`supNo` = `product_manage`.`supNo`
					inner join
						`product`
					on
						`product`.`proNo` = `product_manage`.`proNo`
					where
						`product_manage`.`pmNewest`= '1'
					group by
						`product`.`proNo`";
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
						`product_manage`.`pmSpecial`= '1'
					group by
						`product`.`proNo`";
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
						`product_manage`.`pmHot`= '1'
					group by
						`product`.`proNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//新增
		function insert($array){
			foreach($array as $key =>$value){
                if (is_array($value)){
                    foreach ($value as $v){
                        $v = mysqli_real_escape_string($this->db->oDbLink, $v);
                    }
                    $$key = $value[0];
                }else {
                    $$key = mysqli_real_escape_string($this->db->oDbLink, $value);
                }
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
							'".$pmStatus."',
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
		
		//更換供應商更改訂單供貨價
		public function updatePmNoInOrder($pmNo,$orNo,$supNo){
			$sql = "update
						`orders`
					set
						`pmNo`='".$pmNo."',
						`supNo` =  '".$supNo."'
					where
						`orNo`='".$orNo."'";
		
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
			date_default_timezone_set('Asia/Taipei');
			$date = date('Y-m-d H:i:s', time());
			foreach($array as $key =>$value){
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
			}
			//若要統一更改上架狀態
			$sql = "update
						`product_manage`
					set
						`proNo`='".$proNo."',
						`pmPeriodAmnt`='".$pmPeriodAmnt."',
						`pmUpDate`='".$date."',
						`pmIfDirect`='".$pmIfDirect."',
						`pmDirectAmnt`='".$pmDirectAmnt."',
						`pmNewest`='".$pmNewest."',
						`pmHot`='".$pmHot."',";
			if(isset($newDate)){
				$sql .= "`pmUpDate`='".$pmUpDate."',";
			}
			$sql .= "`pmSpecial`='".$pmSpecial."',
						`pmPopular`='".$pmPopular."',
						`pmStatus` = '".$pmStatus."'
					where
						`proNo`='".$proNo."'";
			// $sql = "update
						// `product_manage`
					// set
						// `proNo`='".$proNo."',
						// `pmPeriodAmnt`='".$pmPeriodAmnt."',
						// `pmUpDate`='".$pmUpDate."',
						// `pmIfDirect`='".$pmIfDirect."',
						// `pmDirectAmnt`='".$pmDirectAmnt."',
						// `pmNewestOrder`='".$pmNewestOrder."',
						// `pmHotOrder`='".$pmHotOrder."',
						// `pmSpecialOrder`='".$pmSpecialOrder."',
						// `pmNewest`='".$pmNewest."',
						// `pmHot`='".$pmHot."',
						// `pmSpecial`='".$pmSpecial."',
						// `pmPopular`='".$pmPopular."'
					// where
						// `proNo`='".$proNo."'";
			
			$update = $this->db->updateRecords($sql);
			return $update;
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
		
		//刪除
		function delete($pmNo){
			$sql = "delete from `product_manage` where `pmNo` = ".$pmNo;
			$delete = $this->db->deleteRecords($sql);
			return $delete;
		}
	}
?>