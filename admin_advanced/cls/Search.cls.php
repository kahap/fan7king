<?php
class Search{
	var $db;

	public function __construct(){
		//抓取資料庫定義內容
		$this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
	}

	public function searchData($data, $p,$a, $dateType=1 ){
		if ($data)foreach($data as $key=>$value){
			$$key = $value;
		}
		$sql = "select
				 *
				from
				 real_cases as rc
				inner join
				 member as mem
				on
				 rc.memNo = mem.memNo
				where ";
        if ($data) {
            if ($startDate != "" || $endDate != "") {
                if ($startDate == "") {
                    $startDate = date("Y-m-d", time());
                }
                if ($endDate == "") {
                    $endDate = $startDate;
                }
                $endDate = date('Y-m-d', strtotime("+1 day", strtotime($endDate)));
                if ($dateType == "1") {
                    $sql .= " rc.rcDate between '" . $startDate . "' and '" . $endDate . "' and ";
                } else {
                    $sql .= " rc.rcStatus2Time between '" . $startDate . "' and '" . $endDate . "' and ";
                }
            }
            if ($memName != "") {
                $sql .= " mem.memName like '%" . $memName . "%' and";
            }
            if ($memIdNum != "") {
                $sql .= " mem.memIdNum like '%" . $memIdNum . "%' and";
            }
            if ($rcCaseNo != "") {
                $sql .= " rc.rcCaseNo like '%" . $rcCaseNo . "%' and";
            }
            if ($rcType != "") {
                $sql .= " rc.rcType = '" . $rcType . "' and";
            }
            if ($supNo != "") {
                $sql .= " rc.supNo = '" . $supNo . "' and";
            }
            if ($rcStatus != "") {
                $sql .= " rc.rcStatus = '" . $rcStatus . "' and";
            }
        }
		$sql .= " 1 = 1";
        if ($data) {
            if ($orCaseNo != "") {

                if (strlen($orCaseNo) == 13) {
                    $sql = "select	 rc.rcStatus6Time,
rc.rcNo,rc.rcType,rc.memNo,rc.rcRelateDataNo,rc.rcCaseNo,ord.orCaseNo as CaseNo,
rc.rcStatus,mem.memName,mem.memIdNum,mem.memClass,rc.rcIfCredit,rc.aauNoCredit,rc.aauNoAuthen,rc.rcIfAuthen,rc.rcDate,ord.orPeriodTotal as rcPeriodTotal,
ord.orPeriodAmnt as rcPeriodAmount 
                    from real_cases as rc
					inner join member as mem on rc.memNo = mem.memNo
					inner join orders as ord on rc.rcRelateDataNo = ord.orNo  where
					ord.orCaseNo='" . $orCaseNo . "' ";
                    if ($rcType != "") {
                        $sql .= " and rc.rcType = '" . $rcType . "' ";
                    }
                    $sql .= "UNION
				        select	rc.rcNo,rc.rcType,rc.memNo,rc.rcRelateDataNo,rc.rcCaseNo ,moto.mcoCaseNo as CaseNo,rc.rcStatus,mem.memName,mem.memIdNum,mem.memClass,rc.rcIfCredit,rc.aauNoCredit,rc.aauNoAuthen,rc.rcIfAuthen,rc.rcDate,moto.mcoPeriodTotal as rcPeriodTotal,moto.mcoMinMonthlyTotal as rcPeriodAmount from real_cases as rc
					        inner join member as mem on rc.memNo = mem.memNo
					        inner join motorbike_cellphone_orders as moto on rc.rcRelateDataNo = moto.mcoNo  where
					        moto.mcoCaseNo='" . $orCaseNo . "'";
                } else {
                    $sql = "select	 rc.rcStatus6Time,
rc.rcNo,rc.rcType,rc.memNo,rc.rcRelateDataNo,rc.rcCaseNo,ord.orCaseNo as CaseNo,
rc.rcStatus,mem.memName,mem.memIdNum,mem.memClass,rc.rcIfCredit,rc.aauNoCredit,rc.aauNoAuthen,rc.rcIfAuthen,rc.rcDate ,ord.orPeriodTotal as rcPeriodTotal,
ord.orPeriodAmnt as rcPeriodAmount 
                    from real_cases as rc
					inner join member as mem on rc.memNo = mem.memNo
					inner join orders as ord on rc.rcRelateDataNo = ord.orNo  where
					ord.orCaseNo like '%" . $orCaseNo . "%' ";
                    if ($rcType != "") {
                        $sql .= " and rc.rcType = '" . $rcType . "' ";
                    }
                    $sql .= "UNION
				        select	rc.rcNo,rc.rcType,rc.memNo,rc.rcRelateDataNo,rc.rcCaseNo ,moto.mcoCaseNo as CaseNo,rc.rcStatus,mem.memName,mem.memIdNum,mem.memClass,rc.rcIfCredit,rc.aauNoCredit,rc.aauNoAuthen,rc.rcIfAuthen,rc.rcDate ,moto.mcoPeriodTotal as rcPeriodTotal,moto.mcoMinMonthlyTotal as rcPeriodAmount from real_cases as rc
					        inner join member as mem on rc.memNo = mem.memNo
					        inner join motorbike_cellphone_orders as moto on rc.rcRelateDataNo = moto.mcoNo  where
					        moto.mcoCaseNo like '%" . $orCaseNo . "%'";
                }
            }
        }
		$sql.=' limit '.$p.','.$a;

		$data = $this->db->selectRecords($sql);
		return $data;
	}
	// 搜尋完回傳資料筆數
    public function getSearchDataCount($data, $dateType=1 ){
        if ($data)foreach($data as $key=>$value){
            $$key = $value;
        }
        $sql = "select
				 *
				from
				 real_cases as rc
				inner join
				 member as mem
				on
				 rc.memNo = mem.memNo
				where ";
        if ($data) {
            if ($startDate != "" || $endDate != "") {
                if ($startDate == "") {
                    $startDate = date("Y-m-d", time());
                }
                if ($endDate == "") {
                    $endDate = $startDate;
                }
                $endDate = date('Y-m-d', strtotime("+1 day", strtotime($endDate)));
                if ($dateType == "1") {
                    $sql .= " rc.rcDate between '" . $startDate . "' and '" . $endDate . "' and ";
                } else {
                    $sql .= " rc.rcStatus2Time between '" . $startDate . "' and '" . $endDate . "' and ";
                }
            }
            if ($memName != "") {
                $sql .= " mem.memName like '%" . $memName . "%' and";
            }
            if ($memIdNum != "") {
                $sql .= " mem.memIdNum like '%" . $memIdNum . "%' and";
            }
            if ($rcCaseNo != "") {
                $sql .= " rc.rcCaseNo like '%" . $rcCaseNo . "%' and";
            }
            if ($rcType != "") {
                $sql .= " rc.rcType = '" . $rcType . "' and";
            }
            if ($supNo != "") {
                $sql .= " rc.supNo = '" . $supNo . "' and";
            }
            if ($rcStatus != "") {
                $sql .= " rc.rcStatus = '" . $rcStatus . "' and";
            }
        }
        $sql .= " 1 = 1";
        if ($data) {
            if ($orCaseNo != "") {

                if (strlen($orCaseNo) == 13) {
                    $sql = "select	 rc.rcNo,rc.rcType,rc.memNo,rc.rcRelateDataNo,rc.rcCaseNo,ord.orCaseNo as CaseNo,rc.rcStatus,mem.memName,mem.memIdNum,mem.memClass,rc.rcIfCredit,rc.aauNoCredit,rc.aauNoAuthen,rc.rcIfAuthen,rc.rcDate,ord.orPeriodTotal as rcPeriodTotal,ord.orPeriodAmnt as rcPeriodAmount from real_cases as rc
					inner join member as mem on rc.memNo = mem.memNo
					inner join orders as ord on rc.rcRelateDataNo = ord.orNo  where
					ord.orCaseNo='" . $orCaseNo . "' ";
                    if ($rcType != "") {
                        $sql .= " and rc.rcType = '" . $rcType . "' ";
                    }
                    $sql .= "UNION
				        select	rc.rcNo,rc.rcType,rc.memNo,rc.rcRelateDataNo,rc.rcCaseNo ,moto.mcoCaseNo as CaseNo,rc.rcStatus,mem.memName,mem.memIdNum,mem.memClass,rc.rcIfCredit,rc.aauNoCredit,rc.aauNoAuthen,rc.rcIfAuthen,rc.rcDate,moto.mcoPeriodTotal as rcPeriodTotal,moto.mcoMinMonthlyTotal as rcPeriodAmount from real_cases as rc
					        inner join member as mem on rc.memNo = mem.memNo
					        inner join motorbike_cellphone_orders as moto on rc.rcRelateDataNo = moto.mcoNo  where
					        moto.mcoCaseNo='" . $orCaseNo . "'";
                } else {
                    $sql = "select	 rc.rcNo,rc.rcType,rc.memNo,rc.rcRelateDataNo,rc.rcCaseNo,ord.orCaseNo as CaseNo,rc.rcStatus,mem.memName,mem.memIdNum,mem.memClass,rc.rcIfCredit,rc.aauNoCredit,rc.aauNoAuthen,rc.rcIfAuthen,rc.rcDate,ord.orPeriodTotal as rcPeriodTotal,ord.orPeriodAmnt as rcPeriodAmount from real_cases as rc
					inner join member as mem on rc.memNo = mem.memNo
					inner join orders as ord on rc.rcRelateDataNo = ord.orNo  where
					ord.orCaseNo like '%" . $orCaseNo . "%' ";
                    if ($rcType != "") {
                        $sql .= " and rc.rcType = '" . $rcType . "' ";
                    }
                    $sql .= "UNION
				        select	rc.rcNo,rc.rcType,rc.memNo,rc.rcRelateDataNo,rc.rcCaseNo ,moto.mcoCaseNo as CaseNo,rc.rcStatus,mem.memName,mem.memIdNum,mem.memClass,rc.rcIfCredit,rc.aauNoCredit,rc.aauNoAuthen,rc.rcIfAuthen,rc.rcDate,moto.mcoPeriodTotal as rcPeriodTotal,moto.mcoMinMonthlyTotal as rcPeriodAmount from real_cases as rc
					        inner join member as mem on rc.memNo = mem.memNo
					        inner join motorbike_cellphone_orders as moto on rc.rcRelateDataNo = moto.mcoNo  where
					        moto.mcoCaseNo like '%" . $orCaseNo . "%'";
                }
            }
        }
        $data = $this->db->selectRecords($sql);
	    return $this->db->iNoOfRecords;
    }


