<?php
//require_once("../admin/cls/Product_Manage.cls.php");
//require_once("../admin/cls/Period_Setting.cls.php");
//require_once("../admin/cls/Product.cls.php");
require_once("../../../admin/cls/Product_Period.cls.php");
	class API{
		var $db;
		private $table;
		private $idColumn;
		private $records;
		private $status; //1:OK 0:error
		private $message;
		private $data;
		private $result;
		//where條件句
		private $whereArr = array(); //column=>value
		private $orArr = array(); //column=>value
		//join table條件句
		private $joinArr = array(); //table=>column
		//group by
		private $groupArr = array();
		//order by
		private $orderArr = "";
		//想要抓取的資料
		private $retrieveArr = array();
		//webView page
		private $webViewArr = array("front_manage","front_manage2");
		//當資料為陣列時
		private $arrayDataList = array(
			"proImage","orAppAuthenExtraInfo","orAppContactRelaName","orAppContactRelaRelation",
			"orAppContactRelaPhone","orAppContactRelaCell","orAppContactFrdName","orAppContactFrdRelation",
			"orAppContactFrdPhone","orAppContactFrdCell","mcoContactName","mcoContactRelation",
			"mcoContactPhone","mcoContactCell","mcoExtraInfoUpload"
		);
		//資料路徑(KEY:欄位 VAL:有無ADMIN(BOOL))
		private $pathArr = array(
			"proImage"=>false,
			"adImage"=>false,
			"catImage"=>false,
			"catIcon"=>false,
			"rbsIdTopImg"=>true,
			"rbsIdBotImg"=>true,
			"rbsBankBookImg"=>true,
			"orAppAuthenIdImgTop"=>true,
			"orAppAuthenIdImgBot"=>true,
			"orAppAuthenStudentIdImgTop"=>true,
			"orAppAuthenStudentIdImgBot"=>true,
			"orAppAuthenExtraInfo"=>true,
			"orAppAuthenProvement"=>true,
			"orAppAuthenPromiseLetter"=>true,
			"mcoIdImgTop"=>true,
			"mcoIdImgBot"=>true,
			"mcoStudentIdImgTop"=>true,
			"mcoStudentIdImgBot"=>true,
			"mcoSubIdImgTop"=>true,
			"mcoCarIdImgTop"=>true,
			"mcoBankBookImgTop"=>true,
			"mcoRecentTransactionImgTop"=>true,
			"mcoExtraInfoUpload"=>true
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
				$this->setInformation("", 0, 0, "Table not exist.");
				$this->setResult();
				throw new Exception($this->getResult());
			}
		}

		public function changeToReadable(&$data){
			if($data != null && is_array($data)){
				foreach($data as $key=>&$value){
					if($value != "" && is_array($value)) { // jimmy add is_array
						foreach($value as $columnName=>&$columnValue){
							switch($columnName){
								case "memClass":
									switch($columnValue){
										case "0":
											$columnValue = "學生";
											break;
										case "1":
											$columnValue = "上班族";
											break;
										case "2":
											$columnValue = "家管";
											break;
										case "3":
											$columnValue = "其他";
											break;
										case "4":
											$columnValue = "非學生";
											break;
									}
									break;
								case "memGender":
									switch($columnValue){
										case "0":
											$columnValue = "女";
											break;
										case "1":
											$columnValue = "男";
											break;
									}
									break;
								case "memRegistMethod":
									switch($columnValue){
										case "0":
											$columnValue = "FB連結";
											break;
										case "1":
											$columnValue = "一般申請";
											break;
									}
									break;
								case "memEmailAuthen":
									switch($columnValue){
										case "0":
											$columnValue = "尚未驗證";
											break;
										case "1":
											$columnValue = "通過驗證";
											break;
									}
									break;
								case "memAllowLogin":
									switch($columnValue){
										case "0":
											$columnValue = "停權";
											break;
										case "1":
											$columnValue = "允許登入";
											break;
									}
									break;
								case "memAllowLogin":
									switch($columnValue){
										case "0":
											$columnValue = "停權";
											break;
										case "1":
											$columnValue = "允許登入";
											break;
									}
									break;
							}
						}
					}
				}
			}
			return $data;
		}

		public function getAllWithColumns($array){
			$sql = "select ";
			foreach($array as $key=>$value){
				if(array_pop(array_keys($array)) != $key){
					$sql .= $value.", ";
				}else{
					$sql .= $value;
				}
			}
			$sql .= " from
					`".$this->table."`";
			$data = $this->db->selectRecords($sql);

			if($data != null){
				$this->setInformation($data, 1, $this->db->iNoOfRecords, "OK");
			}else{
				$this->setInformation($data, 0, 0, "No matches found.");
			}
			$this->setResult();
		}

		public function getAll($query="*",$desc=false){
			$sql = "select
					".$query."
				from
					`".$this->table."`";
			if(!empty($this->orderArr)){
				if($desc){
					$sql .= " order by ".$this->orderArr." desc";
				}else{
					$sql .= " order by ".$this->orderArr;
				}
			}else{
				if($desc){
					$sql .= " order by ".$this->idColumn." desc";
				}
			}
			$data = $this->db->selectRecords($sql);

			if($data != null){
				$this->setInformation($data, 1, $this->db->iNoOfRecords, "OK");
			}else{
				$this->setInformation($data, 0, 0, "No matches found.");
			}
			$this->setResult();
		}

		public function getOne($no,$query="*"){
			$sql = "select
					".$query."
				from
					`".$this->table."`
				where
					`".$this->idColumn."` = '".$no."'";
			$data = $this->db->selectRecords($sql);

			if($data != null){
				$this->setInformation($data, 1, $this->db->iNoOfRecords, "OK");
			}else{
				$this->setInformation($data, 0, 0, "No matches found.");
			}
			$this->setResult();
		}

		public function update($array,$no){
			$sql = "update
						`".$this->table."`
					set ";

			$arrkeys = array_keys($array);
			$lastArrKey = array_pop($arrkeys);
			foreach($array as $key =>$value){
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
				if($key != "adTokenId" && $key != $this->idColumn){
					if($lastArrKey != $key){
						$sql .= " `".$key."` = '".$value."', ";
					}else{
						$sql .= " `".$key."` = '".$value."' ";
					}
				}
			}

			$sql .= " where `".$this->idColumn."` = '".$no."'";

			$update = $this->db->updateRecords($sql);
			if($update){
				$this->setInformation(true, 1, 1, "成功修改！");
			}else{
				$this->setInformation(false, 0, 0, "修改失敗！");
			}

			$this->setResult();
		}

		//更新全部
		public function updateAll($array){
			$sql = "update
						`".$this->table."`
					set ";

			$arrkeys = array_keys($array);
			$lastArrKey = array_pop($arrkeys);
			foreach($array as $key =>$value){
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
				if($key != "adTokenId" && $key != $this->idColumn){
					if($lastArrKey != $key){
						$sql .= " `".$key."` = '".$value."', ";
					}else{
						$sql .= " `".$key."` = '".$value."' ";
					}
				}
			}

			$update = $this->db->updateRecords($sql);

			if($update){
				$this->setInformation(true, 1, 1, "成功修改！");
			}else{
				$this->setInformation(false, 0, 0, "修改失敗！");
			}

			$this->setResult();
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

			$this->setInformation($this->db->bInsertRecords, 1, $this->db->iNoOfRecords, "成功新增！");

			$this->setResult();
		}

		public function delete($array){
			$key = array_keys($array)[0];
			$value = $array[$key];
			$sql = "delete from
						`".$this->table."`
					where
						`".$key."` = '".$value."'";
			$delete = $this->db->deleteRecords($sql);

			$this->setInformation(true, 1, 1, "成功刪除！");

			$this->setResult();
		}
			public function getAllProdWithCatAndApplier($supNo){

			$sql = "select
						`product`.`proNo`,`product_manage`.`pmNo`,`proName`,`pmIfDirect`,`proImage`,`proSpec`,`pmDirectAmnt`,`pmPeriodAmnt`,`pmBuyAmnt`,`pmStatus`
					from
						`product`
					inner join
						`product_manage`
					on
						`product`.`proNo` = `product_manage`.`proNo`
					where
						`product_manage`.`supNo` = '".$supNo."' &&
						`product_manage`.`pmStatus` != '0'

					order by
						`product`.`proNo` ";

			$data = $this->db->selectRecords($sql);

			if($data != null){
				$this->setInformation($data, 1, $this->db->iNoOfRecords, "OK");
			}else{
				$this->setInformation($data, 0, 0, "No matches found.");
			}
           	$this->setResult();

	 }
		//取得所有商品上架
		public function getAllCatPM($cat){
			$sql = "select
						proImage
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

			if($data != null){
				$this->setInformation($data, 1, $this->db->iNoOfRecords, "OK");
			}else{
				$this->setInformation($data, 0, 0, "No matches found.");
			}
			$this->setResult();
		}

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

			if($data != null){
				$this->setInformation($data, 1, $this->db->iNoOfRecords, "OK");
			}else{
				$this->setInformation($data, 0, 0, "No matches found.");
			}
			$this->setResult();

		}

		public function getWithWhereAndJoinClause($desc=false){
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
			if(!empty($this->joinArr)){
				foreach($this->joinArr as $key=>$value){
					$sql .= " inner join ".$key."
							on `".$this->table."`.`".$value."` = `".$key."`.`".$value."` ";
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
			if(!empty($this->orArr)){
				foreach($this->orArr as $key=>$value){
					$sql .= " or `".$key."` = '".$value."' ";
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
				if($desc){
					$sql .= " order by ".$this->orderArr." desc";
				}else{
					$sql .= " order by ".$this->orderArr;
				}
			}else{
				if($desc){
					$sql .= " order by ".$this->idColumn." desc";
				}
			}
			$data = $this->db->selectRecords($sql);

			if($data != null){
				$this->setInformation($data, 1, $this->db->iNoOfRecords, "OK");
			}else{
				$this->setInformation($data, 0, 0, "No matches found.");
			}
			$this->setResult();
		}

		public function setWhereArray($array){
			$this->whereArr = $array;
		}

		public function setOrArray($array){
			$this->orArr = $array;
		}

		public function setJoinArray($array){
			$this->joinArr = $array;
		}

		public function setGroupArray($array){
			$this->groupArr = $array;
		}

		public function setRetrieveArray($array){
			$this->retrieveArr = $array;
		}

		public function setOrderArray($string){
			$this->orderArr = $string;
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

		public function setInformation($data,$status,$records,$msg){
			$this->data = $data;
			$this->status = $status;
			$this->records = $records;
			$this->message = $msg;
		}

		public function setResult($reCal=true){
			if($reCal){
				$curData = $this->changeToReadable($this->data);

				if($curData != null && is_array($curData)) {
					foreach($curData as $key=>&$value){
						if($value != null && is_array($value)){ // jimmy add is_array()
							foreach($value as $keyIn=>&$valueIn){
								//路徑轉成絕對路徑
								if(array_key_exists($keyIn,$this->pathArr)){
									if(!empty(json_decode($valueIn))){
										$curJsonObj = json_decode($valueIn);
										foreach($curJsonObj as $keyPath=>&$valPath){
											if($this->pathArr[$keyIn]){
												$valPath = "http://".DOMAIN."/".$valPath;
											}else{
												$valPath = "http://".DOMAIN."/admin/".$valPath;
											}
										}
										$valueIn = json_encode($curJsonObj,JSON_UNESCAPED_UNICODE);
									}else{
										if($valueIn != ""){
											if($this->pathArr[$keyIn]){
												$valueIn = "http://".DOMAIN."/".$valueIn;
											}else{
												$valueIn = "http://".DOMAIN."/admin/".$valueIn;
											}
										}
									}
								}
								//陣列字串轉換陣列
								if(in_array($keyIn,$this->arrayDataList)){
									$valueIn = json_decode($valueIn);
								}
								//分期價轉為陣列
								if($keyIn == "pmPeriodAmnt"){
									$valueIn = $this->calculatePeriodAmount($value["pmNo"]);
								}
								//商品規格轉陣列
								if($keyIn == "proSpec" || $keyIn == "memSchool"){
									$valueIn = explode("#",$valueIn);
									foreach($valueIn as &$eachSpec){
										if($eachSpec == "無"){
											$eachSpec = "";
										}
									}
								}
							}
						}
					}
				}
				$this->data = $curData;
			}

			$result = array(
					"data"=>$this->data,
					"status"=>$this->status,
					"records"=>$this->records,
					"message"=>$this->message
			);
			$this->result = $result;
		}

		//取得所有搜尋商品上架
		public function getSearchPM($array){
			foreach($array as $key => $value){
				$$key= mysqli_real_escape_string($this->db->oDbLink, $value);
			}
			$str = ($catNo != "0") ? "`product`.catNo = '".$catNo."' &&":'';
			$str .= "`product`.proName like '%".$search."%'";
			$sql = "select
						`product_manage`.`proNo`,`product_manage`.`pmNo`,
						`pmStatus`,`pmBuyAmnt`,`proName`,`pmIfDirect`,`proImage`,`pmDirectAmnt`,`pmPeriodAmnt`,`proSpec`
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
						`product_manage`.`pmUpDate` desc";
			$data = $this->db->selectRecords($sql);
			return $data;
		}

		//取得所有搜尋商品上架
		public function getCustomSql($sql){
			$data = $this->db->selectRecords($sql);
			return $data;
		}

		public function getData(){
			return $this->data;
		}

		public function getResult(){
			if(!in_array($this->table, $this->webViewArr)){
				return json_encode($this->result,JSON_UNESCAPED_UNICODE);
			}else{
				return "";
			}
		}

		public function calculatePeriodAmount($pmNo){
			$resultArr = array();

			$pm = new Product_Manage();
			$pro = new Product();
			$pp = new Product_Period();
			$ps =new Period_Setting();

			$pmData = $pm->getOnePMByNo($pmNo);
			$ppData = $pp->getPPByProduct($pmData[0]["proNo"]);
			$psData = $ps->getAllPS();

			$followDefault = true;
			if($ppData != null){
				foreach($ppData as $key=>$value){
					if($value["ppPercent"] != ""){
						$followDefault = false;
					}
				}
			}
			if($followDefault){
				$cur = 0;
				foreach($psData as $key=>$value){
					if((float) number_format($value["psRatio"], 2) == 1.00){
						$ifZero = true;
					}else{
						$ifZero = false;
					}
					$resultArr[$cur] = array(
						"period"=>$value["psMonthNum"],
						"amount"=>ceil($value["psRatio"]*$pmData[0]["pmPeriodAmnt"]/$value["psMonthNum"]),
						"advertise"=>"",
						"ifZero"=>$ifZero,
						"total"=>ceil($value["psRatio"]*$pmData[0]["pmPeriodAmnt"]/$value["psMonthNum"])*$value["psMonthNum"],
						"pmPeriodAmnt" => $pmData[0]["pmPeriodAmnt"]
					);
					$cur++ ;
				}
			}else{
				$cur = 0;
				foreach($ppData as $key=>$value){
					if($value["ppPercent"] != ""){
						if((float) number_format($value["ppPercent"], 2) == 1.0 || (float) number_format($value["ppPercent"], 2) == 1.00){
							$ifZero = true;
						}else{
							$ifZero = false;
						}
						$resultArr[$cur] = array(
							"period"=>$value["ppPeriodAmount"],
							"amount"=>ceil($value["ppPercent"]*$pmData[0]["pmPeriodAmnt"]/$value["ppPeriodAmount"]),
							"advertise"=>$value["ppIntroText"],
							"ifZero"=>$ifZero,
							"total"=>ceil($value["ppPercent"]*$pmData[0]["pmPeriodAmnt"]/$value["ppPeriodAmount"])*$value["ppPeriodAmount"],
							"pmPeriodAmnt" => $pmData[0]["pmPeriodAmnt"]
						);
					}
					$cur++;
				}
			}
			return $resultArr;
		}

	}
?>