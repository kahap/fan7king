<?php
	class API{
		var $db;
		private $table;
		private $idColumn;
		private $records;
		private $status; //1:OK 0:error
		private $message;
		private $data;
		private $result;
		//where����y
		private $whereArr = array(); //column=>value
		//join table����y
		private $joinArr = array(); //table=>column
		//group by
		private $groupArr = array();
		//order by
		private $orderArr = "";
		//�Q�n��������
		private $retrieveArr = array();
		//webView page
		private $webViewArr = array("front_manage","front_manage2");
		//���Ƭ��}�C��
		private $arrayDataList = array("proImage","orAppAuthenExtraInfo");
		//��Ƹ��|(KEY:��� VAL:���LADMIN(BOOL))
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
			"orAppAuthenPromiseLetter"=>true
		);
		
		//�غc�禡
		public function API($table){
			//�����Ʈw�w�q���e
			$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
			
			if(mysqli_num_rows(mysqli_query($this->db->oDbLink, "SHOW TABLES LIKE '".$table."'"))==1){
				//��ltable�W��
				$this->table = $table;
				
				//��lpk���W
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
					if($value != ""){
						foreach($value as $columnName=>&$columnValue){
							switch($columnName){
								case "memClass":
									switch($columnValue){
										case "0":
											$columnValue = "�ǥ�";
											break;
										case "1":
											$columnValue = "�W�Z��";
											break;
										case "2":
											$columnValue = "�a��";
											break;
										case "3":
											$columnValue = "��L";
											break;
									}
									break;
								case "memGender":
									switch($columnValue){
										case "0":
											$columnValue = "�k";
											break;
										case "1":
											$columnValue = "�k";
											break;
									}
									break;
								case "memRegistMethod":
									switch($columnValue){
										case "0":
											$columnValue = "FB�s��";
											break;
										case "1":
											$columnValue = "�@��ӽ�";
											break;
									}
									break;
								case "memEmailAuthen":
									switch($columnValue){
										case "0":
											$columnValue = "�|������";
											break;
										case "1":
											$columnValue = "�q�L����";
											break;
									}
									break;
								case "memAllowLogin":
									switch($columnValue){
										case "0":
											$columnValue = "���v";
											break;
										case "1":
											$columnValue = "���\�n�J";
											break;
									}
									break;
								case "memAllowLogin":
									switch($columnValue){
										case "0":
											$columnValue = "���v";
											break;
										case "1":
											$columnValue = "���\�n�J";
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
		
			foreach($array as $key =>$value){
				$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
				if($key != "adTokenId" && $key != $this->idColumn){
					if(array_pop(array_keys($array)) != $key){
						$sql .= " `".$key."` = '".$value."', ";
					}else{
						$sql .= " `".$key."` = '".$value."' ";
					}
				}
			}
			
			$sql .= " where `".$this->idColumn."` = '".$no."'";
			
			$update = $this->db->updateRecords($sql);
			
			if($update){
				$this->setInformation(true, 1, 1, "���\�ק�I");
			}else{
				$this->setInformation(false, 0, 0, "�ק異�ѡI");
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
			
			$this->setInformation($this->db->bInsertRecords, 1, $this->db->iNoOfRecords, "���\�s�W�I");
			
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
			
			$this->setInformation(true, 1, 1, "���\�R���I");
				
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
		
		public function setInformation($data,$status,$records,$msg){
			$this->data = $data;
			$this->status = $status;
			$this->records = $records;
			$this->message = $msg;
		}
		
		public function setResult($reCal=true){
			if($reCal){
				$curData = $this->changeToReadable($this->data);
				if($curData != null){
					foreach($curData as $key=>&$value){
						if($value != null){
							foreach($value as $keyIn=>&$valueIn){
								//���|�ন������|
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
								//�}�C�r���ഫ�}�C
								if(in_array($keyIn,$this->arrayDataList)){
									$valueIn = json_decode($valueIn);
								}
								//�������ର�}�C
								if($keyIn == "pmPeriodAmnt"){
									$valueIn = $this->calculatePeriodAmount($value["pmNo"]);
								}
								//�ӫ~�W����}�C
								if($keyIn == "proSpec" || $keyIn == "memSchool"){
									$valueIn = explode("#",$valueIn);
									foreach($valueIn as &$eachSpec){
										if($eachSpec == "�L"){
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
		
		
	}
?>