	public function searchDataForAllan($data,$dateType=1){
		foreach($data as $key=>$value){
			$$key = $value;
		}
		$sql = $sql = "select
				 *
				from
				 real_cases as rc
				inner join
				 member as mem
				on
				 rc.memNo = mem.memNo
				where rc.rcCaseNo != '' and";
		if($startDate != "" || $endDate != ""){
			if($startDate == ""){
				$startDate = date("Ymd",time());
			}
			if($endDate == ""){
				$endDate = $startDate;
			}
			$startDate = date('Ymd', strtotime($startDate));
			$endDate = date('Ymd', strtotime("+1 day", strtotime($endDate)));
			$sql .= " rc.rcCaseNo between '".$startDate."' and '".$endDate."' and ";
		}
		if($memName != ""){
			$sql .= " mem.memName like '%".$memName."%' and";
		}
		if($memIdNum != ""){
			$sql .= " mem.memIdNum like '%".$memIdNum."%' and";
		}
		if($rcCaseNo != ""){
			$sql .= " rc.rcCaseNo = '".$rcCaseNo."' and";
		}
		if($rcType != ""){
			$sql .= " rc.rcType = '".$rcType."' and";
		}
		if($supNo != ""){
			$sql .= " rc.supNo = '".$supNo."' and";
		}
		if($rcStatus != ""){
			$sql .= " rc.rcStatus = '".$rcStatus."' and";
		}
		$sql .= " 1 = 1";
		if($orCaseNo != ""){
			$sql = "select	 rc.rcNo,rc.rcType,rc.memNo,rc.rcRelateDataNo,rc.rcCaseNo,ord.orCaseNo as CaseNo,rc.rcStatus,mem.memName,mem.memIdNum,mem.memClass,rc.rcIfCredit,rc.aauNoCredit,rc.aauNoAuthen,rc.rcIfAuthen,rc.rcDate,ord.orPeriodTotal as rcPeriodTotal,ord.orPeriodAmnt as rcPeriodAmount from real_cases as rc
					inner join member as mem on rc.memNo = mem.memNo
					inner join orders as ord on rc.rcRelateDataNo = ord.orNo  where
					ord.orCaseNo like '%".$orCaseNo."%'  ";

           if($rcType != ""){
                $sql .= " and rc.rcType = '".$rcType."' ";
            }
			$sql .= "UNION
				select	rc.rcNo,rc.rcType,rc.memNo,rc.rcRelateDataNo,rc.rcCaseNo ,moto.mcoCaseNo as CaseNo,rc.rcStatus,mem.memName,mem.memIdNum,mem.memClass,rc.rcIfCredit,rc.aauNoCredit,rc.aauNoAuthen,rc.rcIfAuthen,rc.rcDate,moto.mcoPeriodTotal as rcPeriodTotal,moto.mcoMinMonthlyTotal as rcPeriodAmount from real_cases as rc
					inner join member as mem on rc.memNo = mem.memNo
					inner join motorbike_cellphone_orders as moto on rc.rcRelateDataNo = moto.mcoNo  where
					moto.mcoCaseNo like '%".$orCaseNo."%'";
		}


		$data = $this->db->selectRecords($sql);
		return $data;
	}

}