<?php
	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../model/require_login.php');


	$pm = new Product_Manage();
	$prod = new Product();

///////////////////////////////////// 舊後台新增商品 //////////////////////////////////////////////////////
	$cat = new Category();
	$bra = new Brand();
	$catData = array();

	$newProNo = "";
	$last3Num = "";
///////////////////////////////////// //////////////////////////////////////////////////////

	foreach($_POST as $key=>$value){
		if (!is_array($value)){
            $$key = trim($value);
		}else{
            $$key = $value;
		}
	}
	
	//錯誤訊息
	$errMsg = array();
	//成功訊息
	$success="";  

	if(!isset($_POST["proNo"]) || $proNo == ""){
		$errMsg["proNoErr"] = "請輸入產品名稱";
	}		

    if(!isset($_POST["catNo"]) || $catNo == ""){
		$errMsg["catErr"] = "請選擇產品分類";
	}		

    if(!isset($_POST["braNo"]) || $braNo == ""){
		$errMsg["braErr"] = "請選擇產品品牌";
	}

	if(!isset($_POST["biNo"]) || $biNo == ""){
		$errMsg["biNoErr"] = "請選擇產品品項";
	}


	//上架金額
	if(!isset($pmPeriodAmnt) || $pmPeriodAmnt == ""){
		$_POST["pmPeriodAmnt"] = 0;
        $pmPeriodAmnt =0;
	}
	//撥款金額
    if(!isset($pmPeriodAmnt2) || $pmPeriodAmnt2 == ""){
		$_POST["pmPeriodAmnt2"] = 0;
        $pmPeriodAmnt2=0;
	}

	//商品說明，文字編輯器
	$_POST["proDetail"] = preg_replace("/\r\n|\r|\n/","",nl2br($_POST["proDetail"]));


	if(!isset($_POST["pmPeriodAmnt2"]) || !is_numeric($pmPeriodAmnt2)){
		$errMsg["pmPeriodAmntErr2"] = "請填入數字";
	}



///////////////////////////////////// 舊後台新增商品 //////////////////////////////////////////////////////
	//取得商品編號

	if(trim($_POST["catNo"]) != "" && trim($_POST["braNo"]) != "" && trim($_POST["biNo"]) != ""){
		//最後一筆
		$lastData = $prod->getAllProDescWithCatAndBraAndItem($braNo, $catNo, $biNo);
		if($lastData != null){
			if(substr($lastData[0]["proCaseNo"], -3)<9){
				$old3Num = substr($lastData[0]["proCaseNo"], -3);
				$last3Num = "00".++$old3Num;
			}else if(substr($lastData[0]["proCaseNo"], -3)<99 && substr($lastData[0]["proCaseNo"], -3)>=9){
				$old3Num = substr($lastData[0]["proCaseNo"], -3);
				$last3Num = "0".++$old3Num;
			}else{
				$old3Num = substr($lastData[0]["proCaseNo"], -3);
				$last3Num = ++$old3Num;
			}
		}else{
			$last3Num = "001";
		}
		$newProNo = $catNo.$braNo.$last3Num;
	}

	//upload part
	$catName = "";
	if(trim($_POST["catNo"]) != ""){
		$catData = $cat->getOneCatByNo($catNo);
		if($catData != null){
			$catName = $catData[0]["catName"];
		}else{
			$catName = "其他";
		}
	}else{
		$catName = "其他";
	}
	if($_POST["imgMethod"] == "album"){
		$imgArr = explode(",", $_POST["album"]);
		if(isset($_FILES["single"])){

			$err = uploadMultipleImg("insert", "", true, "product/".$catName, 'single');
			if($err != ""){
				$errMsg["supStampImgErr"] = $err;
			}
			foreach(json_decode($_POST["single"]) as $value){
				array_push($imgArr, $value);
			}
		}
		$imgArr = array_values(array_filter($imgArr));
		$_POST["proImage"] = json_encode($imgArr,JSON_UNESCAPED_UNICODE);
	}else{
		//圖片上傳
		$err = uploadMultipleImg("insert", "", true, "product/".$catName, 'proImage');
		if($err != ""){
			$errMsg["supStampImgErr"] = $err;
		}
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////



if(empty(array_filter($errMsg))){
        $dt = new DateTime();
        $pmUpDate =$dt->format('Y-m-d H:i:s'); 	
           	    
        $dataS = array();
		$dataS["proName"] = $supLogId."_".$proNo;
        $dataS["bySup"] = 1;
        $dataS["catNo"] = $catNo;
		$dataS["braNo"] = $braNo;
		//
    	$dataS["biNo"] = $biNo;
		$dataS["proOffer"] = $proOffer;
		$dataS["proGift"] = $proGift;
		$dataS["proModelID"] = $proModelID;
		$dataS["proSpec"] = $proSpec;
		$dataS["proDetail"] = $proDetail;
		$dataS["proImage"] = $_POST["proImage"];
		//
		$prod->insert($dataS,$newProNo);


        $checkData  = $prod->getProNo_Sup($catNo,$braNo,$biNo,$supLogId."_".$proNo) ;
        $_POST["proNo"] = $checkData[0]["proNo"] ;
       
        $proNo = $_POST["proNo"]  ;
  
	    //商品利率
	    $pp = new Product_Period();
	    $ppPeriodAmountArr = $_POST["ppPeriodAmount"];
//	    $ppPercentArr = $_POST["ppPercent"];
    	$ppPercentArr = $_POST["ppPercent1"];
	
	    $ppData = $pp->getPPByProduct($proNo);
	    if($ppData != null){
		    foreach($ppPeriodAmountArr as $key=>$value){
			    $ppDataEach = $pp->getPPByProductAndPeriodsAmount($proNo,$value);
			    $dataInsert = array();
			    $dataInsert["ppNo"] = $ppDataEach[0]["ppNo"];
			    $dataInsert["ppPeriodAmount"] = $value;
				$dataInsert["ppPercent"] = $ppPercentArr[$key];
				$dataInsert["ppIntroText"] = '';
			    $pp->update($dataInsert);
		    }
	    }else{
			if(isset($ppPeriodAmountArr)){
				foreach($ppPeriodAmountArr as $key=>$value){
					$dataInsert = array();
					$dataInsert["proNo"] = $proNo;
					$dataInsert["ppPeriodAmount"] = $value;
					$dataInsert["ppPercent"] = $ppPercentArr[$key];
					$dataInsert["ppIntroText"] = '';
					$pp->insert($dataInsert);
				}
			}
	    }	

		unset($_POST["ppPeriodAmount"]);
		unset($_POST["ppPercent"]);
		$update = $pm->insert($_POST);
		$success="新增成功";
	}
	
	$result = array("success"=>$success,"errMsg"=>$errMsg);
	echo json_encode($result);

?>
