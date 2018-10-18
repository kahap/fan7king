<?php
//一般註冊
$mem = new Member();
$or = new Orders();
$mco = new API("motorbike_cellphone_orders");

$orColumns = $or->getAllColumnNames("motorbike_cellphone_orders");
foreach($orColumns as $key => $value){
	$orColumnsArr[$value['COLUMN_NAME']] = $value['COLUMN_COMMENT'];
}

if(isset($_POST["mcoNo"]) && !empty($_POST["mcoNo"])){

	$mco->getOne($_POST["mcoNo"]);
	$mcoData = $mco->getData();
	$memData = $mem->getOneMemberByNo($mcoData[0]["memNo"]);

	//必填欄位
	if($mcoData[0]["mcoType"] == "1"){
		$mustFill = array(
			"mcoIdIssueYear","mcoIdIssueMonth","mcoIdIssueDay","mcoIdIssuePlace","mcoIdIssueType",
			"mcoIdImgTop","mcoIdImgBot","mcoStudentIdImgTop","mcoStudentIdImgBot","mcoBankBookImgTop"
		);
	}else{
		if($memData[0]["memClass"] == 0){
			$mustFill = array(
				"mcoIdIssueYear","mcoIdIssueMonth","mcoIdIssueDay","mcoIdIssuePlace","mcoIdIssueType",
				"mcoIdImgTop","mcoIdImgBot","mcoStudentIdImgTop","mcoStudentIdImgBot","mcoSubIdImgTop","mcoCarIdImgTop","mcoBankBookImgTop"
			);
		}else{
			$mustFill = array(
				"mcoIdIssueYear","mcoIdIssueMonth","mcoIdIssueDay","mcoIdIssuePlace","mcoIdIssueType",
				"mcoIdImgTop","mcoIdImgBot","mcoSubIdImgTop","mcoCarIdImgTop","mcoBankBookImgTop"
			);
		}
	}
	//圖片的欄位
	$mcoInputColumns = array(
			"mcoIdIssueYear","mcoIdIssueMonth","mcoIdIssueDay","mcoIdIssuePlace","mcoIdIssueType",
	);
	$imgType = array(
			"mcoIdImgTop","mcoIdImgBot","mcoStudentIdImgTop","mcoStudentIdImgBot",
			"mcoSubIdImgTop","mcoCarIdImgTop","mcoBankBookImgTop","mcoRecentTransactionImgTop",
			"mcoExtraInfoUpload"
	);

	//檢查是否有空白
	foreach($mustFill as $key=>$value){
		if(!isset($_POST[$value]) || trim($_POST[$value]) == ""){
			$errMsg[] = "必須上傳或填寫".$orColumnsArr[$value];
		}
	}

	//若沒錯誤的話預設其他欄位
	if(!isset($errMsg)){
		//檔案上傳部分
		//檢查有無資料夾並放置檔案
		$memNo = $memData[0]["memNo"];
		if(!is_dir('../admin/file/'.$memNo)){
			mkdir('../admin/file/'.$memNo);
			chmod('../admin/file/'.$memNo,0777);
		}
		//base64轉換
		foreach($imgType as $key=>$value){
			if(!empty($_POST[$value])){
				if($value == "mcoExtraInfoUpload"){
					$srcArr = $_POST[$value];
					$pathArr = array();
					foreach($srcArr as $keyIn=>$eachPic){
						$data = base64_decode($eachPic);
						file_put_contents('../admin/file/'.$memNo.'/'.date("YmdHis").'-'.$key.'-'.$keyIn.'.jpg', $data);
						$pathArr[] = 'admin/file/'.$memNo.'/'.date("YmdHis").'-'.$key.'-'.$keyIn.'.jpg';
					}
					$_POST[$value] = json_encode($pathArr);
				}else{
					$data = base64_decode($_POST[$value]);
					file_put_contents('../admin/file/'.$memNo.'/'.date("YmdHis").'-'.$key.'.jpg', $data);
					$_POST[$value] = 'admin/file/'.$memNo.'/'.date("YmdHis").'-'.$key.'.jpg';
				}
			}
		}
		//去除不必要的資料
		foreach($_POST as $key=>$value){
			if(!in_array($key,$imgType) && !in_array($key,$mcoInputColumns)){
				unset($_POST[$key]);
			}
		}
		
		$api->update($_POST, $mcoData[0]["mcoNo"]);
		$api->setInformation(array("mcoNo"=>$mcoData[0]["mcoNo"]), 1, 1, "身分證資訊與相關證件上傳成功。");
	}else{
		$api->setInformation(false, 0, 0, $errMsg);
	}
}else{
	$api->setInformation(false, 0, 0, "請帶入訂單編號");
}
$api->setResult();

?>