<?php
	class Product_Period{
		var $db;
		
		//建構函式
		public function Product_Period(){
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			return TRUE;
		}
		
		
		//取得所有商品分期利率
		public function getAllPP(){
			$sql = "select
						*
					from
						`product_period`
					order by
						`ppNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//編號取得單一商品分期利率
		public function getOnePPByNo($ppNo){
			$sql = "select
						*
					from
						`product_period`
					where
						`ppNo`='".$ppNo."'";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//根據商品取得
		public function getPPByProduct($proNo){
			$sql = "select
						*
					from
						`product_period`
					where
						`proNo`='".$proNo."'
					order by
						`ppNo`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//根據商品取得
		public function getPPByProductAndPeriodsAmount($proNo,$ppPeriodAmount){
			$sql = "select
						*
					from
						`product_period`
					where
						`proNo`='".$proNo."'
					and
						`ppPeriodAmount` = '".$ppPeriodAmount."'";
			$data = $this->db->selectRecords($sql);
			return $data;
		}
		
		//新增
		function insert($array){
			foreach($array as $key =>$value){
				$$key = mysql_real_escape_string($value);
			}
			date_default_timezone_set('Asia/Taipei');
			$date = date('Y-m-d H:i:s', time());
			$sql = "insert into `product_period`(`proNo`,`ppPeriodAmount`,`ppPercent`,`ppIntroText` )
					values('".$proNo."',
							'".$ppPeriodAmount."',
							'".$ppPercent."',
							'".$ppIntroText."')";
			$insert = $this->db->insertRecords($sql);
			return $insert;
		}
		
		//編輯
		public function update($array){
			foreach($array as $key =>$value){
				$$key = mysql_real_escape_string($value);
			}
			$sql = "update
						`product_period`
					set
						`ppPeriodAmount`='".$ppPeriodAmount."',
						`ppPercent`='".$ppPercent."',
						`ppIntroText`='".$ppIntroText."'
					where
						`ppNo`='".$ppNo."'";
			
			$update = $this->db->updateRecords($sql);
			return $update;
		}
		
		//刪除
		function delete($ppNo){
			$sql = "delete from `product_period` where `ppNo` = ".$ppNo;
			$delete = $this->db->deleteRecords($sql);
			return $delete;
		}
	}
?>