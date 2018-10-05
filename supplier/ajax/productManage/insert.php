<?php
	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../model/require_login.php');
	
	$pm = new Product_Manage();
	$prod = new Product();

	foreach($_POST as $key=>$value){
		$$key = trim($value);
	}
	
	//錯誤訊息
	$errMsg = array();
	//成功訊息
	$success="";  

	if(!isset($_POST["proNo"]) || $proNo == ""){
		$errMsg["proNoErr"] = "請輸入商品名稱";
	}		

    if(!isset($_POST["catNo"]) || $catNo == ""){
		$errMsg["catErr"] = "請選擇所屬分類";
	}		

    if(!isset($_POST["braNo"]) || $braNo == ""){
		$errMsg["braErr"] = "請選擇所屬品牌";
	}	

	if($pmPeriodAmnt == ""){
		$_POST["pmPeriodAmnt"] = 0;
        $pmPeriodAmnt =0;
	}
    if($pmPeriodAmnt2 == ""){
		$_POST["pmPeriodAmnt2"] = 0;
        $pmPeriodAmnt2=0;
	}

	if(!isset($_POST["pmPeriodAmnt"]) || !is_numeric($pmPeriodAmnt)){
		$errMsg["pmPeriodAmntErr"] = "請填入數字";
	}

	if(!isset($_POST["pmPeriodAmnt2"]) || !is_numeric($pmPeriodAmnt2)){
		$errMsg["pmPeriodAmntErr2"] = "請填入數字";
	}
	if(empty(array_filter($errMsg))){
        $dt = new DateTime();
        $pmUpDate =$dt->format('Y-m-d H:i:s'); 	
           	    
        $dataS = array();
		$dataS["proName"] = $proNo;
        $dataS["bySup"] = 1;
        $dataS["catNo"] = $catNo;
		$dataS["braNo"] = $braNo;	
	    $prod->insert($dataS);

        $checkData  = $prod->getProNo_Sup($catNo,$braNo,$proNo) ;
        $_POST["proNo"] = $checkData[0]["proNo"] ;
       
        $proNo = $_POST["proNo"]  ;
  
	    //商品利率
	    $pp = new Product_Period();
	    $ppPeriodAmountArr = $_POST["ppPeriodAmount"];
	    $ppPercentArr = $_POST["ppPercent"];
	
	    $ppData = $pp->getPPByProduct($proNo);
	    if($ppData != null){
		    foreach($ppPeriodAmountArr as $key=>$value){
			    $ppDataEach = $pp->getPPByProductAndPeriodsAmount($proNo,$value);
			    $dataInsert = array();
			    $dataInsert["ppNo"] = $ppDataEach[0]["ppNo"];
			    $dataInsert["ppPeriodAmount"] = $value;
			    $dataInsert["ppPercent"] = $ppPercentArr[$key];
			    $pp->update($dataInsert);
		    }
	    }else{
		    foreach($ppPeriodAmountArr as $key=>$value){
			    $dataInsert = array();
			    $dataInsert["proNo"] = $proNo;
			    $dataInsert["ppPeriodAmount"] = $value;
			    $dataInsert["ppPercent"] = $ppPercentArr[$key];
			    $pp->insert($dataInsert);
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
