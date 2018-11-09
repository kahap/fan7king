	<?php
	
	switch($type){
		case "get_member_data":
		$joinArr = array("app_data"=>"memNo");
		$whereArr = array("app_data`.`adTokenId"=>$_POST["adTokenId"]);
		$api->setWhereArray($whereArr);
		$api->setJoinArray($joinArr);
		$api->getWithWhereAndJoinClause();
		$data = $api->getData();
		if($data != null){
			$api->setInformation($data[0], 1, 1, "OK");
		}else{
			$api->setInformation($data, 0, 0, "No matches found.");
		}
		$api->setResult(false);
		break;
		case "get_member_orders":
		$memNo = getMemberNo();
		$whereArr = array("orMethod"=>1,"memNo"=>$memNo);
		$api->setWhereArray($whereArr);
		$api->setRetrieveArray(array("orNo","pmNo","orStatus","orCaseNo","orDate","orPeriodAmnt","orHandleTransportComp","orHandleTransportSerialNum"));
		$api->getWithWhereAndJoinClause(true);
		$curData = $api->getData();
		$curKey = 0;
		if($curData != null){
			$pm = new Product_Manage();
			$pro = new Product();
			$os = new Other_Setting();
			$osData = $os->getAll();
			$order_status = array('110','7');
			$dataInput = array();
			foreach($curData as $key=>$value){
				if(in_array($value["orStatus"],$order_status)){
					$curTIme = strtotime($value["orDate"])+$osData[0]["orderLimitDays"]*86400;
					if($curTIme >= time()){
						$pmData = $pm->getOnePMByNo($value["pmNo"]);
						$proData = $pro->getOneProByNo($pmData[0]["proNo"]);
						$dataInput[$curKey]["proName"] = $proData[0]["proName"];
						foreach($value as $keyIn=>$valueIn){
							$dataInput[$curKey][$keyIn] = $valueIn;
						}
						$curKey++;
					}
				}else{
					$pmData = $pm->getOnePMByNo($value["pmNo"]);
					$proData = $pro->getOneProByNo($pmData[0]["proNo"]);
					$dataInput[$curKey]["proName"] = $proData[0]["proName"];
					foreach($value as $keyIn=>$valueIn){
						$dataInput[$curKey][$keyIn] = $valueIn;
					}
					$curKey++;
				}
			}
			$api->setInformation($dataInput, 1, count($dataInput), "OK");
		}else{
			$api->setInformation($curData, 0, 0, "No matches found.");
		}
		$api->setResult(false);
		break;
		case "get_member_orders_direct":
		$memNo = getMemberNo();
		$whereArr = array("orMethod"=>0,"memNo"=>$memNo);
		$api->setWhereArray($whereArr);
		$api->setRetrieveArray(array("orNo","orStatus","pmNo","orCaseNo","orDate"));
		$api->getWithWhereAndJoinClause();
		$curData = $api->getData();
		if($curData != null){
			$pm = new Product_Manage();
			$pro = new Product();
			foreach($curData as $key=>$value){
				$pmData = $pm->getOnePMByNo($value["pmNo"]);
				$proData = $pro->getOneProByNo($pmData[0]["proNo"]);
				$curData[$key]["proName"] = $proData[0]["proName"];
			}
			$api->setInformation($curData, 1, count($curData), "OK");
			$api->setResult(false);
		}
		break;
		case "get_member_recomm_apply":
		$memNo = getMemberNo();

		$rbs = new Recomm_Bonus_Apply();
		$allRbsData = $rbs->getRBAByMemNo($memNo);
		if($allRbsData != null){
			$or = new Orders();
			$mem = new Member();
			$rs = new Recomm_Setting();
			$rsData = $rs->getSetting();
			$statusArr = array(0=>"否",1=>"是");
			foreach($allRbsData as $key=>&$value){
				$orData = $or->getOneOrderByNo($value["orNo"]);
				$memData = $mem->getOneMemberByNo($orData[0]["memNo"]);
				$value["orDate"] = $orData[0]["orDate"];
				$value["orCaseNo"] = $orData[0]["orCaseNo"];
				$value["orStatus"] = $orData[0]["orStatus"];
				$value["orHandleTransportComp"] = $orData[0]["orHandleTransportComp"];
				$value["orHandleTransportSerialNum"] = $orData[0]["orHandleTransportSerialNum"];
				$value["memName"] = $memData[0]["memName"];
				$value["rsTotalPerOrder"] = $rsData[0]["rsTotalPerOrder"];
				if($value["rbaExtract"] == 1){
					$value["rbaStatus"] = "否";
				}else{
					$value["rbaStatus"] = $statusArr[$value["rbaStatus"]];
				}
			}
			$api->setInformation($allRbsData, 1, $rbs->db->iNoOfRecords, "OK");
		}else{
			$api->setInformation($allRbsData, 0, 0, "No matches found.");
		}
		$api->setResult(false);
		break;
		case "get_member_recomm_ready":
		$memNo = getMemberNo();
		$rbs = new Recomm_Bonus_Apply();
		$re = new Recomm_Setting();
		$orders = new Orders();
		$re_data = $re->getSetting();
		$allRbsData = $rbs->getRBAByMemNo($memNo);
		if($allRbsData != null){
			foreach($allRbsData as $key=>$value){
				if($value['rbaRecMemNo'] == $memNo && $value['rbaExtract'] != '1'){
					$ord_member = $orders->getOneOrderByNo($value['orNo']);
					if($ord_member[0]['orStatus'] == "10" && strtotime($ord_member[0]['orHandlePaySupDate'])+(86400*$re_data[0]['rsDaysLimit']) < time()){
						$MakeMoney += $re_data[0]['rsTotalPerOrder'];
					}
				}
			}
			$total = $MakeMoney != '' ? $MakeMoney-$re_data[0]['rsCharge'] : 0;
			$api->setInformation($total, 1, 1, "OK");
		}else{
			$api->setInformation($allRbsData, 0, 0, "No matches found.");
		}
		$api->setResult(false);
		break;
		case "get_member_recomm_success":
		$memNo = getMemberNo();
		$api->setRetrieveArray(array("rbsNo","rbsDate","rbsTotal","rbsStatus"));
		$api->setWhereArray(array("memNo"=>$memNo));
		$api->getWithWhereAndJoinClause();
		break;
		case "get_member_recomm_success_each":
		$rbsNo = $which;
		$api->setRetrieveArray(array("rbsNo","rbsDate","rbsTotal","rbsBankAccName","rbsIdNum","rbsBankName","rbsBankBranchName","rbsBankAcc","rbsIdTopImg","rbsIdBotImg","rbsBankBookImg"));
		$api->setWhereArray(array("rbsNo"=>$rbsNo));
		$api->getWithWhereAndJoinClause();
		break;
		case "search":
			$array = array();
			if(!isset($_POST['catNo']) || $_POST['catNo'] == ""){
				$array['catNo'] = '0';
			}else{
				$array['catNo'] = $_POST['catNo'];
			}
			if(!isset($_POST['braNo']) || $_POST['braNo'] == ""){
				$array['braNo'] = '0';
			}else{
				$array['braNo'] = $_POST['braNo'];
			}
			if(!isset($_POST['biNo']) || $_POST['biNo'] == ""){
				$array['biNo'] = '0';
			}else{
				$array['biNo'] = $_POST['biNo'];
			}
			if(!isset($_POST['orderby']) || $_POST['orderby'] == ""){
				$array['orderby'] = '0';
			}else{
				$array['orderby'] = $_POST['orderby'];
			}
			if(!isset($_POST["search"])){
				$array['search'] = '';
			}else{
				$array['search'] = $_POST['search'];
			}
			if(!isset($_POST["limit"])){
				$array['limit'] = '';
			}else{
				$array['limit'] = $_POST['limit'];
			}
			$product_data = $api->getSearchPM($array);
			if($product_data != null){
				$resultArr = array();
				$neededData = array(
					"proNo","pmNo","catNo","braNo","braName","biNo","biName","pmStatus","pmBuyAmnt","proName","proOffer","proGift",
					"pmIfDirect","proImage","pmDirectAmnt","pmPeriodAmnt","proSpec"
				);
				foreach($product_data as $key=>$value){
					foreach($value as $keyIn=>$valueIn){
						if(in_array($keyIn,$neededData)){
							$resultArr[$key][$keyIn] = $valueIn;
						}
					}
				}
				$api->setInformation($resultArr, 1, count($resultArr), "OK");
			}else{
				$api->setInformation($product_data, 0, 0, "No matches found.");
			}
			$api->setResult();
			break;
		case "member_latest_order":
		$memNo = getMemberNo();
		$api->setJoinArray(array("member"=>"memNo"));
		$api->setWhereArray(array("member`.`memNo"=>$memNo));
		$api->getWithWhereAndJoinClause(true);
		$data = $api->getData();
		if($data != null){
			$dataArr = array(0=>$data[0]);
			$api->setInformation($dataArr, 1, 1, "OK");
			$api->setResult(false);
		}
		break;
		case "get_hot_items":
			//$which->最新 精選 限時
			$api->setJoinArray(array("product_manage"=>"proNo","brand"=>"braNo","b_items"=>"biNo"));
			$api->setWhereArray(array("product_manage`.`".$which=>1,"product_manage`.`pmMainSup"=>1,"product_manage`.`pmStatus"=>1));
			$api->setOrArray(array("product_manage`.`pmStatus"=>2));
			$api->setGroupArray(array("product`.`proNo"));
			$api->setLimitArray(isset($_POST['limit'])?$_POST['limit']:'');
			$api->setRetrieveArray($api->getDataFieldName);
			switch (isset($_POST['orderby'])?$_POST['orderby']:'') {
				case 'hot':
					$api->setOrderArray("`product_manage`.`pmHotOrder`");
					$api->getWithWhereAndJoinClause(TRUE);
					break;
				case 'money':
					$api->setOrderArray("`product_manage`.`pmDirectAmnt`");
					$api->getWithWhereAndJoinClause();
					break;
				default:
					$api->setOrderArray("`product_manage`.`pmNewestOrder`");
					$api->getWithWhereAndJoinClause(TRUE);
					break;
			}
			$data = $api->getData();
			break;
		case "get_items_periods":
			//$which->proNo
		$api->setWhereArray(array("proNo"=>$which));
		$api->getWithWhereAndJoinClause();
		if($api->getData() == null){
			$ps = new Period_Setting();
			$psData = $ps->getAllPS();
			$dataSendBack = array();
			foreach($psData as $curKey=>$curValue){
				$dataSendBack[$curKey]["ppPeriodAmount"] = $curValue["psMonthNum"];
				$dataSendBack[$curKey]["ppPercent"] = $curValue["psRatio"];
			}
			$api->setInformation($dataSendBack, 1, count($psData), "OK");
			$api->setResult(false);
		}
		break;
		case "get_item_by_orno":
			//$which->orNo
		$api->setJoinArray(array("product_manage"=>"pmNo"));
		$api->setWhereArray(array("orders`.`orNo"=>$which));
		$api->getWithWhereAndJoinClause();
		$curData = $api->getData();
		$proNo = $curData[0]["proNo"];
		$pro = new Product();
		$proData = $pro->getOneProByNo($proNo);
		if($proData != null){
			$dataArr = array();
			foreach($proData as $curKey=>$curValue){
				$dataArr[$curKey]["proNo"] = $curValue["proNo"];
				$dataArr[$curKey]["proName"] = $curValue["proName"];
			}
			$api->setInformation($dataArr, 1, 1, "OK");
			$api->setResult(false);
		}
		break;
		case "get_category_order":
		$api->setWhereArray(array("catIfDisplay"=>1));
		$api->setRetrieveArray(array("catNo","catName","catImage","catIcon"));
		$api->setOrderArray("catOrder");
		$api->getWithWhereAndJoinClause();
		$data = $api->getData();
		$return = array();
		if($data != null){
			foreach($data as $key=>&$value){
				$apiPro = new API("product");
				$apiPro->getAllCatPM($value["catNo"]);
				$proData = $apiPro->getData();
				$data[$key]["proImage"] = $proData[0]["proImage"][0];
			}
			$api->setInformation($data, 1, count($data), "OK");
		}else{
			$api->setInformation($data, 0, 0, "No matches found.");
		}
		$api->setResult(false);
		break;
		case "get_category_items_hotorder":
			//$which->catNo
		$api->setJoinArray(array("product_manage"=>"proNo"));
		$api->setWhereArray(array("product`.`catNo"=>$which,"product_manage`.`pmMainSup"=>1,"product_manage`.`pmStatus"=>1));
		$api->setOrArray(array("product_manage`.`pmStatus"=>2));
		$api->setRetrieveArray(array("product.proNo","product_manage.pmNo","pmUpDate","proName","pmIfDirect","proImage","proSpec","pmDirectAmnt","pmPeriodAmnt","pmPopular","pmBuyAmnt","pmStatus"));
		$api->setOrderArray("product_manage.pmUpDate");
		$api->getWithWhereAndJoinClause(true);
		break;
		case "get_brand_order_by_category":
			//$which->catNo
		$api->setJoinArray(array("product"=>"braNo"));
		$api->setWhereArray(array("braIfDisplay"=>1,"catNo"=>$which));
		$api->setRetrieveArray(array("brand.braNo","braName"));
		$api->setGroupArray(array("brand`.`braNo"));
		$api->setOrderArray("braOrder");
		$api->getWithWhereAndJoinClause();
		break;
		case "get_brand_items_by_category":
			//$which->catNo_braNo
		$whichArr = explode("_",$which);
		$api->setJoinArray(array("product_manage"=>"proNo"));
		$api->setWhereArray(array("product`.`catNo"=>$whichArr[0],"product`.`braNo"=>$whichArr[1],"product_manage`.`pmMainSup"=>1,'pmStatus'=>1));
		$api->setOrArray(array('pmStatus'=>2));
		$api->setRetrieveArray(array("product.proNo","product_manage.pmNo","proName","pmIfDirect","proImage","proSpec","pmDirectAmnt","pmPeriodAmnt","pmBuyAmnt","pmStatus"));
		$api->getWithWhereAndJoinClause();
		break;
		case "get_product_details":
			//$which->pmNo
			//!!!!!!!之後會加上廣告字!!!!!!!!!!
		$api->setJoinArray(array("product_manage"=>"proNo"));
		$api->setWhereArray(array("product_manage`.`pmNo"=>$which));
		$api->setRetrieveArray(array("product.proNo","product_manage.pmNo","proName","pmIfDirect","proImage","proSpec","pmDirectAmnt","pmPeriodAmnt","pmBuyAmnt"));
		$api->getWithWhereAndJoinClause();
		$data = $api->getData();
		if($data != null){
			$api->setInformation($data[0], 1, 1, "OK");
			$api->setResult(false);
		}
		break;
		case "get_hot_keys":
		$api->setWhereArray(array("hkEnable"=>1));
		$api->setRetrieveArray(array("hkKey"));
		$api->getWithWhereAndJoinClause();
		break;
		case "all_ques":
		$apiQa = new API("qa_app");
		$apiQa->setWhereArray(array("qaaIfShow"=>1));
		$apiQa->setOrderArray("qaaNo");
		$apiQa->setRetrieveArray(array("qaaNo","qaaQues","qaaDate"));
		$apiQa->getWithWhereAndJoinClause();
		$data = $apiQa->getData();
		$dataOutput = array();
		foreach($data as $key=>$value){
			$dataOutput[$key]["qaNo"] = $value["qaaNo"];
			$dataOutput[$key]["qaQues"] = $value["qaaQues"];
			$dataOutput[$key]["qaDate"] = $value["qaaDate"];
		}
		$api->setInformation($dataOutput, 1, 1, "OK");
		$api->setResult(false);
		break;
		case "recomm_people_list":
		$memNo = getMemberNo();
		$mem = new Member();
		$memData = $mem->getRecommTotalForMember($memNo);
		if($memData != null){
			$api->setInformation($memData, 1, count($memData), "OK");
		}else{
			$api->setInformation(null, 0, 0, "No matches found.");
		}
		$api->setResult(false);
		break;
		case "get_school_major":
		$api->setWhereArray(array("schNo"=>$which));
		$api->setRetrieveArray(array("majNo","majName"));
		$api->getWithWhereAndJoinClause();
		break;
		case "check_if_registered":
		$api->setWhereArray(array("memFBtoken"=>$which));
		$api->getWithWhereAndJoinClause();
		$data = $api->getData();
		if($data != null){
			if(empty($data[0]["memClass"])){
				$api->setInformation(1, 1, 1, "OK");
			}else{
				$api->setInformation(2, 1, 1, "OK");
			}
		}else{
			$api->setInformation(0, 1, 0, "OK");
		}
		$api->setResult(false);
		break;
		case "all_activate_images":
		$api->setWhereArray(array("adIfShow"=>1,"adArea"=>0));
		$api->setOrderArray("adOrder");
		$api->setRetrieveArray(array("adNo","adImage","adLink"));
		$api->getWithWhereAndJoinClause();
		break;
		case "single_order_detail":
		$dba = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
		$sql = "SELECT * FROM `inform_center` where orNo ='".$which."' ORDER BY `icDate` DESC";
		$inData = $dba->selectRecords($sql);
		if($inData['0']['icType'] == "" or $inData['0']['icType'] == "0"){
			$api->setJoinArray(array("member"=>"memNo","product_manage"=>"pmNo"));
			$api->setWhereArray(array("orders`.`orNo"=>$which));
			$api->setRetrieveArray(array("orNo",
				"orders.memNo",
				"orders.pmNo",
				/*訂單資訊*/
				"orDate",
				"orStatus",
				"orIfEditable",
				"orIfSecret",
				"orProSpec",
				"orPeriodAmnt",
				"orPeriodTotal",
				"orBusinessNumNumber",
				"orBusinessNumTitle",
				"orHandleTransportComp",
				"orHandleTransportSerialNum",
				/*會員資料*/
				"orders.memClass",
				"memSchool",
				"memCompanyName",
				"memSalary",
				"memYearWorked",
				"memAccount",
				"memSubEmail",
				"memEmailAuthen",
				"memName",
				"memGender",
				"memBday",
				"memIdNum",
				"memPostCode",
				"memAddr",
				"memPhone",
				"memCell",
				"memLineId",
				"memRegistDate",
				"memRegistMethod",
				/*訂單其他資料*/
				"orAppApplierBirthAddrPostCode",
				"orAppApplierBirthPhone",
				"orAppApplierBirthAddr",
				"orAppApplierLivingOwnership",
				"orAppApplierCompanyName",
				"orAppApplierYearExperience",
				"orAppApplierMonthSalary",
				"orAppApplierCompanyPhone",
				"orAppApplierCompanyPhoneExt",
				"orAppApplierCreditNum",
				"orAppApplierCreditIssueBank",
				"orAppApplierCreditDueDate",
				"orAppApplierCreditSecurityNum",
				"orDocProvideReason",
				"orDocProvideComment",
				"orAppApplierCompanystatus",
				"orIdIssueYear",
				"orIdIssueMonth",
				"orIdIssueDay",
				"orIdIssuePlace",
				"orIdIssueType",
				/*收穫人資料*/
				"orReceiveName",
				"orReceiveAddr",
				"orReceivePhone",
				"orReceiveCell",
				"orReceiveComment",
				/*聯絡人資料*/
				"orAppContactRelaName",
				"orAppContactRelaRelation",
				"orAppContactRelaPhone",
				"orAppContactRelaCell",
				"orAppContactFrdName",
				"orAppContactFrdRelation",
				"orAppContactFrdPhone",
				"orAppContactFrdCell",
				/*備註*/
				"orAppExtraAvailTime",
				"orAppExtraInfo",
				/*證件上傳*/
				"orAppAuthenIdImgTop",
				"orAppAuthenIdImgBot",
				"orAppAuthenStudentIdImgTop",
				"orAppAuthenStudentIdImgBot",
				"orAppAuthenExtraInfo"
			));
			$api->getWithWhereAndJoinClause();
			$data = $api->getData();
			$mem = new Member();
			$pm = new Product_Manage();
			$pro = new Product();
			$pmNo = $data[0]["pmNo"];
			$pmData = $pm->getOnePMByNo($pmNo);
			$proData = $pro->getOneProByNo($pmData[0]["proNo"]);
			$memData = $mem->getOneMemberByNo($data[0]["memNo"]);
			if($data[0]['orStatus'] != '701' && $data[0]['orStatus'] != '7' && $data[0]['orStatus'] != '4'){
				// 客製資料
				$schoolArr = $data[0]["memSchool"];
				$apiSch = new API("school");
				$apiSch->setWhereArray(array("schName"=>$schoolArr[0]));
				$apiSch->getWithWhereAndJoinClause();
				$schData = $apiSch->getData();
				$schoolKey = $schData[0]["schNo"];
				$data[0]["memSchool"][0] = $schoolKey;
				$dateStrArr = explode("-",date("Y-m-d",strtotime($data[0]["orDate"])));
				$dateStr = "";
				foreach($dateStrArr as $key=>$value){
					if($key == "0"){
						$dateStr .= ($value - 1911)."年";
					}else if($key == "1"){
						$dateStr .= $value."月";
					}else{
						$dateStr .= $value."日";
					}
				}
				$data[0]["orDate"] = $dateStr;
				if(empty($data[0]["memCompanyName"])){
					$data[0]["company"] = $data[0]["orAppApplierCompanyName"];
					$data[0]["salary"] = $data[0]["orAppApplierMonthSalary"];
					$data[0]["yearWorked"] = $data[0]["orAppApplierYearExperience"];
				}else{
					$data[0]["company"] = $data[0]["memCompanyName"];
					$data[0]["salary"] = $data[0]["memSalary"];
					$data[0]["yearWorked"] = $data[0]["memYearWorked"];
				}
				if($data[0]["orDocProvideReason"] != "" && $data[0]["orDocProvideReason"] != "無"){
					if(is_numeric($data[0]["orDocProvideReason"]) && $data[0]["orDocProvideReason"] != "1"){
						$or = new Orders();
						$data[0]["orDocProvideReason"] = $or->reasonArr[$data[0]["orDocProvideReason"]];
					}else{
						if($data[0]["orDocProvideReason"] == "自訂" || $data[0]["orDocProvideReason"] == "1"){
							$data[0]["orDocProvideReason"] = $data[0]["orDocProvideComment"];
						}
					}
				}else{
					$data[0]["orDocProvideReason"] = "";
				}
				$data[0]["proName"] = $proData[0]["proName"];
				$data[0]["paymentPerMonth"] = ceil($data[0]["orPeriodTotal"]/$data[0]["orPeriodAmnt"])."";
				if(!empty($memData[0]["memRecommCode"])){
					$data[0]["ifHasRecomm"] = "是";
				}else{
					$data[0]["ifHasRecomm"] = "否";
				}
				if($data != null){
					$api->setInformation($data[0], 1, 1, "OK");
				}else{
					$api->setInformation($data, 0, 0, "No matches found.");
				}
				$api->setResult(false);
			}
		}else{
			$sql1 = "SELECT rcType,rcRelateDataNo FROM `real_cases` where rcNo ='".$inData[0]['orNo']."'";
			$rcData = $dba->selectRecords($sql1);
			$api->setJoinArray(array("member"=>"memNo"));
			$api->setWhereArray(array("motorbike_cellphone_orders`.`mcoNo"=>$rcData['0']['rcRelateDataNo']));
			$api->setRetrieveArray(array("mcoNo",
				"motorbike_cellphone_orders.memNo",
				/*訂單資訊*/
				"mcoDate",
				"mcoStatus",
				"mcoIfEditable",
				"mcoIfSecret",
				"mcoPeriodAmount",
				"mcoPeriodTotal",
				"mcoTotalShouldPay",
				"mcoCellBrand",
				"mcoMotorBrand",
				"mcoCcNumber",
				"mcoCellphoneSpec",
				"mcoMotorbikeSpec",
				"mcoCarNum",
				"mcoYear",
				"mcoMinMonthlyTotal",
				"mcoMaxMonthlyTotal",
				"mcoDailyInterest",
				/*會員資料*/
				"motorbike_cellphone_orders.memClass",
				"memSchool",
				"memCompanyName",
				"memSalary",
				"memYearWorked",
				"memAccount",
				"memSubEmail",
				"memEmailAuthen",
				"memName",
				"memGender",
				"memBday",
				"memIdNum",
				"memPostCode",
				"memAddr",
				"memPhone",
				"memCell",
				"memLineId",
				"memRegistDate",
				"memRegistMethod",
				/*訂單其他資料*/
				"mcoBirthPostCode",
				"mcoBirthPhone",
				"mcoBirthAddr",
				"mcoLivingOwnership",
				"mcoCompanyStatus",
				"mcoCompanyPhone",
				"mcoCompanyPhoneExt",
				"mcoCreditNum",
				"mcoCreditIssueBank",
				"mcoCreditDueDate",
				"mcoCreditSecurityNum",
				"mcoDocProvideReason",
				"mcoDocProvideComment",
				"mcoApplyPurpose",
				"mcoIdIssueYear",
				"mcoIdIssueMonth",
				"mcoIdIssueDay",
				"mcoIdIssuePlace",
				"mcoIdIssueType",
				"mcoAvailTime",
				"mcoExtraInfo",
				/*聯絡人資料*/
				"mcoContactName",
				"mcoContactRelation",
				"mcoContactPhone",
				"mcoContactCell",
				/*證件上傳*/
				"mcoIdImgTop",
				"mcoIdImgBot",
				"mcoStudentIdImgTop",
				"mcoStudentIdImgBot",
				"mcoSubIdImgTop",
				"mcoCarIdImgTop",
				"mcoBankBookImgTop",
				"mcoRecentTransactionImgTop",
				"mcoExtraInfoUpload"
			));
			$api->getWithWhereAndJoinClause();
			$data = $api->getData();
			$mem = new Member();
			$memData = $mem->getOneMemberByNo($data[0]["memNo"]);
			// 客製資料
			$schoolArr = $data[0]["memSchool"];
			$apiSch = new API("school");
			$apiSch->setWhereArray(array("schName"=>$schoolArr[0]));
			$apiSch->getWithWhereAndJoinClause();
			$schData = $apiSch->getData();
			$schoolKey = $schData[0]["schNo"];
			$data[0]["memSchool"][0] = $schoolKey;
			$dateStrArr = explode("-",date("Y-m-d",strtotime($data[0]["orDate"])));
			$dateStr = "";
			foreach($dateStrArr as $key=>$value){
				if($key == "0"){
					$dateStr .= ($value - 1911)."年";
				}else if($key == "1"){
					$dateStr .= $value."月";
				}else{
					$dateStr .= $value."日";
				}
			}
			$data[0]["mcoDate"] = $dateStr;
			$data[0]["company"] = $data[0]["memCompanyName"];
			$data[0]["salary"] = $data[0]["memSalary"];
			$data[0]["yearWorked"] = $data[0]["memYearWorked"];
			$data[0]["orIfEditable"] = "1";
			if($data[0]["mcoDocProvideReason"] == "1"){
				$data[0]["mcoDocProvideReason"] = $data[0]["mcoDocProvideComment"];
			}else if($data[0]["mcoDocProvideReason"] == "0"){
				$data[0]["mcoDocProvideReason"] = "無";
			}else{
				$or = new Orders();
				$data[0]["mcoDocProvideReason"] = $or->reasonArr[$data[0]["mcoDocProvideReason"]];
			}
			if(!empty($memData[0]["memRecommCode"])){
				$data[0]["ifHasRecomm"] = "是";
			}else{
				$data[0]["ifHasRecomm"] = "否";
			}
			$data[0]["paymentPerMonth"] = ceil($data[0]["mcoTotalShouldPay"]/$data[0]["mcoPeriodAmount"])."";
			if($data != null){
				$api->setInformation($data[0], 1, 1, "OK");
			}else{
				$api->setInformation($data, 0, 0, "No matches found.");
			}
			$api->setResult(false);
		}
		break;
		case "member_last_order":
		$memNo = getMemberNo();
			// $memNo = $which;
		$api->setJoinArray(array("member"=>"memNo","product_manage"=>"pmNo"));
		$api->setWhereArray(array("member`.`memNo"=>$memNo));
		$api->setRetrieveArray(array("orNo",
			"orders.memNo",
			"orders.pmNo",
			/*訂單資訊*/
			"orDate",
			"orStatus",
			"orIfEditable",
			"orIfSecret",
			"orProSpec",
			"orPeriodAmnt",
			"orPeriodTotal",
			"orBusinessNumNumber",
			"orBusinessNumTitle",
			"orHandleTransportComp",
			"orHandleTransportSerialNum",
			/*會員資料*/
			"member.memClass",
			"memSchool",
			"memCompanyName",
			"memSalary",
			"memYearWorked",
			"memAccount",
			"memSubEmail",
			"memEmailAuthen",
			"memName",
			"memGender",
			"memBday",
			"memIdNum",
			"memPostCode",
			"memAddr",
			"memPhone",
			"memCell",
			"memLineId",
			"memRegistDate",
			"memRegistMethod",
			/*訂單其他資料*/
			"orAppApplierBirthAddrPostCode",
			"orAppApplierBirthPhone",
			"orAppApplierBirthAddr",
			"orAppApplierLivingOwnership",
			"orAppApplierCompanyName",
			"orAppApplierYearExperience",
			"orAppApplierMonthSalary",
			"orAppApplierCompanyPhone",
			"orAppApplierCompanyPhoneExt",
			"orAppApplierCreditNum",
			"orAppApplierCreditIssueBank",
			"orAppApplierCreditDueDate",
			"orAppApplierCreditSecurityNum",
			"orAppApplierCompanystatus",
			"orIdIssueYear",
			"orIdIssueMonth",
			"orIdIssueDay",
			"orIdIssuePlace",
			"orIdIssueType",
			/*收穫人資料*/
			"orReceiveName",
			"orReceiveAddr",
			"orReceivePhone",
			"orReceiveCell",
			"orReceiveComment",
			/*聯絡人資料*/
			"orAppContactRelaName",
			"orAppContactRelaRelation",
			"orAppContactRelaPhone",
			"orAppContactRelaCell",
			"orAppContactFrdName",
			"orAppContactFrdRelation",
			"orAppContactFrdPhone",
			"orAppContactFrdCell",
			/*備註*/
			"orAppExtraAvailTime",
			"orAppExtraInfo",
			/*證件上傳*/
			"orAppAuthenIdImgTop",
			"orAppAuthenIdImgBot",
			"orAppAuthenStudentIdImgTop",
			"orAppAuthenStudentIdImgBot",
			"orAppAuthenExtraInfo"
		));
		$api->getWithWhereAndJoinClause(true);
		$data = $api->getData();
		if($data != null){
			$schoolArr = $data[0]["memSchool"];
			$apiSch = new API("school");
			$apiSch->setWhereArray(array("schName"=>$schoolArr[0]));
			$apiSch->getWithWhereAndJoinClause();
			$schData = $apiSch->getData();
			$schoolKey = $schData[0]["schNo"];
			$data[0]["memSchool"][0] = $schoolKey;
			$dateStrArr = explode("-",date("Y-m-d",strtotime($data[0]["orDate"])));
			$dateStr = "";
			foreach($dateStrArr as $key=>$value){
				if($key == "0"){
					$dateStr .= ($value - 1911)."年";
				}else if($key == "1"){
					$dateStr .= $value."月";
				}else{
					$dateStr .= $value."日";
				}
			}
			$data[0]["orDate"] = $dateStr;
			$data[0]["hasLastOrder"] = true;
			if(empty($data[0]["memCompanyName"])){
				$data[0]["company"] = $data[0]["orAppApplierCompanyName"];
				$data[0]["salary"] = $data[0]["orAppApplierMonthSalary"];
				$data[0]["yearWorked"] = $data[0]["orAppApplierYearExperience"];
			}else{
				$data[0]["company"] = $data[0]["memCompanyName"];
				$data[0]["salary"] = $data[0]["memSalary"];
				$data[0]["yearWorked"] = $data[0]["memYearWorked"];
			}
			$api->setInformation($data[0], 1, 1, "OK");
		}else{
			$apiMem = new API("member");
			$apiMem->setWhereArray(array("memNo"=>$memNo));
			$apiMem->setRetrieveArray(array(
				/*會員資料*/
				"memNo",
				"memClass",
				"memSchool",
				"memCompanyName",
				"memSalary",
				"memYearWorked",
				"memAccount",
				"memSubEmail",
				"memEmailAuthen",
				"memName",
				"memGender",
				"memBday",
				"memIdNum",
				"memAddr",
				"memPhone",
				"memCell",
				"memLineId",
				"memRegistDate",
				"memRegistMethod"
			));
			$apiMem->getWithWhereAndJoinClause();
			$dataMem = $apiMem->getData();
			$dataMem[0]["company"] = $dataMem[0]["memCompanyName"];
			$dataMem[0]["salary"] = $dataMem[0]["memSalary"];
			$dataMem[0]["yearWorked"] = $dataMem[0]["memYearWorked"];
			$dataMem[0]["hasLastOrder"] = false;
			$api->setInformation($dataMem[0], 1, 1, "OK");
		}
		$api->setResult(false);
		break;
		case "get_payment_orders":
		$memNo = getMemberNo();
		$api->setWhereArray(array("memNo"=>$memNo,"orMethod"=>"1"));
		$api->setRetrieveArray(array("orNo","memNo","pmNo","orCaseNo","orInternalCaseNo","orDate","orPeriodAmnt"));
		$api->getWithWhereAndJoinClause();
		$data = $api->getData();
		$resultData = array();
		if($data != null){
			$pm = new Product_Manage();
			$pro = new Product();
			$curIndex = 0;
			foreach($data as $key=>$value){
					//檢查是否在新系統有資料
				$rc = new API("real_cases");
				$rc->setWhereArray(array("rcRelateDataNo"=>$value["orNo"],"rcType"=>"0"));
				$rc->getWithWhereAndJoinClause();
				$rcData = $rc->getData();
				if($rcData != null){
						//查看是否有本息表
					$tpi = new API("tpi");
					$tpi->setWhereArray(array("rcNo"=>$rcData[0]["rcNo"]));
					$tpi->getWithWhereAndJoinClause();
					$tpiData = $tpi->getData();
					if($tpiData != null){
						foreach($value as $keyIn=>$valueIn){
							$resultData[$curIndex][$keyIn] = $valueIn;
						}
						$pmData = $pm->getOnePMByNo($value["pmNo"]);
						$proData = $pro->getOneProByNo($pmData[0]["proNo"]);
						$resultData[$curIndex]["proName"] = $proData[0]["proName"];
							// 當期期數
						$curPeriod = 0;
						$shouldPayDate = "";
						$shouldPayAmount = 0;
						$actualPayDate = "";
						$overDue = 0;
						foreach($tpiData as $whichPeriod=>$content){
							if(empty($content["tpiActualDate"])){
								$curPeriod = $content["tpiPeriod"];
								$shouldPayDate = date("Y-m-d",strtotime($content["tpiSupposeDate"]));
								$shouldPayAmount = $content["tpiPeriodTotal"];
								$actualPayDate = $content["tpiActualDate"];
								$due = ceil((time() - strtotime($content["tpiSupposeDate"]))/86400);
								if($due >= 0){
									$overDue = $due;
								}
								break;
							}
						}
						$resultData[$curIndex]["curPeriod"] = $curPeriod;
						$resultData[$curIndex]["shouldPayDate"] = $shouldPayDate;
						$resultData[$curIndex]["shouldPayAmount"] = $shouldPayAmount;
						$resultData[$curIndex]["actualPayDate"] = $actualPayDate;
						$resultData[$curIndex]["overDue"] = $overDue;
						$curIndex++;
					}
				}else{
						// 是否有案件編號
					if(!empty($value["orInternalCaseNo"])){
						$apiData = json_decode(getPaymenyAPI("TPI",$value["orInternalCaseNo"],""));
							// 原彰API是否有資料
						if(!empty(array_filter($apiData))){
							foreach($value as $keyIn=>$valueIn){
								$resultData[$curIndex][$keyIn] = $valueIn;
							}
							$pmData = $pm->getOnePMByNo($value["pmNo"]);
							$proData = $pro->getOneProByNo($pmData[0]["proNo"]);
							$resultData[$curIndex]["proName"] = $proData[0]["proName"];
								// 當期期數
							$curPeriod = 0;
							$shouldPayDate = "";
							$shouldPayAmount = 0;
							$actualPayDate = "";
							$overDue = 0;
							foreach($apiData as $whichPeriod=>$contentObj){
								$content = get_object_vars($contentObj);
								if(empty($content["實際繳款日"])){
									$curPeriod = $content["期數"];
									$shouldPayDate = date("Y-m-d",strtotime($content["應繳款日"]));
									$shouldPayAmount = $content["應繳金額"];
									$actualPayDate = $content["實際繳款日"];
									$due = ceil((time() - strtotime($content["應繳款日"]))/86400);
									if($due >= 0){
										$overDue = $due;
									}
									break;
								}
							}
							$resultData[$curIndex]["curPeriod"] = $curPeriod;
							$resultData[$curIndex]["shouldPayDate"] = $shouldPayDate;
							$resultData[$curIndex]["shouldPayAmount"] = $shouldPayAmount;
							$resultData[$curIndex]["actualPayDate"] = $actualPayDate;
							$resultData[$curIndex]["overDue"] = $overDue;
							$curIndex++;
						}
					}
				}
			}
			if(!empty(array_filter($resultData))){
				$api->setInformation($resultData, 1, count($resultData), "OK");
			}else{
				$api->setInformation($resultData, 0, 0, "No matches found.");
			}
		}else{
			$api->setInformation($data, 0, 0, "No matches found.");
		}
		$api->setResult(false);
		break;
		case "get_barcode":
		$orNo = $which;
		$api->setWhereArray(array("orNo"=>$which));
		$api->getWithWhereAndJoinClause();
		$data = $api->getData();
			//檢查是否在新系統有資料
		$rc = new API("real_cases");
		$rc->setWhereArray(array("rcRelateDataNo"=>$orNo,"rcType"=>"0"));
		$rc->getWithWhereAndJoinClause();
		$rcData = $rc->getData();
		if($rcData != null){
				//查看是否有本息表
			$tpi = new API("tpi");
			$tpi->setWhereArray(array("rcNo"=>$rcData[0]["rcNo"]));
			$tpi->getWithWhereAndJoinClause();
			$tpiData = $tpi->getData();
			if($tpiData != null){
				$outputData = array();
					// 當期期數
				$curPeriod = 0;
				$shouldPayAmount = 0;
				$status = "";
				foreach($tpiData as $whichPeriod=>$content){
					if(empty($content["tpiActualDate"])){
						$curPeriod = $content["tpiPeriod"];
						$shouldPayAmount = $content["tpiPeriodTotal"];
						if(empty($content["tpiActualDate"])){
							$status = "0";
						}else{
							$status = "1";
						}
						$bar = new API("barcode");
						$bar->setWhereArray(array("tpiNo"=>$content["tpiNo"]));
						$bar->getWithWhereAndJoinClause();
						$barData = $bar->getData();
						foreach($barData as $barKey=>$barVal){
							if($barVal["barIndex"] == "1"){
								$outputData["first"] = $barVal["barBarcode"];
							}else if($barVal["barIndex"] == "2"){
								$outputData["second"] = $barVal["barBarcode"];
							}else if($barVal["barIndex"] == "3"){
								$outputData["third"] = $barVal["barBarcode"];
							}
						}
						break;
					}
				}
				$outputData["shouldPayAmount"] = $shouldPayAmount;
				$outputData["status"] = $status;

				$api->setInformation($outputData, 1, 1, "OK");
			}else{
				$api->setInformation(null, 0, 0, "No matches found.");
			}
		}else{
			$orInternalCaseNo = $data[0]["orInternalCaseNo"];
			$apiData = json_decode(getPaymenyAPI("TPI",$orInternalCaseNo,""));
				// 原彰API是否有資料
			if(!empty(array_filter($apiData))){
				$outputData = array();
					// 當期期數
				$curPeriod = 0;
				$shouldPayAmount = 0;
				$status = "";
				foreach($apiData as $whichPeriod=>$contentObj){
					$content = get_object_vars($contentObj);
					if(empty($content["實際繳款日"])){
						$curPeriod = $content["期數"];
						$shouldPayAmount = $content["應繳金額"];
						switch($content["狀態"]){
							case "未繳款":
							$status = "0";
							break;
							case "已繳款":
							$status = "1";
							break;
							default:
							$status = "2";
							break;
						}
						break;
					}
				}
				$outputData["shouldPayAmount"] = $shouldPayAmount;
				$outputData["status"] = $status;
				$barcode = json_decode(getPaymenyAPI("BarCode",$orInternalCaseNo,$curPeriod));
				$each = get_object_vars($barcode);
				$outputData["first"] = $each["第一段條碼"];
				$outputData["second"] = $each["第二段條碼"];
				$outputData["third"] = $each["第三段條碼"];

				$api->setInformation($outputData, 1, 1, "OK");
			}else{
				$api->setInformation(null, 0, 0, "No matches found.");
			}
		}
		$api->setResult(false);
		break;
		case "get_each_period_payment":
		$orNo = $which;
		$api->setWhereArray(array("orNo"=>$which));
		$api->getWithWhereAndJoinClause();
		$data = $api->getData();

			//檢查是否在新系統有資料
		$rc = new API("real_cases");
		$rc->setWhereArray(array("rcRelateDataNo"=>$orNo,"rcType"=>"0"));
		$rc->getWithWhereAndJoinClause();
		$rcData = $rc->getData();
		if($rcData != null){
				//查看是否有本息表
			$tpi = new API("tpi");
			$tpi->setWhereArray(array("rcNo"=>$rcData[0]["rcNo"]));
			$tpi->getWithWhereAndJoinClause();
			$tpiData = $tpi->getData();
			if($tpiData != null){
				$outputData = array();
					// 當期期數
				foreach($tpiData as $whichPeriod=>$content){
					$outputData["data"][$whichPeriod]["period"] = $content["tpiPeriod"];
					$outputData["data"][$whichPeriod]["shouldPayDate"] = $content["tpiSupposeDate"];
					$outputData["data"][$whichPeriod]["shouldPayAmount"] = $content["tpiPeriodTotal"];
					$outputData["data"][$whichPeriod]["actualPayDate"] = $content["tpiActualDate"];
					if(empty($content["tpiActualDate"])){
						$due = 0;
					}else{
						$due = ceil((strtotime($content["tpiActualDate"]) - strtotime($content["tpiSupposeDate"]))/86400);
						if($due < 0){
							$due = 0;
						}
					}
					$outputData["data"][$whichPeriod]["overdue"] = $due;
					if(empty($content["tpiActualDate"])){
						$outputData["data"][$whichPeriod]["status"] = "0";
					}else{
						$outputData["data"][$whichPeriod]["status"] = "1";
					}
				}
					//計算到幾期
				$curPeriod = 0;
				foreach($tpiData as $whichPeriod=>$content){
					if(empty($content["tpiActualDate"])){
						$curPeriod = $content["tpiPeriod"]-1;
						break;
					}
				}
				$outputData["curPeriod"] = $curPeriod;

				$api->setInformation($outputData, 1, count($outputData), "OK");
			}else{
				$api->setInformation(array_filter($apiData), 0, 0, "No matches found.");
			}
		}else{
			$orInternalCaseNo = $data[0]["orInternalCaseNo"];
			$apiData = json_decode(getPaymenyAPI("TPI",$orInternalCaseNo,""));
				// 原彰API是否有資料
			if(!empty(array_filter($apiData))){
				$outputData = array();
					// 當期期數
				foreach($apiData as $whichPeriod=>$contentObj){
					$content = get_object_vars($contentObj);
					$outputData["data"][$whichPeriod]["period"] = $content["期數"];
					$outputData["data"][$whichPeriod]["shouldPayDate"] = $content["應繳款日"];
					$outputData["data"][$whichPeriod]["shouldPayAmount"] = $content["應繳金額"];
					$outputData["data"][$whichPeriod]["actualPayDate"] = $content["實際繳款日"];
					if(empty($content["實際繳款日"])){
						$due = 0;
					}else{
						$due = ceil((strtotime($content["實際繳款日"]) - strtotime($content["應繳款日"]))/86400);
						if($due < 0){
							$due = 0;
						}
					}
					$outputData["data"][$whichPeriod]["overdue"] = $due;
					switch($content["狀態"]){
						case "未繳款":
						$outputData["data"][$whichPeriod]["status"] = "0";
						break;
						case "已繳款":
						$outputData["data"][$whichPeriod]["status"] = "1";
						break;
						default:
						$outputData["data"][$whichPeriod]["status"] = "2";
						break;
					}
				}
					//計算到幾期
				$curPeriod = 0;
				foreach($apiData as $whichPeriod=>$contentObj){
					$content = get_object_vars($contentObj);
					if(empty($content["實際繳款日"])){
						$curPeriod = $content["期數"]-1;
						break;
					}
				}
				$outputData["curPeriod"] = $curPeriod;
				$api->setInformation($outputData, 1, count($outputData), "OK");
			}else{
				$api->setInformation(array_filter($apiData), 0, 0, "No matches found.");
			}
		}
		$api->setResult(false);
		break;
		case "get_barcode_by_period":
		$orNo = $_POST["orNo"];
		$curPeriod = $_POST["period"];
		$api->setWhereArray(array("orNo"=>$orNo));
		$api->getWithWhereAndJoinClause();
		$data = $api->getData();

			//檢查是否在新系統有資料
		$rc = new API("real_cases");
		$rc->setWhereArray(array("rcRelateDataNo"=>$orNo,"rcType"=>"0"));
		$rc->getWithWhereAndJoinClause();
		$rcData = $rc->getData();
		if($rcData != null){
				//查看是否有本息表
			$tpi = new API("tpi");
			$tpi->setWhereArray(array("rcNo"=>$rcData[0]["rcNo"]));
			$tpi->getWithWhereAndJoinClause();
			$tpiData = $tpi->getData();
			if($tpiData != null){
				$outputData = array();
				foreach($tpiData as $whichPeriod=>$content){
					if($content["tpiPeriod"] == $curPeriod){
						$shouldPayAmount = $content["tpiPeriodTotal"];
						if(empty($content["tpiActualDate"])){
							$status = "0";
						}else{
							$status = "1";
						}
						$bar = new API("barcode");
						$bar->setWhereArray(array("tpiNo"=>$content["tpiNo"]));
						$bar->getWithWhereAndJoinClause();
						$barData = $bar->getData();
						foreach($barData as $barKey=>$barVal){
							if($barVal["barIndex"] == "1"){
								$outputData["first"] = $barVal["barBarcode"];
							}else if($barVal["barIndex"] == "2"){
								$outputData["second"] = $barVal["barBarcode"];
							}else if($barVal["barIndex"] == "3"){
								$outputData["third"] = $barVal["barBarcode"];
							}
						}
						break;
					}
				}
				$outputData["shouldPayAmount"] = $shouldPayAmount;
				$outputData["status"] = $status;

				$api->setInformation($outputData, 1, 1, "OK");
			}else{
				$api->setInformation(array_filter($apiData), 0, 0, "No matches found.");
			}
		}else{
			$orInternalCaseNo = $data[0]["orInternalCaseNo"];
			$apiData = json_decode(getPaymenyAPI("TPI",$orInternalCaseNo,""));
				// 原彰API是否有資料
			if(!empty(array_filter($apiData))){
				foreach($apiData as $whichPeriod=>$contentObj){
					$content = get_object_vars($contentObj);
					if($content["期數"] == $curPeriod){
						$shouldPayAmount = $content["應繳金額"];
						switch($content["狀態"]){
							case "未繳款":
							$status = "0";
							break;
							case "已繳款":
							$status = "1";
							break;
							default:
							$status = "2";
							break;
						}
						break;
					}
				}
				$outputData = array();
				$barcode = json_decode(getPaymenyAPI("BarCode",$orInternalCaseNo,$curPeriod));
				$each = get_object_vars($barcode);
				$outputData["shouldPayAmount"] = $shouldPayAmount;
				$outputData["status"] = $status;
				$outputData["first"] = $each["第一段條碼"];
				$outputData["second"] = $each["第二段條碼"];
				$outputData["third"] = $each["第三段條碼"];
				$api->setInformation($outputData, 1, 1, "OK");
			}else{
				$api->setInformation(array_filter($apiData), 0, 0, "No matches found.");
			}
		}
		$api->setResult(false);
		break;
		case "get_pushed_msg":
		$memNo = getMemberNo();
		$api->setWhereArray(array("memNo"=>$memNo));
		$api->setOrderArray("icDate");
		$api->getWithWhereAndJoinClause(true);
		break;
		case "latest_version":
		if($which == "ios"){
			$api->setRetrieveArray(array("iosVersion"));
			$api->getWithWhereAndJoinClause();
			$data = $api->getData();
			$api->setInformation($data[0]["iosVersion"], 1, 1, "OK");
		}else{
			$api->setRetrieveArray(array("androidVersion"));
			$api->getWithWhereAndJoinClause();
			$data = $api->getData();
			$api->setInformation($data[0]["androidVersion"], 1, 1, "OK");
		}
		$api->setResult();
		break;

		//取得手機貸款相關資訊
		case "cellphone_related_data":
		$memNo = getMemberNo();
		$mem = new API("member");
		$mem->getOne($memNo);
		$memData = $mem->getData();
		//if($memData[0]["memClass"] != "學生"){
			$api->setInformation(null, 0, 0, "手機貸款僅開放給學生！");
		//}else{
				//相關參數
			$api->getAll();
			$data = $api->getData();
				//期數
			$lp = new API("loan_periods");
			$lp->getAll();
			$lpData = $lp->getData();
				//手機廠牌
			$cb = new API("cellphone_brand");
			$cb->getAll();
			$cbData = $cb->getData();
			$outputData = array(
				"cellphone_brands"=>$cbData,
				"minAmount"=>$data[0]["minCellAmount"],
				"maxAmount"=>$data[0]["maxCellAmount"],
				"periods"=>$lpData
			);

			$api->setInformation($outputData, 1, 1, "OK");
		//}
		$api->setResult(false);
		break;
		//取得機車貸款相關資訊
		case "motorbike_related_data":
			//相關參數
		$api->getAll();
		$data = $api->getData();
			//期數
		$lp = new API("loan_periods");
		$lp->getAll();
		$lpData = $lp->getData();
			//機車廠牌
		$cb = new API("motorbike_brand");
		$cb->getAll();
		$cbData = $cb->getData();
			//機車年分
		$years = array();
		for($i=0; $i<=$data[0]["maxMotorYearDiff"]; $i++){
			$years[] = date("Y")-$i;
		}
			//CC數
		$cc = new API("motorbike_cc");
		$cc->getAll();
		$ccData = $cc->getData();

		$outputData = array(
			"motorbike_brands"=>$cbData,
			"years"=>$years,
			"cc_amount"=>$ccData,
			"minAmount"=>$data[0]["minMotorAmount"],
			"periods"=>$lpData
		);
		$api->setInformation($outputData, 1, 1, "OK");
		$api->setResult(false);
		break;
		//取得機車最大貸款金額
		case "get_motorbike_max_amount":
		foreach($_POST as $key=>$value){
			$$key = $value;
		}
		if(isset($mcNo,$year)){
			$yearDiff = date('Y')-$year;
			$api->setWhereArray(array("mcNo"=>$mcNo,"mmaYearDiff"=>$yearDiff));
			$api->getWithWhereAndJoinClause();
			$data = $api->getData();
			if($data != null){
				$outputData = array(
					"maxAmount"=>$data[0]["mmaAmount"]*10000
				);
				$api->setInformation($outputData, 1, 1, "OK");
			}else{
				$api->setInformation($data, 0, 0, "查無此CC數與此年分的最大貸款金額。");
			}
		}else{
			$api->setInformation(null, 0, 0, "必須帶入CC數與年份");
		}
		$api->setResult(false);
		break;
		//取得月付金額及利息
		case "get_pay_interest":
		foreach($_POST as $key=>$value){
			$$key = $value;
		}
		if(isset($amount,$lpNo)){
			$amountBase = $amount/10000;
			$api->setWhereArray(array("lpNo"=>$lpNo));
			$api->getWithWhereAndJoinClause();
			$data = $api->getData();

			$lp = new API("loan_periods");
			$lp->getOne($lpNo);
			$lpData = $lp->getData();

			$minMonthly = floor($data[0]["mbMin"]*$amountBase);
			$maxMonthly = floor($data[0]["mbMax"]*$amountBase);

			if($minMonthly > 1000){
				$monthlyPay = array(
					"minPay"=>$minMonthly,
					"maxPay"=>$maxMonthly
				);

				$totalMinShouldPay = $minMonthly*$lpData[0]["lpPeriod"];
				$minInterestAmount = ($totalMinShouldPay - $amount)/($lpData[0]["lpPeriod"]*30);
				$totalMaxShouldPay = $maxMonthly*$lpData[0]["lpPeriod"];
				$maxInterestAmount = ($totalMaxShouldPay - $amount) >= 0 ? ($totalMaxShouldPay - $amount)/($lpData[0]["lpPeriod"]*30) : 0;
				$interest = array(
					"minInterest"=>floor($minInterestAmount),
					"maxInterest"=>floor($maxInterestAmount)
				);
				if($data != null){
					$outputData = array(
						"monthlyPay"=>$monthlyPay,
						"interest"=>$interest
					);
					$api->setInformation($outputData, 1, 1, "OK");
				}else{
					$api->setInformation($data, 0, 0, "查無資料。");
				}
			}else{
				$api->setInformation(null, 0, 0, "您的借款金額過低無法使用此".$lpData[0]["lpPeriod"]."期分期，請調整借款金額或分期數");
			}

		}else{
			$api->setInformation(null, 0, 0, "必須帶入貸款金額、期數ID");
		}
		$api->setResult(false);
		break;
		//手機機車訂單列表
		case "loan_orders_list":
		foreach($_POST as $key=>$value){
			$$key = $value;
		}
		if(isset($mcoType,$adTokenId)){
			$memNo = getMemberNo();
			$api->setWhereArray(array("memNo"=>$memNo,"mcoType"=>$mcoType));
			$api->setRetrieveArray(array("mcoNo","mcoStatus","mcoCaseNo","mcoPeriodTotal","mcoPeriodAmount","mcoDate"));
			$api->getWithWhereAndJoinClause(true);
			$data = $api->getData();
			if($data != null){
				$rc = new API("real_cases");
				foreach($data as $key=>&$value){
					$rcData = $rc->getCustomSql("select rcApproStatus from real_cases where (rcType = 1 or rcType = 2) and rcRelateDataNo = ".$value["mcoNo"]);
					$value["rcApproStatus"] = $rcData[0]["rcApproStatus"];
				}
				$api->setInformation($data, 1, count($data), "OK");
			}else{
				$api->setInformation($data, 0, 0, "查無資料");
			}
		}else{
			$api->setInformation(null, 0, 0, "必須帶入Token與訂單類型");
		}
		$api->setResult(false);
		break;
		//單筆手機/機車訂單資料
		case "single_loan_order_detail":
        $api_real_case = new API("real_cases");
			//sander test
			//$api_real_case->setWhereArray(array("real_cases`.`rcNo"=>$which));
        //$api_real_case->setRetrieveArray(array("rcRelateDataNo"));
        //$api_real_case->getWithWhereAndJoinClause();
		   //$data_real_case = $api_real_case->getData();

		$api->setJoinArray(array("member"=>"memNo"));
		//$api->setWhereArray(array("motorbike_cellphone_orders`.`mcoNo"=>$data_real_case[0]["rcRelateDataNo"]));
		$api->setWhereArray(array("motorbike_cellphone_orders`.`mcoNo"=>$which));
		$api->setRetrieveArray(array("mcoNo",
			"motorbike_cellphone_orders.memNo",
			/*訂單資訊*/
			"mcoDate",
			"mcoStatus",
			"mcoIfEditable",
			"mcoIfSecret",
			"mcoPeriodAmount",
			"mcoPeriodTotal",
			"mcoTotalShouldPay",
			"mcoCellBrand",
			"mcoMotorBrand",
			"mcoCcNumber",
			"mcoCellphoneSpec",
			"mcoMotorbikeSpec",
			"mcoCarNum",
			"mcoYear",
			"mcoMinMonthlyTotal",
			"mcoMaxMonthlyTotal",
			"mcoDailyInterest",
			/*會員資料*/
			"motorbike_cellphone_orders.memClass",
			"memSchool",
			"memCompanyName",
			"memSalary",
			"memYearWorked",
			"memAccount",
			"memSubEmail",
			"memEmailAuthen",
			"memName",
			"memGender",
			"memBday",
			"memIdNum",
			"memPostCode",
			"memAddr",
			"memPhone",
			"memCell",
			"memLineId",
			"memRegistDate",
			"memRegistMethod",
			/*訂單其他資料*/
			"mcoBirthPostCode",
			"mcoBirthPhone",
			"mcoBirthAddr",
			"mcoLivingOwnership",
			"mcoCompanyStatus",
			"mcoCompanyPhone",
			"mcoCompanyPhoneExt",
			"mcoCreditNum",
			"mcoCreditIssueBank",
			"mcoCreditDueDate",
			"mcoCreditSecurityNum",
			"mcoDocProvideReason",
			"mcoDocProvideComment",
			"mcoApplyPurpose",
			"mcoIdIssueYear",
			"mcoIdIssueMonth",
			"mcoIdIssueDay",
			"mcoIdIssuePlace",
			"mcoIdIssueType",
			"mcoAvailTime",
			"mcoExtraInfo",
			/*聯絡人資料*/
			"mcoContactName",
			"mcoContactRelation",
			"mcoContactPhone",
			"mcoContactCell",
			/*證件上傳*/
			"mcoIdImgTop",
			"mcoIdImgBot",
			"mcoStudentIdImgTop",
			"mcoStudentIdImgBot",
			"mcoSubIdImgTop",
			"mcoCarIdImgTop",
			"mcoBankBookImgTop",
			"mcoRecentTransactionImgTop",
			"mcoExtraInfoUpload"
		));
		$api->getWithWhereAndJoinClause();
		$data = $api->getData();
		$mem = new Member();
		$memData = $mem->getOneMemberByNo($data[0]["memNo"]);
			// 客製資料
		$schoolArr = $data[0]["memSchool"];
		$apiSch = new API("school");
		$apiSch->setWhereArray(array("schName"=>$schoolArr[0]));
		$apiSch->getWithWhereAndJoinClause();
		$schData = $apiSch->getData();
		$schoolKey = $schData[0]["schNo"];
		$data[0]["memSchool"][0] = $schoolKey;
		$dateStrArr = explode("-",date("Y-m-d",strtotime($data[0]["orDate"])));
		$dateStr = "";
		foreach($dateStrArr as $key=>$value){
			if($key == "0"){
				$dateStr .= ($value - 1911)."年";
			}else if($key == "1"){
				$dateStr .= $value."月";
			}else{
				$dateStr .= $value."日";
			}
		}
		$data[0]["mcoDate"] = $dateStr;
		$data[0]["company"] = $data[0]["memCompanyName"];
		$data[0]["salary"] = $data[0]["memSalary"];
		$data[0]["yearWorked"] = $data[0]["memYearWorked"];
		if($data[0]["mcoDocProvideReason"] == "1"){
			$data[0]["mcoDocProvideReason"] = $data[0]["mcoDocProvideComment"];
		}else if($data[0]["mcoDocProvideReason"] == "0"){
			$data[0]["mcoDocProvideReason"] = "無";
		}else{
			$or = new Orders();
			$data[0]["mcoDocProvideReason"] = $or->reasonArr[$data[0]["mcoDocProvideReason"]];
		}
		if(!empty($memData[0]["memRecommCode"])){
			$data[0]["ifHasRecomm"] = "是";
		}else{
			$data[0]["ifHasRecomm"] = "否";
		}
		$data[0]["paymentPerMonth"] = ceil($data[0]["mcoTotalShouldPay"]/$data[0]["mcoPeriodAmount"])."";
		if($data != null){
			$api->setInformation($data[0], 1, 1, "OK");
		}else{
			$api->setInformation($data, 0, 0, "No matches found.");
		}
		$api->setResult(false);
		break;
		case "member_last_loan_order":
		$memNo = getMemberNo();
			// $memNo = $which;
		$api->setJoinArray(array("member"=>"memNo"));
		$api->setWhereArray(array("member`.`memNo"=>$memNo,"motorbike_cellphone_orders`.`mcoType"=>$_POST["mcoType"]));
		$api->setRetrieveArray(array("mcoNo",
			"motorbike_cellphone_orders.memNo",
			/*訂單資訊*/
			"mcoDate",
			"mcoStatus",
			"mcoIfEditable",
			"mcoIfSecret",
			"mcoPeriodAmount",
			"mcoPeriodTotal",
			"mcoTotalShouldPay",
			"mcoCellBrand",
			"mcoMotorBrand",
			"mcoCcNumber",
			"mcoCellphoneSpec",
			"mcoMotorbikeSpec",
			"mcoCarNum",
			"mcoYear",
			"mcoMinMonthlyTotal",
			"mcoMaxMonthlyTotal",
			"mcoDailyInterest",
			/*會員資料*/
			"motorbike_cellphone_orders.memClass",
			"memSchool",
			"memCompanyName",
			"memSalary",
			"memYearWorked",
			"memAccount",
			"memSubEmail",
			"memEmailAuthen",
			"memName",
			"memGender",
			"memBday",
			"memIdNum",
			"memPostCode",
			"memAddr",
			"memPhone",
			"memCell",
			"memLineId",
			"memRegistDate",
			"memRegistMethod",
			/*訂單其他資料*/
			"mcoBirthPostCode",
			"mcoBirthPhone",
			"mcoBirthAddr",
			"mcoLivingOwnership",
			"mcoCompanyStatus",
			"mcoCompanyPhone",
			"mcoCompanyPhoneExt",
			"mcoCreditNum",
			"mcoCreditIssueBank",
			"mcoCreditDueDate",
			"mcoCreditSecurityNum",
			"mcoDocProvideReason",
			"mcoDocProvideComment",
			"mcoApplyPurpose",
			"mcoIdIssueYear",
			"mcoIdIssueMonth",
			"mcoIdIssueDay",
			"mcoIdIssuePlace",
			"mcoIdIssueType",
			"mcoAvailTime",
			"mcoExtraInfo",
			/*聯絡人資料*/
			"mcoContactName",
			"mcoContactRelation",
			"mcoContactPhone",
			"mcoContactCell",
			/*證件上傳*/
			"mcoIdImgTop",
			"mcoIdImgBot",
			"mcoStudentIdImgTop",
			"mcoStudentIdImgBot",
			"mcoSubIdImgTop",
			"mcoCarIdImgTop",
			"mcoBankBookImgTop",
			"mcoRecentTransactionImgTop",
			"mcoExtraInfoUpload"
		));
		$api->getWithWhereAndJoinClause(true);
		$data = $api->getData();
		if($data != null){
			$schoolArr = $data[0]["memSchool"];
			$apiSch = new API("school");
			$apiSch->setWhereArray(array("schName"=>$schoolArr[0]));
			$apiSch->getWithWhereAndJoinClause();
			$schData = $apiSch->getData();
			$schoolKey = $schData[0]["schNo"];
			$data[0]["memSchool"][0] = $schoolKey;
			$dateStrArr = explode("-",date("Y-m-d",strtotime($data[0]["orDate"])));
			$dateStr = "";
			foreach($dateStrArr as $key=>$value){
				if($key == "0"){
					$dateStr .= ($value - 1911)."年";
				}else if($key == "1"){
					$dateStr .= $value."月";
				}else{
					$dateStr .= $value."日";
				}
			}
			$mem = new Member();
			$memData = $mem->getOneMemberByNo($data[0]["memNo"]);
			if(!empty($memData[0]["memRecommCode"])){
				$data[0]["ifHasRecomm"] = "是";
			}else{
				$data[0]["ifHasRecomm"] = "否";
			}
			$data[0]["mcoDate"] = $dateStr;
			$data[0]["hasLastOrder"] = true;
			$data[0]["company"] = $data[0]["memCompanyName"];
			$data[0]["salary"] = $data[0]["memSalary"];
			$data[0]["yearWorked"] = $data[0]["memYearWorked"];

			$api->setInformation($data[0], 1, 1, "OK");
		}else{
			$apiMem = new API("member");
			$apiMem->setWhereArray(array("memNo"=>$memNo));
			$apiMem->setRetrieveArray(array(
				/*會員資料*/
				"memNo",
				"memClass",
				"memSchool",
				"memCompanyName",
				"memSalary",
				"memYearWorked",
				"memAccount",
				"memSubEmail",
				"memEmailAuthen",
				"memName",
				"memGender",
				"memBday",
				"memIdNum",
				"memAddr",
				"memPhone",
				"memCell",
				"memLineId",
				"memRegistDate",
				"memRegistMethod"
			));
			$apiMem->getWithWhereAndJoinClause();
			$dataMem = $apiMem->getData();
			$dataMem[0]["company"] = $dataMem[0]["memCompanyName"];
			$dataMem[0]["salary"] = $dataMem[0]["memSalary"];
			$dataMem[0]["yearWorked"] = $dataMem[0]["memYearWorked"];
			$dataMem[0]["hasLastOrder"] = false;
			$api->setInformation($dataMem[0], 1, 1, "OK");
		}
		$api->setResult(false);
		break;
		//樂分期訂單繳費列表
		case "get_payment_orders_new":
		$memNo = getMemberNo();
		$api->setWhereArray(array("memNo"=>$memNo,"orMethod"=>"1"));
		$api->setRetrieveArray(array("orNo","memNo","pmNo","orCaseNo","orStatus","orInternalCaseNo","orDate","orPeriodAmnt"));
		$api->getWithWhereAndJoinClause();
		$data = $api->getData();
		$resultData = array();
		if($data != null){
			$pm = new Product_Manage();
			$pro = new Product();
			$curIndex = 0;
			foreach($data as $key=>$value){
					//檢查是否在新系統有資料
				$rc = new API("real_cases");
				$rc->setWhereArray(array("rcRelateDataNo"=>$value["orNo"],"rcType"=>"0"));
				$rc->getWithWhereAndJoinClause();
				$rcData = $rc->getData();
				if($rcData != null){
						//查看是否有本息表
					$tpi = new API("tpi");
					$tpi->setWhereArray(array("rcNo"=>$rcData[0]["rcNo"]));
					$tpi->getWithWhereAndJoinClause();
					$tpiData = $tpi->getData();
					if($tpiData != null){
						foreach($value as $keyIn=>$valueIn){
							$resultData[$curIndex][$keyIn] = $valueIn;
						}
						$pmData = $pm->getOnePMByNo($value["pmNo"]);
						$proData = $pro->getOneProByNo($pmData[0]["proNo"]);
						$resultData[$curIndex]["proName"] = $proData[0]["proName"];
						$curIndex++;
					}
				}else{
						// 是否有案件編號
					if(!empty($value["orInternalCaseNo"])){
						$apiData = json_decode(getPaymenyAPI("TPI",$value["orInternalCaseNo"],""));
							// 原彰API是否有資料
						if(!empty(array_filter($apiData))){
							foreach($value as $keyIn=>$valueIn){
								$resultData[$curIndex][$keyIn] = $valueIn;
							}
							$pmData = $pm->getOnePMByNo($value["pmNo"]);
							$proData = $pro->getOneProByNo($pmData[0]["proNo"]);
							$resultData[$curIndex]["proName"] = $proData[0]["proName"];
							$curIndex++;
						}
					}
				}
			}
			if(!empty(array_filter($resultData))){
				$api->setInformation($resultData, 1, count($resultData), "OK");
			}else{
				$api->setInformation($resultData, 0, 0, "No matches found.");
			}
		}else{
			$api->setInformation($data, 0, 0, "No matches found.");
		}
		$api->setResult(false);
		break;
			//樂分期每期繳款資訊
		case "get_each_period_payment_new":
		$orNo = $which;
		$api->setWhereArray(array("orNo"=>$orNo));
		$api->getWithWhereAndJoinClause();
		$data = $api->getData();

		$pm = new Product_Manage();
		$pro = new Product();
		$pmData = $pm->getOnePMByNo($data[0]["pmNo"]);
		$proData = $pro->getOneProByNo($pmData[0]["proNo"]);

			//檢查是否在新系統有資料
		$rc = new API("real_cases");
		$rc->setWhereArray(array("rcRelateDataNo"=>$orNo,"rcType"=>"0"));
		$rc->getWithWhereAndJoinClause();
		$rcData = $rc->getData();
		if($rcData != null){
				//查看是否有本息表
			$tpi = new API("tpi");
			$tpi->setWhereArray(array("rcNo"=>$rcData[0]["rcNo"]));
			$tpi->setOrderArray("tpiPeriod");
			$tpi->getWithWhereAndJoinClause();
			$tpiData = $tpi->getData();
			if($tpiData != null){
				$outputData = array();
					// 當期期數
				$countPeriod = 0;
				foreach($tpiData as $whichPeriod=>$content){
					if(empty($content["tpiActualDate"])){
						$outputData[$countPeriod]["orCaseNo"] = $data[0]["orCaseNo"];
						$outputData[$countPeriod]["orDate"] = $data[0]["orDate"];
						$outputData[$countPeriod]["orPeriodAmnt"] = $data[0]["orPeriodAmnt"];
						$outputData[$countPeriod]["proName"] = $proData[0]["proName"];
						$outputData[$countPeriod]["period"] = $content["tpiPeriod"];
						$outputData[$countPeriod]["shouldPayDate"] = $content["tpiSupposeDate"];
						$outputData[$countPeriod]["shouldPayAmount"] = $content["tpiPeriodTotal"];
						$outputData[$countPeriod]["penalty"] = $content["tpiPenalty"];
						$outputData[$countPeriod]["shouldPayTotal"] = ($content['tpiIfCancelPenalty'] !='1') ? $content["tpiPeriodTotal"]+$content["tpiPenalty"]:$content["tpiPeriodTotal"];
						$outputData[$countPeriod]["actualPayDate"] = $content["tpiActualDate"];
						if(empty($content["tpiActualDate"])){
							$due = 0;
						}else{
							$due = ceil((strtotime($content["tpiActualDate"]) - strtotime($content["tpiSupposeDate"]))/86400);
							if($due < 0){
								$due = 0;
							}
						}
						$outputData[$countPeriod]["overdue"] = $due;
						if(empty($content["tpiActualDate"])){
							$outputData[$countPeriod]["status"] = "0";
						}else{
							$outputData[$countPeriod]["status"] = "1";
						}
						$countPeriod++;
					}
				}
					//計算到幾期
				$curPeriod = 0;
				foreach($tpiData as $whichPeriod=>$content){
					if(empty($content["tpiActualDate"])){
						$curPeriod = $content["tpiPeriod"]-1;
						break;
					}
				}

				$api->setInformation($outputData, 1, count($outputData), "OK");
			}else{
				$api->setInformation(array_filter($apiData), 0, 0, "No matches found.");
			}
		}else{
			$orInternalCaseNo = $data[0]["orInternalCaseNo"];
			$apiData = json_decode(getPaymenyAPI("TPI",$orInternalCaseNo,""));
				// 原彰API是否有資料
			if(!empty(array_filter($apiData))){
				$outputData = array();
					// 當期期數
				$countPeriod = 0;
				foreach($apiData as $whichPeriod=>$contentObj){
					$content = get_object_vars($contentObj);
					if(empty($content["實際繳款日"])){
						$outputData[$countPeriod]["orCaseNo"] = $data[0]["orCaseNo"];
						$outputData[$countPeriod]["orDate"] = $data[0]["orDate"];
						$outputData[$countPeriod]["orPeriodAmnt"] = $data[0]["orPeriodAmnt"];
						$outputData[$countPeriod]["proName"] = $proData[0]["proName"];
						$outputData[$countPeriod]["period"] = $content["期數"];
						$outputData[$countPeriod]["shouldPayDate"] = $content["應繳款日"];
						$outputData[$countPeriod]["shouldPayAmount"] = $content["應繳金額"];
						$outputData[$countPeriod]["penalty"] = ((time() - strtotime($content["應繳款日"]) >= (3*86400)) ? "300" : "0");
						$outputData[$countPeriod]["shouldPayTotal"] = $content["應繳金額"] + $outputData[$whichPeriod]["penalty"];
						$outputData[$countPeriod]["actualPayDate"] = $content["實際繳款日"];
						if(empty($content["實際繳款日"])){
							$due = 0;
						}else{
							$due = ceil((strtotime($content["實際繳款日"]) - strtotime($content["應繳款日"]))/86400);
							if($due < 0){
								$due = 0;
							}
						}
						$outputData[$countPeriod]["overdue"] = $due;
						switch($content["狀態"]){
							case "未繳款":
							$outputData[$countPeriod]["status"] = "0";
							break;
							case "已繳款":
							$outputData[$countPeriod]["status"] = "1";
							break;
							default:
							$outputData[$countPeriod]["status"] = "2";
							break;
						}
						$countPeriod++;
					}
				}
				$api->setInformation($outputData, 1, count($outputData), "OK");
			}else{
				$api->setInformation(array_filter($apiData), 0, 0, "No matches found.");
			}
		}
		$api->setResult(false);
		break;
		//樂分期當期繳款條碼
		case "get_barcode_by_period_new":
		$orNo = $_POST["orNo"];
		$curPeriod = $_POST["period"];
		$api->setWhereArray(array("orNo"=>$orNo));
		$api->getWithWhereAndJoinClause();
		$data = $api->getData();

			//檢查是否在新系統有資料
		$rc = new API("real_cases");
		$rc->setWhereArray(array("rcRelateDataNo"=>$orNo,"rcType"=>"0"));
		$rc->getWithWhereAndJoinClause();
		$rcData = $rc->getData();
		if($rcData != null){
				//查看是否有本息表
			$tpi = new API("tpi");
			$tpi->setWhereArray(array("rcNo"=>$rcData[0]["rcNo"]));
			$tpi->getWithWhereAndJoinClause();
			$tpiData = $tpi->getData();
			if($tpiData != null){
				$outputData = array();
				foreach($tpiData as $whichPeriod=>$content){
					if($content["tpiPeriod"] == $curPeriod){
						$shouldPayAmount = ($content['tpiIfCancelPenalty'] !='1') ? $content["tpiPeriodTotal"]+$content["tpiPenalty"]:$content["tpiPeriodTotal"];
						if(empty($content["tpiActualDate"])){
							$status = "0";
						}else{
							$status = "1";
						}
						$bar = new API("barcode");
						$bar->setWhereArray(array("tpiNo"=>$content["tpiNo"]));
						$bar->getWithWhereAndJoinClause();
						$barData = $bar->getData();
						if(count($barData) > 3){
							$shouldPayAmount = $content["tpiPeriodTotal"];
							$penalty = $content["tpiPenalty"];
							$outputData["shouldPayAmount"] = $shouldPayAmount;
							$outputData["penalty"] = $penalty;
							foreach($barData as $barKey=>$barVal){
								if($barVal["barType"] == "1"){
									if($barVal["barIndex"] == "1"){
										$outputData["firstPenalty"] = $barVal["barBarcode"];
									}else if($barVal["barIndex"] == "2"){
										$outputData["secondPenalty"] = $barVal["barBarcode"];
									}else if($barVal["barIndex"] == "3"){
										$outputData["thirdPenalty"] = $barVal["barBarcode"];
									}
								}else{
									if($barVal["barIndex"] == "1"){
										$outputData["first"] = $barVal["barBarcode"];
									}else if($barVal["barIndex"] == "2"){
										$outputData["second"] = $barVal["barBarcode"];
									}else if($barVal["barIndex"] == "3"){
										$outputData["third"] = $barVal["barBarcode"];
									}
								}
							}
						}else{
							$shouldPayAmount = ($content['tpiIfCancelPenalty'] !='1') ? $content["tpiPeriodTotal"]+$content["tpiPenalty"]:$content["tpiPeriodTotal"];
							$outputData["shouldPayAmount"] = $shouldPayAmount;
							foreach($barData as $barKey=>$barVal){
								if($barVal["barIndex"] == "1"){
									$outputData["first"] = $barVal["barBarcode"];
								}else if($barVal["barIndex"] == "2"){
									$outputData["second"] = $barVal["barBarcode"];
								}else if($barVal["barIndex"] == "3"){
									$outputData["third"] = $barVal["barBarcode"];
								}
							}
						}
						break;
					}
				}
				$outputData["shouldPayAmount"] = $shouldPayAmount;
				$outputData["status"] = $status;

				$api->setInformation($outputData, 1, 1, "OK");
			}else{
				$api->setInformation(array_filter($apiData), 0, 0, "No matches found.");
			}
		}else{
			$orInternalCaseNo = $data[0]["orInternalCaseNo"];
			$apiData = json_decode(getPaymenyAPI("TPI",$orInternalCaseNo,""));
				// 原彰API是否有資料
			if(!empty(array_filter($apiData))){
				foreach($apiData as $whichPeriod=>$contentObj){
					$content = get_object_vars($contentObj);
					if($content["期數"] == $curPeriod){
						$shouldPayAmount = $content["應繳金額"];
						switch($content["狀態"]){
							case "未繳款":
							$status = "0";
							break;
							case "已繳款":
							$status = "1";
							break;
							default:
							$status = "2";
							break;
						}
						break;
					}
				}
				$outputData = array();
				$barcode = json_decode(getPaymenyAPI("BarCode",$orInternalCaseNo,$curPeriod));
				$each = get_object_vars($barcode);
				$outputData["shouldPayAmount"] = $shouldPayAmount;
				$outputData["status"] = $status;
				$outputData["first"] = $each["第一段條碼"];
				$outputData["second"] = $each["第二段條碼"];
				$outputData["third"] = $each["第三段條碼"];
				$api->setInformation($outputData, 1, 1, "OK");
			}else{
				$api->setInformation(array_filter($apiData), 0, 0, "No matches found.");
			}
		}
		$api->setResult(false);
		break;
			//機車/手機貸款訂單繳費列表
		case "get_payment_orders_loan":
		$memNo = getMemberNo();
		$api->setWhereArray(array("memNo"=>$memNo,"mcoType"=>$_POST["mcoType"]));
		$api->setRetrieveArray(array("mcoNo","mcoType","memNo","mcoCaseNo","mcoDate","mcoPeriodAmount","mcoPeriodTotal"));
		$api->getWithWhereAndJoinClause();
		$data = $api->getData();
		$resultData = array();
		if($data != null){
			$curIndex = 0;
			foreach($data as $key=>$value){
					//檢查是否在新系統有資料
				$rc = new API("real_cases");
				$rc->setWhereArray(array("rcRelateDataNo"=>$value["mcoNo"],"rcType"=>$value["mcoType"]));
				$rc->getWithWhereAndJoinClause();
				$rcData = $rc->getData();
				if($rcData != null){
						//查看是否有本息表
					$tpi = new API("tpi");
					$tpi->setWhereArray(array("rcNo"=>$rcData[0]["rcNo"]));
					$tpi->getWithWhereAndJoinClause();
					$tpiData = $tpi->getData();
					if($tpiData != null){
						foreach($value as $keyIn=>$valueIn){
							$resultData[$curIndex][$keyIn] = $valueIn;
						}
						$curIndex++;
					}
				}
			}
			if(!empty(array_filter($resultData))){
				$api->setInformation($resultData, 1, count($resultData), "OK");
			}else{
				$api->setInformation($resultData, 0, 0, "No matches found.");
			}
		}else{
			$api->setInformation($data, 0, 0, "No matches found.");
		}
		$api->setResult(false);
		break;
			//機車/手機每期繳款資訊
		case "get_each_period_payment_loan":
		$mcoNo = $which;
		$api->setWhereArray(array("mcoNo"=>$mcoNo));
		$api->getWithWhereAndJoinClause();
		$data = $api->getData();

			//檢查是否在新系統有資料
		$rc = new API("real_cases");
		$rc->setWhereArray(array("rcRelateDataNo"=>$mcoNo,"rcType"=>$data[0]["mcoType"]));
		$rc->getWithWhereAndJoinClause();
		$rcData = $rc->getData();
		if($rcData != null){
				//查看是否有本息表
			$tpi = new API("tpi");
			$tpi->setWhereArray(array("rcNo"=>$rcData[0]["rcNo"]));
			$tpi->setOrderArray("tpiPeriod");
			$tpi->getWithWhereAndJoinClause();
			$tpiData = $tpi->getData();
			if($tpiData != null){
				$outputData = array();
					// 當期期數
				foreach($tpiData as $whichPeriod=>$content){
					if(empty($content["tpiActualDate"])){
						if(empty($content["tpiActualDate"])){
							$due = 0;
						}else{
							$due = ceil((strtotime($content["tpiActualDate"]) - strtotime($content["tpiSupposeDate"]))/86400);
							if($due < 0){
								$due = 0;
							}
						}
						if(empty($content["tpiActualDate"])){
							$tpiStatus = "0";
						}else{
							$tpiStatus = "1";
						}
						$curPeriodData = array(
							"mcoCaseNo"=>$data[0]["mcoCaseNo"],
							"mcoDate"=>$data[0]["mcoDate"],
							"mcoPeriodAmount"=>$data[0]["mcoPeriodAmount"],
							"mcoPeriodTotal"=>$data[0]["mcoPeriodTotal"],
							"period"=>$content["tpiPeriod"],
							"shouldPayDate"=>$content["tpiSupposeDate"],
							"shouldPayAmount"=>$content["tpiPeriodTotal"],
							"penalty"=>$content["tpiPenalty"],
							"shouldPayTotal"=>$content["tpiPeriodTotal"]+$content["tpiPenalty"],
							"actualPayDate"=>$content["tpiActualDate"],
							"overdue"=>$due,
							"status"=>$tpiStatus
						);
						$outputData[] = $curPeriodData;
					}
				}

				$api->setInformation($outputData, 1, count($outputData), "OK");
			}else{
				$api->setInformation(array_filter($tpiData), 0, 0, "No matches found.");
			}
		}else{
			$api->setInformation(null, 0, 0, "查無此案件");
		}
		$api->setResult(false);
		break;
			//機車/手機當期繳款條碼
		case "get_barcode_by_period_loan":
		$mcoNo = $_POST["mcoNo"];
		$curPeriod = $_POST["period"];
		$api->setWhereArray(array("mcoNo"=>$mcoNo));
		$api->getWithWhereAndJoinClause();
		$data = $api->getData();

			//檢查是否在新系統有資料
		$rc = new API("real_cases");
		$rc->setWhereArray(array("rcRelateDataNo"=>$mcoNo,"rcType"=>$data[0]["mcoType"]));
		$rc->getWithWhereAndJoinClause();
		$rcData = $rc->getData();
		if($rcData != null){
				//查看是否有本息表
			$tpi = new API("tpi");
			$tpi->setWhereArray(array("rcNo"=>$rcData[0]["rcNo"]));
			$tpi->getWithWhereAndJoinClause();
			$tpiData = $tpi->getData();
			if($tpiData != null){
				$outputData = array();
				foreach($tpiData as $whichPeriod=>$content){
					if($content["tpiPeriod"] == $curPeriod){
						if(empty($content["tpiActualDate"])){
							$status = "0";
						}else{
							$status = "1";
						}
						$bar = new API("barcode");
						$bar->setWhereArray(array("tpiNo"=>$content["tpiNo"]));
						$bar->getWithWhereAndJoinClause();
						$barData = $bar->getData();
						if(count($barData) > 3){
							$shouldPayAmount = $content["tpiPeriodTotal"];
							$penalty = $content["tpiPenalty"];
							$outputData["shouldPayAmount"] = $shouldPayAmount;
							$outputData["penalty"] = $penalty;
							foreach($barData as $barKey=>$barVal){
								if($barVal["barType"] == "1"){
									if($barVal["barIndex"] == "1"){
										$outputData["firstPenalty"] = $barVal["barBarcode"];
									}else if($barVal["barIndex"] == "2"){
										$outputData["secondPenalty"] = $barVal["barBarcode"];
									}else if($barVal["barIndex"] == "3"){
										$outputData["thirdPenalty"] = $barVal["barBarcode"];
									}
								}else{
									if($barVal["barIndex"] == "1"){
										$outputData["first"] = $barVal["barBarcode"];
									}else if($barVal["barIndex"] == "2"){
										$outputData["second"] = $barVal["barBarcode"];
									}else if($barVal["barIndex"] == "3"){
										$outputData["third"] = $barVal["barBarcode"];
									}
								}
							}
						}else{
							$shouldPayAmount = $content["tpiPeriodTotal"]+$content["tpiPenalty"];
							$outputData["shouldPayAmount"] = $shouldPayAmount;
							foreach($barData as $barKey=>$barVal){
								if($barVal["barIndex"] == "1"){
									$outputData["first"] = $barVal["barBarcode"];
								}else if($barVal["barIndex"] == "2"){
									$outputData["second"] = $barVal["barBarcode"];
								}else if($barVal["barIndex"] == "3"){
									$outputData["third"] = $barVal["barBarcode"];
								}
							}
						}
						break;
					}
				}
				$outputData["status"] = $status;

				$api->setInformation($outputData, 1, 1, "OK");
			}else{
				$api->setInformation(array_filter($apiData), 0, 0, "No matches found.");
			}
		}
		$api->setResult(false);
		break;
		//sander add
		case "get_supplier_product":
		$supplier_sales = new Supplier_sales;
		$mustFill = array("ssLogId","ssPwd");

		foreach($mustFill as $key=>$value){
			if(!isset($_POST[$value])){
				$errMsg[] = "Missing: ".$value;
			}
		}

		if(!isset($errMsg)){
			if($_POST["ssLogId"] != "" && $_POST["ssPwd"] != ""){
				$supplierData = $supplier_sales->getSupplier_sales_information($_POST["ssLogId"],$_POST["ssPwd"]);
			}else{
				$supplierData = null;
			}

			if($supplierData != null){
				$api->setJoinArray(array("supplier"=>"supNo","product"=>"proNo"));
				$api->setWhereArray(array("supplier`.`".'supNo'=> $supplierData[supNo],"product_manage`.`pmStatus"=>1));
				//$api->setOrArray(array("product_manage`.`pmStatus"=>2));
				$api->setGroupArray(array("product`.`proNo"));
				$api->setRetrieveArray(array("product.proNo","product_manage.pmNo","proName","pmIfDirect","proImage","proSpec","pmDirectAmnt","pmPeriodAmnt","pmBuyAmnt","pmStatus"));
				$api->getWithWhereAndJoinClause();
				$data = $api->getData();
			}
			else{
				$api->setInformation(false, 0, 0, "錯誤的帳號密碼");
			}
		}
		else{
			$api->setInformation(false, 0, 0, $errMsg);
		}
		$api->setResult(false);
		break;
        //sander add end
	}
	?>