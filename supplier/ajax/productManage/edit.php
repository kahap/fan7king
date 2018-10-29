<?php
	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../model/require_login.php');


//若空白就輸入原始資料
foreach($_POST as $key=>$value){
// 		if(trim($value) == ""){
// 			$_POST[$key] = trim($origData[0][$key]);
// 		}
    $$key = trim($value);
}

$pm = new Product_Manage();

///////////////////////////////////// 舊後台新增商品 //////////////////////////////////////////////////////
			$pro = new Product();
			$cat = new Category();
			$bra = new Brand();
			$catData = array();


			//原始資料
			$origData = $pro->getOneProByNo($_POST["proNo"]);


			$newProNo = $origData[0]["proCaseNo"];
			$last3Num = "";

			//取得商品編號
			if((trim($_POST["catNo"]) != "" && trim($_POST["braNo"]) != "") && (trim($_POST["catNo"]) != $origData[0]["catNo"] || trim($_POST["braNo"]) != $origData[0]["braNo"])){
				//最後一筆
				$lastData = $pro->getAllProDescWithCatAndBra($braNo, $catNo);
				if($lastData != null){
					if($lastData[0]["proCaseNo"] <>"")
					{
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
					}
					else
					{
						$last3Num = "001";
					}
				}else{
					$last3Num = "001";
				}
				$newProNo = $catNo.$braNo.$last3Num;
			}


			//錯誤訊息
			$errMsg = array();
			//成功訊息
			$success="";
			//	$errMsg["supStampImgErr"] = "";

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


			//圖片上傳
			if($_POST["imgMethod"] == "album"){
				$imgArr = $_POST["album"] == "" ? array() : explode(",", $_POST["album"]);
				$origArr = json_decode($origData[0]["proImage"],true);
				foreach($origArr as $value){
					array_unshift($imgArr,$value);
				}
				if(isset($_FILES["single"])){
					//新增照片
					if(count($_FILES['proImage'])){
						$err = uploadMultipleImg("insert", "", true, "product/".$catName, 'single');
					}
					if($err != ""){
						$errMsg["supStampImgErr"] = $err;
					}
					foreach(json_decode($_POST["single"],true) as $value){
						array_push($imgArr, $value);
					}
				}
				$imgArr = array_values(array_filter($imgArr));
				$_POST["proImage"] = json_encode($imgArr,JSON_UNESCAPED_UNICODE);
			}else{
				$err = uploadMultipleImg("edit", $origData[0]["proImage"], true, "product/".$catName, 'proImage');
				if($err != ""){
					$errMsg["supStampImgErr"] = $err;
				}
			}

			$_POST["proDetail"] = preg_replace("/\r\n|\r|\n/","",nl2br($_POST["proDetail"]));
///////////////////////////////////// //////////////////////////////////////////////////////


//原始資料
	$origData = $pm->getAllByProNameAndGroup($_POST["proNo"]);
	
	$origDataNoGroup = $pm->getAllByProName($_POST["proNo"]);

	//錯誤訊息
	$errMsg = array();
	//成功訊息
	$success="";

	if(!isset($_POST["proNo"]) || trim($proNo) == ""){
		$errMsg["proNoErr"] = "請輸入商品名稱";
	}		
	if($pmPeriodAmnt != "" && !is_numeric($pmPeriodAmnt)){
		$errMsg["pmPeriodAmntErr"] = "請填入數字";
	}
	
	if($pmDirectAmnt != "" && !is_numeric($pmDirectAmnt)){
		$errMsg["pmDirectAmntErr"] = "請填入數字";
	}
	
	if($pmPopular != "" && !is_numeric($pmPopular)){
		$errMsg["pmPopularErr"] = "請填入數字";
	}
	
	if($pmUpDate != "" && !preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/',$pmUpDate)){
		$errMsg["pmUpDateErr"] = "日期格式錯誤";
	}
	if($pmUpDate != $origData[0]["pmUpDate"]){
		$_POST["newDate"] = $pmUpDate;
	}	
	
	//商品利率
	$pp = new Product_Period();
	$ppPeriodAmountArr = $_POST["ppPeriodAmount"];
	$ppPercentArr = $_POST["ppPercent"];
	$ppIntroTextArr = $_POST["ppIntroText"];
	$proNo = $_POST["proNo"];
	
  
	$ppData = $pp->getPPByProduct($proNo);
	if($ppData != null){
		foreach($ppPeriodAmountArr as $key=>$value){
			$ppDataEach = $pp->getPPByProductAndPeriodsAmount($proNo,$value);
			$dataInsert = array();
			$dataInsert["ppNo"] = $ppDataEach[0]["ppNo"];
			$dataInsert["ppPeriodAmount"] = $value;
			$dataInsert["ppPercent"] = $ppPercentArr[$key];
			$dataInsert["ppIntroText"] = $ppIntroTextArr[$key];
			$pp->update($dataInsert);
		}
	}else{
		foreach($ppPeriodAmountArr as $key=>$value){
			$dataInsert = array();
			$dataInsert["proNo"] = $proNo;
			$dataInsert["ppPeriodAmount"] = $value;
			$dataInsert["ppPercent"] = $ppPercentArr[$key];
			$dataInsert["ppIntroText"] = $ppIntroTextArr[$key];
			$pp->insert($dataInsert);
		}
	}
	
	//如果沒有錯誤訊息
	if(empty(array_filter($errMsg))){
		unset($_POST["ppPeriodAmount"]);
		unset($_POST["ppPercent"]);
		$update = $pm->update($_POST,$_POST["proNo"]);
		$success="更新成功";
	}
	
	$result = array("success"=>$success,"errMsg"=>$errMsg);
	echo json_encode($result);

?>
