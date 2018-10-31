<?php
	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../model/require_login.php');


$pm = new Product_Manage();
///////////////////////////////////// 舊後台新增商品 //////////////////////////////////////////////////////
		$prod = new Product();
		$cat = new Category();
		$bra = new Brand();
		$catData = array();

		//原始資料
		$origData = $prod->getOneProByNo($_POST["proNo"]);

		$newProNo = $origData[0]["proCaseNo"];
		$last3Num = "";


//原始資料
$origData = $pm->getAllByProNameAndGroup($_POST["proNo"]);

$origDataNoGroup = $pm->getAllByProName($_POST["proNo"]);
///////////////////////////////////// //////////////////////////////////////////////////////

//若空白就輸入原始資料
//foreach($_POST as $key=>$value){
// 		if(trim($value) == ""){
// 			$_POST[$key] = trim($origData[0][$key]);
// 		}
//    $$key = trim($value);
//}

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


if(!isset($_POST["proNo"]) || trim($proNo) == ""){
    $errMsg["proNoErr"] = "請輸入商品名稱";
}

/* 	前端沒有，這裡沒有用

if(!isset($pmPeriodAmnt) || $pmPeriodAmnt != "" && !is_numeric($pmPeriodAmnt)){
    $errMsg["pmPeriodAmntErr"] = "請填入數字";
}

if(!isset($pmDirectAmnt) || $pmDirectAmnt != "" && !is_numeric($pmDirectAmnt)){
    $errMsg["pmDirectAmntErr"] = "請填入數字";
}

if(!isset($pmPopular) || $pmPopular != "" && !is_numeric($pmPopular)){
    $errMsg["pmPopularErr"] = "請填入數字";
}

if(!isset($pmUpDate) || $pmUpDate != "" && !preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/',$pmUpDate)){
    $errMsg["pmUpDateErr"] = "日期格式錯誤";
}
*/

if(isset($pmUpDate) && $pmUpDate != $origData[0]["pmUpDate"]){
    $_POST["newDate"] = $pmUpDate;
}


if(!isset($_POST["catNo"]) || $catNo == ""){
    $errMsg["catErr"] = "請選擇所屬分類";
}

if(!isset($_POST["braNo"]) || $braNo == ""){
    $errMsg["braErr"] = "請選擇所屬品牌";
}

if(!isset($_POST["biNo"]) || $biNo == ""){
    $errMsg["biNoErr"] = "請選擇所屬品項";
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
			if((trim($_POST["catNo"]) != "" && trim($_POST["braNo"]) != "") && (trim($_POST["catNo"]) != $origData[0]["catNo"] || trim($_POST["braNo"]) != $origData[0]["braNo"])){
				//最後一筆
				$lastData = $prod->getAllProDescWithCatAndBra($braNo, $catNo);
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



	//如果沒有錯誤訊息
	if(empty(array_filter($errMsg))){
        $dt = new DateTime();
        $pmUpDate =$dt->format('Y-m-d H:i:s');

        $dataS = array();
        $dataS["proName"] = $proNo;
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
        $prod->update($dataS,$newProNo,$proNo);



        //商品利率
        $pp = new Product_Period();
        $ppPeriodAmountArr = isset($_POST["ppPeriodAmount"])? $_POST["ppPeriodAmount"] : [];
        $ppPercentArr = isset($_POST["ppPercent1"])? $_POST["ppPercent1"] : [];
        $ppIntroTextArr = isset($_POST["ppIntroTextArr"])? $_POST["ppIntroTextArr"] : [];
        $proNo = isset($_POST["proNo"])? $_POST['proNo'] : 0;


        $ppData = $pp->getPPByProduct($proNo);
        if($ppData != null){
            foreach($ppPeriodAmountArr as $key=>$value){
                $ppDataEach = $pp->getPPByProductAndPeriodsAmount($proNo,$value);
                $dataInsert = array();
                $dataInsert["ppNo"] = isset($ppDataEach[0]["ppNo"])? $ppDataEach[0]["ppNo"] : '';
                $dataInsert["ppPeriodAmount"] = $value;
                $dataInsert["ppPercent"] = isset($ppPercentArr[$key])? $ppPercentArr[$key] : '';
                $dataInsert["ppIntroText"] = isset($ppIntroTextArr[$key])? $ppIntroTextArr[$key] : '';
                $pp->update($dataInsert);
            }
        }else{
            foreach($ppPeriodAmountArr as $key=>$value){
                $dataInsert = array();
                $dataInsert["proNo"] = $proNo;
                $dataInsert["ppPeriodAmount"] = $value;
                $dataInsert["ppPercent"] = isset($ppPercentArr[$key])? $ppPercentArr[$key] : '';
                $dataInsert["ppIntroText"] = isset($ppIntroTextArr[$key])? $ppIntroTextArr[$key] : '';
                $pp->insert($dataInsert);
            }
        }

        unset($_POST["ppPeriodAmount"]);
		unset($_POST["ppPercent"]);
		$update = $pm->update($_POST,$_POST["proNo"]);
		$success="更新成功";
	}
	
	$result = array("success"=>$success,"errMsg"=>$errMsg);
	echo json_encode($result);

?>
