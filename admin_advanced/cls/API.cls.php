<?php
	class API{
		var $db;
		private $table;
		private $idColumn;
		private $data;
		//where條件句
		private $whereArr = array(); //column=>value
		//where not條件句
		private $whereNotArr = array(); //column=>value
		//or條件句
		private $orArr = array(); //column=>value
		//or條件句
		private $orLikeArr = array(); //column=>value
		//join table條件句
		private $joinArr = array(); //table=>column
		//group by
		private $groupArr = array();
		//order by
		private $orderArr = array(); //column=>bool(true=asc,false=desc)
		
		private $limitArr = array(); //limit 50
		//想要抓取的資料
		private $retrieveArr = array();
		//custom sql
		private $customSql;

		//參考資料
		var $memClassArr = array(0=>"學生",1=>"上班族",2=>"家管",3=>"其他",4=>"非學生");
		var $statusArr = array(0=>"已下單，Email未驗證",110=>"未完成下單",1=>"未進件",2=>"審查中",3=>"核准",4=>"婉拒",
				5=>"待補",6=>"補件",7=>"取消訂單",701=>"客戶自行撤件",8=>"出貨中",9=>"已收貨",10=>"已完成",
				11=>"換貨中",12=>"退貨中",13=>"完成退貨");
		var $statusDirectArr = array(0=>"處理中",1=>"取消訂單",2=>"出貨中",3=>"已收貨",4=>"已完成",
				5=>"換貨中",6=>"退貨中",7=>"完成退貨");

		var $category = array('0'=>'3C產品','1'=>'手機貸款','2'=>'機車貸款');
		//補件原因
		var $reasonArr = array(0=>"無",1=>"自訂(需要打在下方欄位客人才能看到)",3=>"請重新上傳清楚不反光及不切字的【身分證】正反面，補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",4=>"請重新上傳清楚不反光及不切字的【學生證】正反面，補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",5=>"請重新補上第二步驟之正楷中文簽名，補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",6=>"請補上一親一友之姓名及市內電話資料，留言1.親屬:姓名、關係、手機、市內電話；2.朋友:姓名、手機，麻煩用LINE傳給我們，如有疑問請洽LINE客服，補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",7=>"請補上【軍人證】正反面影本，補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",8=>"請重新上傳提供最新補換發【身分證】正反面影本，補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",9=>"請您補上財力證明(近半年存摺[薪轉更加分])，有帳號的封面拍一張，若無帳號之存摺封面請再補拍帳號那一面一張，存摺內頁麻煩刷到最新，內頁上下兩頁合拍在一起(從今天往回推提供近6個月)不要反光不要模糊不要切邊之多張照片上傳用LINE傳遞。補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",10=>"請您確認戶籍地址、收貨地址以及現住地址是否完整，並補上正確資料。補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",11=>"請您補上勞保異動明細表。補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",12=>"請您登入學校網站需要有您學校名稱、姓名、學號的畫面，麻煩截圖上傳LINE客服。補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",13=>"請您提供一位法定代理人(父or母)當保證人(盡量有市內電話以及工作滿半年以上較佳)。您需先加入客服LINE索取保人申請書並詢問填寫及回覆方式，並上傳保人身分證正反面影本到LINE客服。補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",14=>"請您提供一位正職保證人(盡量有市內電話以及工作滿半年以上較佳)。您需先加入LINE客服索取保人申請書並詢問填寫及回覆方式，還要上傳保人身分證正反面影本一併傳到LINE客服。補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",15=>"請您補上可接照會電話時段。如審查時段早上9點~12點，下午13點~18點。您本人可接電話時段:ex:10-14點；您的聯絡人可接電話時段:ex:13-18點；您公司內有人可接照會電話時段：ex:9-12點；保人可接電話時段：ex:9-18點(若無告知補保人則免填)。補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件");
        var $IdPlace = array("北縣","宜縣","桃縣","竹縣","苗縣","中縣","彰縣","投縣","雲縣","嘉縣","南縣","高縣","屏縣","東縣","花縣","澎縣","基市","竹市","嘉市","連江","金門","北市","高市","新北市","中市","南市","桃市");


		//案件種類
		var $caseTypeArr = array(0=>"樂分期",1=>"手機貸款",2=>"機車貸款");
		//撥款狀態
		var $approStatusArr = array(0=>"待撥款",1=>"未撥款，可進行撥款",2=>"待確認撥款",3=>"撥款中",4=>"完成撥款");
		//結清狀態
		var $finishStatusArr = array(0=>"審核中",1=>"提前結清",2=>"到期結清",3=>"一般結清",4=>"代償",5=>"未結清");
		//催收聯絡方式
		var $urgeMethodArr = array(0=>"電話聯絡",1=>"存證信函");
		//催收狀態
		var $urgeStatusArr = array(
				0=>"無承諾還款日",1=>"約定繳款",2=>"確定繳款",3=>"多次聯絡不上",
				4=>"初次聯絡不上",5=>"爽約",6=>"其他"
		);
		//溢收款狀態
		var $extraPayStatusArr = array(
				0=>"無溢收款",1=>"尚未退還",2=>"已退還"
		);
		//聯絡人關係
		var $relationArr = array(
			"配偶","父母","子女","兄弟姐妹","祖父母","外祖父母","孫子女","外孫子女","配偶之父母","配偶之兄弟姐妹","其他親屬","負責人","股東","同事","朋友"
		);

		//建構函式
		public function API($table){
			//抓取資料庫定義內容
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);

			if(mysqli_num_rows(mysqli_query($this->db->oDbLink, "SHOW TABLES LIKE '".$table."'"))==1){
				//初始table名稱
				$this->table = $table;

				//初始pk欄位名
				$metaSql = "SHOW KEYS FROM ".$table." WHERE Key_name = 'PRIMARY'";
				$metaData = $this->db->selectRecords($metaSql);
				$this->idColumn = $metaData[0]['Column_name'];
				return TRUE;
			}else{
				print "$table<hr>";
				throw new Exception("Table does not exist.");
			}
		}


		public function getAll( $p,$a,  $desc=false){
			$sql = "select
					*
				from
					`".$this->table."`";
			if($desc){
				$sql .= " order by ".$this->idColumn." desc";
			}else{
				$sql .= " order by ".$this->idColumn;
			}
            $sql.=' limit '.$p.','.$a;
			$data = $this->db->selectRecords($sql);
			$this->data = $data;

			return $data;
		}
		public function getAllCount($desc=false){
            $sql = "select
                        *
                    from
                        `".$this->table."`";
            if($desc){
                $sql .= " order by ".$this->idColumn." desc";
            }else{
                $sql .= " order by ".$this->idColumn;
            }
            $data = $this->db->selectRecords($sql);
            return $this->db->iNoOfRecords;
        }


		public function getOne($no){
			$sql = "select
					*
				from
					`".$this->table."`
				where
					`".$this->idColumn."` = '".$no."'";
			$data = $this->db->selectRecords($sql);
			$this->data = $data;

			return $data;
		}

		//取得欄位中文名
		public function getAllColumnNames(){
			$sql = "select
						COLUMN_COMMENT, COLUMN_NAME
					from
						information_schema.COLUMNS
					WHERE
						TABLE_SCHEMA = 'happyfan_system'
					AND
						TABLE_NAME = '".$this->table."'";
			$data = $this->db->selectRecords($sql);
			return $data;
		}

		public function update($array,$no){
			$sql = "update
						`".$this->table."`
					set ";
			$arrKeys = array_keys($array);
			$lastKey = array_pop($arrKeys);
			foreach($array as $key =>$value){
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
				if($key != $this->idColumn){
					if($lastKey != $key){
						$sql .= " `".$key."` = '".str_replace("'","",$value)."', ";
					}else{
						$sql .= " `".$key."` = '".str_replace("'","",$value)."' ";
					}
				}
			}

			$sql .= " where `".$this->idColumn."` = '".$no."'";

			$update = $this->db->updateRecords($sql);

			return $update;
		}

		public function insert($array){
			$columns = "";
			$values = "";
			foreach($array as $key =>$value){
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
				$arrKeys = array_keys($array);
				$lastArrKey = array_pop($arrKeys);
				if($lastArrKey != $key){
					$columns .= "`".$key."`, ";
					$values .= "'".mysqli_real_escape_string($this->db->oDbLink, $value)."', ";
				}else{
					$columns .= "`".$key."`";
					$values .= "'".mysqli_real_escape_string($this->db->oDbLink, $value)."'";
				}
			}

			$sql = "insert into `".$this->table."`( ".$columns." )
			values( $values )";
			$insert = $this->db->insertRecords($sql);

			return $insert;
		}

		public function delete($array){
			$arrKeys = array_keys($array);
			$lastArrKey = array_pop($arrKeys);
			$sql = "delete from
						`".$this->table."`
					where ";
			foreach($array as $key=>$value){
				if($lastArrKey != $key){
					$sql .= " `".$key."` = '".$value."' and ";
				}else{
					$sql .= " `".$key."` = '".$value."' ";
				}
			}
			$delete = $this->db->deleteRecords($sql);

			return $delete;
		}

		public function getWithConditions($desc=false){
			$sql = "select ";
			if(!empty($this->retrieveArr)){
				// Get array keys
				$arrayKeys = array_keys($this->retrieveArr);
				// Fetch last array key
				$lastArrayKey = array_pop($arrayKeys);
				foreach($this->retrieveArr as $key=>$value){
					if($key != $lastArrayKey){
						$sql .= " ".$value.", ";
					}else{
						$sql .= " $value ";
					}
				}
			}else{
				$sql .= " * ";
			}
			$sql .= " from `".$this->table."` ";
			if (! empty ( $this->joinArr )) {
				foreach ( $this->joinArr as $key => $value ) {
					if ($this->table == "admin_advanced_user" && $key == "admin_advanced_roles" && $value == "aarNo") {
						$sql .= "," . $key . " ";
						$this->aarNo_JOIN_admin_advanced_roles = true;
					} else {
						$sql .= " inner join " . $key . "
								on `" . $this->table . "`.`" . $value . "` = `" . $key . "`.`" . $value . "` ";
					}
				}
			}
			if(!empty($this->whereArr)){
				$sql .= " where ";
				// Get array keys
				$arrayKeys = array_keys($this->whereArr);
				// Fetch last array key
				$lastArrayKey = array_pop($arrayKeys);
				foreach($this->whereArr as $key=>$value){
					if($key != $lastArrayKey){
						$sql .= " `".$key."` = '".$value."'
								 and ";
					}else{
						$sql .= " `".$key."` = '".$value."'";
					}
				}
			}
			if(!empty($this->whereNotArr)){
				if(!empty($this->whereArr)){
					$sql .= " and ";
					// Get array keys
					$arrayKeys = array_keys($this->whereNotArr);
					// Fetch last array key
					$lastArrayKey = array_pop($arrayKeys);
					foreach($this->whereNotArr as $key=>$value){
						if($key != $lastArrayKey){
							$sql .= " `".$key."` != '".$value."'
								 and ";
						}else{
							$sql .= " `".$key."` != '".$value."'";
						}
					}
				}else{
					$sql .= " where ";
					// Get array keys
					$arrayKeys = array_keys($this->whereNotArr);
					// Fetch last array key
					$lastArrayKey = array_pop($arrayKeys);
					foreach($this->whereNotArr as $key=>$value){
						if($key != $lastArrayKey){
							$sql .= " `".$key."` != '".$value."'
								 and ";
						}else{
							$sql .= " `".$key."` != '".$value."'";
						}
					}
				}
			}
			if(!empty($this->orArr)){
				if(!empty($this->whereArr)){
					$sql .= " or ";
					// Get array keys
					$arrayKeys = array_keys($this->orArr);
					// Fetch last array key
					$lastArrayKey = array_pop($arrayKeys);
					foreach($this->orArr as $key=>$value){
						if($key != $lastArrayKey){
							$sql .= " `".$key."` = '".$value."'
								 or ";
						}else{
							$sql .= " `".$key."` = '".$value."'";
						}
					}
				}else{
					$sql .= " where ";
					// Get array keys
					$arrayKeys = array_keys($this->orArr);
					// Fetch last array key
					$lastArrayKey = array_pop($arrayKeys);
					foreach($this->orArr as $key=>$value){
						if($key != $lastArrayKey){
							$sql .= " `".$key."` = '".$value."'
								 or ";
						}else{
							$sql .= " `".$key."` = '".$value."'";
						}
					}
				}
			}
			if(isset($this->aarNo_JOIN_admin_advanced_roles)){
				if(!empty($this->whereArr)){
					$sql .= " and ";
				}else{
					$sql .= " where ";
					$this->whereArr =true;
				}
				$sql .= '  `admin_advanced_user`.`aarNo` LIKE CONCAT ("%\"",`admin_advanced_roles`.`aarNo`,"\"%" )';
			}
			if(!empty($this->orLikeArr)){
				if(!empty($this->whereArr)){
					$sql .= " or ";
					// Get array keys
					$arrayKeys = array_keys($this->orLikeArr);
					// Fetch last array key
					$lastArrayKey = array_pop($arrayKeys);
					foreach($this->orLikeArr as $key=>$value){
						if($key != $lastArrayKey){
							$sql .= " `".$key."` like '%".$value."%'
								 or ";
						}else{
							$sql .= " `".$key."` like '%".$value."%'";
						}
					}
				}else{
					$sql .= " where ";
					// Get array keys
					$arrayKeys = array_keys($this->orLikeArr);
					// Fetch last array key
					$lastArrayKey = array_pop($arrayKeys);
					foreach($this->orLikeArr as $key=>$value){
						if($key != $lastArrayKey){
							$sql .= " `".$key."` like '%".$value."%'
								 or ";
						}else{
							$sql .= " `".$key."` like '%".$value."%'";
						}
					}
				}
			}
			if(!empty($this->groupArr)){
				$sql .= " group by ";
				// Get array keys
				$arrayKeys = array_keys($this->groupArr);
				// Fetch last array key
				$lastArrayKey = array_pop($arrayKeys);
				foreach($this->groupArr as $key=>$value){
					if($key != $lastArrayKey){
						$sql .= " `".$value."`, ";
					}else{
						$sql .= " `".$value."` ";
					}
				}
			}
			if(!empty($this->orderArr)){
				$sql .= " order by ";
				$arrayKeys = array_keys($this->orderArr);
				// Fetch last array key
				$lastArrayKey = array_pop($arrayKeys);
				foreach($this->orderArr as $key=>$value){
					if($value){
						if($key != $lastArrayKey){
							$sql .= $key." desc, ";
						}else{
							$sql .= $key." desc ";
						}
					}else{
						if($key != $lastArrayKey){
							$sql .= $key.", ";
						}else{
							$sql .= $key;
						}
					}
				}
			}else{
				if($desc){
					$sql .= " order by ".$this->idColumn." desc";
				}else{
					$sql .= " order by ".$this->idColumn;
				}
			}
			
			if(!empty($this->limitArr)){
				$sql .= " limit ".$this->limitArr." ";	
			}
			$data = $this->db->selectRecords($sql);
			$this->data = $data;
			return $data;
		}

		public function customSql($sql){
			$data = $this->db->selectRecords($sql);
			$this->data = $data;

			return $data;
		}

		public function getAllOverdue(){
			$sql = "select
					*
				from
					`tpi`
				where
					CURDATE() > `tpiSupposeDate`
				and
					tpiActualDate = ''
				and
				    tpiIfCancelPenalty = 0
				and 
					tpiOverdueDays = '3'";
			$data = $this->db->selectRecords($sql);
			$this->data = $data;

			return $data;
		}

		public function getAllOverdueForUrge($delay_date){
			$sql = "select
					*
				from
					`tpi`
				where
					CURDATE() > `tpiSupposeDate`
				and
					tpiActualDate = ''
				and
				    tpiIfCancelPenalty = 0
				and
					tpiOverdueDays >= '".$delay_date."'
				group by
					`rcNo`";
			$data = $this->db->selectRecords($sql);
			$this->data = $data;

			return $data;
		}

		public function getUploadHistory($purType){
			$sql = "select
					*
				from
					`pay_upload_records`
				where
					`purDate`
				between
					DATE_SUB(NOW(), INTERVAL 1 MONTH)
				and
					NOW()
				and
					`purType` = '".$purType."'";
			$data = $this->db->selectRecords($sql);
			$this->data = $data;

			return $data;
		}

		//抓取滯納金金額
		public function getPenaltyAmount(){
			$sql = "select
					*
				from
					`other_setting_advanced`";
			$data = $this->db->selectRecords($sql);
			$this->data = $data;

			return $data[0]["penaltyAmount"];
		}

		//取得當日撥款案件
		public function getFinishedApproForDate($date,$searchDateEnd,$selectType){
			date_default_timezone_set('Asia/Taipei');
			$nextDay = $searchDateEnd;
			switch($selectType){
				case "0":
					$sql = "select a.rcNo,a.rcCaseNo,a.rcPeriodAmount,a.rcPeriodTotal,a.tbNo,a.memNo,a.rcApproDate,a.rcPredictGetDate,a.rcType,b.memIdNum,b.memName
					from
						`real_cases` a, member b
					where
						a.`rcPredictGetDate`
					between
						'".$date."'
					and
						'".$nextDay."'
					and
						a.`rcApproStatus` = 4
					and
						a.`tbNo` = '2'
					and 
						a.memNo = b.memNo";
				break;
				
				case "1":
					$sql = "select a.rcNo,a.rcCaseNo,a.rcPeriodAmount,a.rcPeriodTotal,a.tbNo,a.memNo,a.rcApproDate,a.rcPredictGetDate,a.rcType,b.memIdNum,b.memName
					from
						`real_cases` a, member b
					where
						a.`rcPredictGetDate`
					between
						'".$date."'
					and
						'".$nextDay."'
					and
						a.`rcApproStatus` = '4'
					and 
						a.memNo = b.memNo";
				break;
				
				case "2":
					$sql = "select a.rcNo,a.rcCaseNo,a.rcPeriodAmount,a.rcPeriodTotal,a.tbNo,a.memNo,a.rcApproDate,a.rcPredictGetDate,a.rcType,b.memIdNum,b.memName
					from
						`real_cases` a, member b
					where
						a.`rcPredictGetDate`
					between
						'".$date."'
					and
						'".$nextDay."'
					and
						a.`rcApproStatus` = '4'
					and
						a.`receiptNumber` = ''
					and 
						a.memNo = b.memNo";
				break;
				
				default:
					$sql = "select a.rcNo,a.rcCaseNo,a.rcPeriodAmount,a.rcPeriodTotal,a.tbNo,a.memNo,a.rcApproDate,a.rcPredictGetDate,a.rcType,b.memIdNum,b.memName
					from
						`real_cases` a, member b
					where
						a.`rcPredictGetDate`
					between
						'".$date."'
					and
						'".$nextDay."'
					and
						a.`rcApproStatus` = 4
					and
						a.`tbNo` = '2'
					and
						a.memNo = b.memNo";
				break;
				
				
			}
			
			
			$data = $this->db->selectRecords($sql);
			$this->data = $data;

			return $data;
		}

		//取得欲開發票案件
		public function getFinishedApproForExport($date,$searchDateEnd,$selectType){
			date_default_timezone_set('Asia/Taipei');
			$nextDay = $searchDateEnd;
			switch($selectType){
				case "1":
					$str = " and `rcInvoiceNumber` != ''";
				break;

				case "2":
					$str = " and `rcInvoiceNumber` = ''";
				break;

				case "3":
					$str = " and `receiptNumber` != ''";
				break;

				case "4":
					$str = " and `receiptNumber` = ''";
				break;

				default:
					$str = "";
				break;
			}
			$sql = "select
					*
				from
					`real_cases`
				where
					`rcApproDate`
				between
					'".$date."'
				and
					'".$nextDay."'
				and
					`rcApproStatus` = 4".$str;
			$data = $this->db->selectRecords($sql);
			$this->data = $data;

			return $data;
		}

		//取得用戶TOKEN
		public function getUniqueDeviceToken($memNo){
			$sql = "SELECT *
					FROM (
						SELECT *
						from app_data
						ORDER BY time desc
					) AS sub
					where memNo = ".$memNo."
					GROUP BY adDeviceId;";

			$data = $this->db->selectRecords($sql);

			return $data;
		}

		//取得所有授信人員統計案件
		public function getAuthenStatistics(){
			$sql = "SELECT `aauNoAuthen`, YEAR(`rcStatus2Time`) as year, MONTH(`rcStatus2Time`) as month,sum(case `rcStatus` when '3' then 1 else 0 end) as 'rcStatus3',sum(case `rcStatus` when '5' then 1 else 0 end) as 'rcStatus5',sum(case `rcStatus` when '7' then 1 else 0 end) as 'rcStatus7',sum(case `rcStatus` when '701' then 1 else 0 end) as 'rcStatus701',sum(case `rcStatus` when '4' then 1 else 0 end) as 'rcStatus4' FROM `real_cases` where `rcIfAuthen` = 1 and `rcStatus` > 2 group by `aauNoAuthen`, YEAR(`rcStatus2Time`), MONTH(`rcStatus2Time`) order by month";
			$data = $this->db->selectRecords($sql);
			return $data;
		}

		//取得案件編號
		public function getCaseNo($type){
			$start = date('Ymd',time());
			$end = date('Y-m-d',time()+86400);
			$sql = "SELECT count(*) as number FROM `real_cases` WHERE `rcType` = 0 and `rcCaseNo` like '".$start."%'";
			$data = $this->db->selectRecords($sql);
			$orNum = $data[0]['number'];
			$loansNum = $this->loans_number($type);
			if($type == 1){
				$caseNo = ($orNum + $loansNum + 5001);
			}else{
				$caseNo = ($loansNum + 3001);
			}
			$product_number = date('Ymd',time()).$caseNo;
			return $product_number;
		}

		public function loans_number($type){
			$start = date('Ymd',time());
			$end = date('Y-m-d',time()+86400);
			if($type == 1){
				$sql = "SELECT count(*) as number FROM `real_cases` WHERE `rcType` = 1 and `rcCaseNo` like '".$start."%'";
			}else{
				$sql = "SELECT count(*) as number FROM `real_cases` WHERE `rcType` = 2 and `rcCaseNo` like '".$start."%'";
			}
			$data = $this->db->selectRecords($sql);
			return $data[0]['number'];
		}

		public function setWhereArray($array){
			$this->whereArr = $array;
		}

		public function setWhereNotArray($array){
			$this->whereNotArr = $array;
		}

		public function setOrArray($array){
			$this->orArr = $array;
		}

		public function setOrLikeArray($array){
			$this->orLikeArr = $array;
		}

		public function setJoinArray($array){
			$this->joinArr = $array;
		}
		
		public function limitArr($value){
			$this->limitArr = $value;
		}

		public function setGroupArray($array){
			$this->groupArr = $array;
		}

		public function setRetrieveArray($array){
			$this->retrieveArr = $array;
		}

		public function setOrderArray($array){
			$this->orderArr = $array;
		}

		public function setCustomSql($sql){
			$this->customSql = $sql;
		}


		public function getData(){
			return $this->data;
		}

		public function getIdColumn(){
			return $this->idColumn;
		}


		public function getauthenForDate($stardate,$enddate){
			if($stardate == ''){
				$str = "= CURDATE()";
			}elseif($stardate != '' and $enddate == ""){
				$str = "between '".$stardate."' and CURDATE()";
			}else{
				$str = "between '".$stardate."' and '".$enddate."'";
			}
			$sql = "SELECT `aauNoAuthen`,SUM(CASE WHEN `member`.`memClass` = '0' and `rcStatus` = 3 and DATE(`rcStatus3Time`) ".$str." THEN 1 ELSE 0 END) as Stustatus3,SUM(CASE WHEN `member`.`memClass` = '4' and `rcStatus` = 3 and DATE(`rcStatus3Time`) ".$str." THEN 1 ELSE 0 END) as status3,SUM(CASE WHEN `member`.`memClass` = '0' and `rcStatus` = 4 and DATE(`rcStatus4Time`) ".$str." THEN 1 ELSE 0 END) as Stustatus4,SUM(CASE WHEN `member`.`memClass` = '4' and `rcStatus` = 4 and DATE(`rcStatus4Time`) ".$str." THEN 1 ELSE 0 END) as status4,SUM(CASE WHEN `member`.`memClass` = '0' and `rcStatus` = 5 and DATE(`rcStatus5Time`) ".$str." THEN 1 ELSE 0 END) as Stustatus5,SUM(CASE WHEN `member`.`memClass` = '4' and `rcStatus` = 5 and DATE(`rcStatus5Time`) ".$str." THEN 1 ELSE 0 END) as status5,SUM(CASE WHEN `member`.`memClass` = '0' and `rcStatus` = 7 and DATE(`rcStatus7Time`) ".$str." THEN 1 ELSE 0 END) as Stustatus7,SUM(CASE WHEN `member`.`memClass` = '4' and `rcStatus` = 7 and DATE(`rcStatus7Time`) ".$str." THEN 1 ELSE 0 END) as status7,SUM(CASE WHEN `member`.`memClass` = '0' and `rcStatus` = 701 and DATE(`rcStatus701Time`) ".$str." THEN 1 ELSE 0 END) as Stustatus701,SUM(CASE WHEN `member`.`memClass` = '4' and `rcStatus` = 701 and DATE(`rcStatus701Time`) ".$str." THEN 1 ELSE 0 END) as status701 FROM `real_cases` JOIN member ON `real_cases`.`memNo` = member.memNo group by `real_cases`.`aauNoAuthen`";
			$data = $this->db->selectRecords($sql);
			return $data;
		}

		//fix time
		public function updateBarCode($barNoArray, $newBarArray, $tpiNo) {

			$sql = "UPDATE `barcode` SET `barBarcode` = CASE `barNo`";
			$sql .= " WHEN '" . $barNoArray[0] . "' THEN '" . $newBarArray[0] . "'";
			$sql .= " WHEN '" . $barNoArray[1] . "' THEN '" . $newBarArray[1] . "'";
			$sql .= " WHEN '" . $barNoArray[2] . "' THEN '" . $newBarArray[2] . "'";
			$sql .= " END";
			$sql .= " WHERE `tpiNo` = '" . $tpiNo . "' AND `barNo` IN('"
			. $barNoArray[0] . "','" . $barNoArray[1] . "','" . $barNoArray[2] . "');";

			$update = $this->db->updateRecords($sql);

			return $update;
		}

		//fix time
		public function insertCMCBarCode($newBarArray, $tpiNo) {

			$sql = "INSERT INTO `barcode` (`tpiNo`, `barIndex`, `barBarcode`, `barType`) VALUES ";
			$sql .= "(" . $tpiNo . ", 1, '" . $newBarArray[0] . "', 1),";
			$sql .= "(" . $tpiNo . ", 2, '" . $newBarArray[1] . "', 1),";
			$sql .= "(" . $tpiNo . ", 3, '" . $newBarArray[2] . "', 1);";
			$insert = $this->db->insertRecords($sql);

			return $insert;
		}

		//fix time
		public function selectBarCode($tpiNo) {

			$sql = "SELECT `barNo` FROM `barcode` WHERE `tpiNo` = '" . $tpiNo . "' ORDER BY `barNo`;";

			$data = $this->db->selectRecords($sql);
			$this->data = $data;

			return $data;
		}
		
		

	}
?>