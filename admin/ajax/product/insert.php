<?php
	header ("content-type: text/HTML; charset=utf-8 ");
	require_once('../../model/require_login.php');
	$pro = new Product();
	$cat = new Category();
	$bra = new Brand();
//	$items = new B_items();
	$catData = array();
	
	$newProNo = "";
	$last3Num = "";
	
	
	foreach($_POST as $key=>$value){
		$$key = $value;
	}
	
	//錯誤訊息
	$errMsg = array();
	//成功訊息
	$success="";


	if($proName == ""){
		$errMsg["proNameErr"] = "必須填入商品名稱";
	}
	if($catNo == ""){
		$errMsg["catNoErr"] = "請選擇所屬分類";
	}
	if($braNo == ""){
		$errMsg["braNoErr"] = "請選擇所屬品牌";
	}
	if($biNo == ""){
		$errMsg["biNoErr"] = "請選擇所屬品項";
	}
	
	
	$_POST["proDetail"] = preg_replace("/\r\n|\r|\n/","",nl2br($_POST["proDetail"]));
	
	
	//取得商品編號
	if(trim($_POST["catNo"]) != "" && trim($_POST["braNo"]) != "" && trim($_POST["biNo"]) != ""){
		//最後一筆
		$lastData = $pro->getAllProDescWithCatAndBraAndItem($braNo, $catNo, $biNo);
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


	//如果沒有錯誤訊息
	if(empty(array_filter($errMsg))){
		$insert = $pro->insert($_POST,$newProNo);

		//
		//多加的
		//
        /*$pp = new Product_Period();
        $dataInsert = array();
        $dataInsert["proNo"] = $proNo;
        $dataInsert["ppPeriodAmount"] = $value;
        $dataInsert["ppPercent"] = $ppPercentArr[$key];
//            $dataInsert["ppIntroText"] = $ppIntroTextArr;
        $pp->insert($dataInsert);


        $pm = new Product_Manage();
        $dataInsert = array();
        $dataInsert["proNo"] = $newProNo;
        $dataInsert["pmStatus"] = 0;
//            $dataInsert["ppIntroText"] = $ppIntroTextArr;
        $pm->insert2($dataInsert);
		//
*/

		$success="新增成功";
        $errMsg='';
	}
	$result = array("success"=>$success,"errMsg"=>$errMsg,"procaseno"=>$newProNo);
	echo json_encode($result,true);

?>
