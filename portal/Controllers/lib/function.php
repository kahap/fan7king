<?php

	//擷取出網域名稱
	function get_domain_name($ip)
	{
		preg_match('/http:\/\/(.*?)\//is',$ip,$match);
		if(strpos($match[1],DOMAIN) !== false){
			return DOMAIN;
		}else{
			return 'error';
		}
		//return $match[1];
		//return 'newa1-3.com';
	}


	function get_client_ip()
    {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
			$ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}
	
	
	//取得所有資料夾裡面的圖片
	function getAllImgs()
    {
		$result = array();
		if(file_exists(iconv("utf-8","utf-8","images/product/"))){
// 		if(file_exists(iconv("utf-8","big5","images/product/"))){
			$arr = scandir("images/product/");
			
			foreach($arr as &$value){
				if($value != "." && $value != ".."){
					$imgArr = array();
					$arrIn = scandir("images/product/".$value);
					foreach($arrIn as &$valueIn){
						if($valueIn != "." && $valueIn != ".."){
							array_push($imgArr,$valueIn);
						}
					}
					$value = iconv("utf-8", "utf-8", $value);
// 					$value = iconv("big5", "utf-8", $value);
					$result[$value] = $imgArr;
				}
			}
		}
		return $result;
	}


	//圖片上傳+驗證
	function uploadImg($editOrInsert,$oldData,$isAllowNull,$folderName,$columnName)
    {
		//Stores the filename as it was on the client computer.
		date_default_timezone_set('Asia/Taipei');
		$errMsg = "";
		$imagename = date("Ymdhis",time());
		//Stores the filetype e.g image/jpeg
		$imagetype = $_FILES[$columnName]['type'];
		//Stores the tempname as it is given by the host when uploaded.
		$imagetemp = $_FILES[$columnName]['tmp_name'];
		//The path you wish to upload the image to
		$imagePath = "../../images/".$folderName."/";
		
		//判斷是否有檔案
		if($_FILES[$columnName]['error'] != 4) {
			$allowedExts = array("jpeg", "jpg", "png");
			$tmp = explode(".", $_FILES[$columnName]["name"]);
			$extension = end($tmp);
			//判斷資料型態是否正確
			if ((($imagetype != "image/jpeg")
					&& ($imagetype != "image/jpg")
					&& ($imagetype != "image/png"))
					|| !in_array($extension, $allowedExts)){
				$errMsg = "圖片格式只接受jpg,jpeg,png。";
			}else{
				if(is_uploaded_file($imagetemp)) {
// 					if (!file_exists(iconv("utf-8","big5",$imagePath))) {
// 						mkdir(iconv("utf-8","big5",$imagePath),0777,true);
// 					}
// 					if(move_uploaded_file($imagetemp, $imagePath.$imagename.".".$extension)) {
// 						$_POST[$columnName] = "images/".$folderName."/".$imagename.".".$extension;
// 					}else{
// 						$errMsg = "上傳失敗";
// 					}
					if (!file_exists(iconv("utf-8","utf-8",$imagePath))) {
						mkdir(iconv("utf-8","utf-8",$imagePath),0777,true);
					}
					if(move_uploaded_file($imagetemp, $imagePath.$imagename.".".$extension)) {
						$_POST[$columnName] = "images/".$folderName."/".$imagename.".".$extension;
					}else{
						$errMsg = "上傳失敗";
					}
				}else{
					$errMsg = "上傳失敗";
				}
			}
		}else{
			if($editOrInsert == "edit"){
				$_POST[$columnName] = $oldData;
			}else{
				if($isAllowNull){
					$_POST[$columnName] = "";
				}else{
					$errMsg = "必須上傳圖片";
				}
			}
		}
		return $errMsg;
	}


	//上傳多張時
	function uploadMultipleImg($editOrInsert,$oldData,$isAllowNull,$folderName,$columnName)
    {
		$total = count($_FILES[$columnName]['name']);
		$finalData = array();
		$errMsg = "";
		
		for($i=0; $i<$total; $i++) {
			//Stores the filename as it was on the client computer.
			date_default_timezone_set('Asia/Taipei');
			$imagename = date("Ymdhis",time())."-".$i;
			//Stores the filetype e.g image/jpeg
			$imagetype = $_FILES[$columnName]['type'][$i];
			//Stores the tempname as it is given by the host when uploaded.
			$imagetemp = $_FILES[$columnName]['tmp_name'][$i];
			//The path you wish to upload the image to
			$imagePath = "../../images/".$folderName."/";
			
			//判斷是否有檔案
			if($_FILES[$columnName]['error'][$i] != 4) {
				$allowedExts = array("jpeg", "jpg", "png");
				$tmp = explode(".", $_FILES[$columnName]["name"][$i]);
				$extension = end($tmp);
				//判斷資料型態是否正確
				if ((($imagetype != "image/jpeg")
						&& ($imagetype != "image/jpg")
						&& ($imagetype != "image/png"))
						|| !in_array($extension, $allowedExts)){
					$errMsg = "圖片格式只接受jpg,jpeg,png。";
				}else{
					if(is_uploaded_file($imagetemp)) {
// 						if (!file_exists(iconv("utf-8","big5",$imagePath))) {
// 							mkdir(iconv("utf-8","big5",$imagePath),0777,true);
// 						}
// 						if(move_uploaded_file($imagetemp, iconv("utf-8","big5",$imagePath.$imagename.".".$extension))) {
// 							array_push($finalData,"images/".$folderName."/".$imagename.".".$extension);
// 						}else{
// 							$errMsg = "上傳失敗";
// 						}
						if (!file_exists(iconv("utf-8","utf-8",$imagePath))) {
							mkdir(iconv("utf-8","utf-8",$imagePath),0777,true);
						}
						if(move_uploaded_file($imagetemp, iconv("utf-8","utf-8",$imagePath.$imagename.".".$extension))) {
							array_push($finalData,"images/".$folderName."/".$imagename.".".$extension);
						}else{
							$errMsg = "上傳失敗";
						}
					}else{
						$errMsg = "上傳失敗";
					}
				}
			}else{
				if($editOrInsert == "edit"){
					foreach(json_decode($oldData) as $value){
						array_push($finalData,$value);
					}
				}else{
					if($isAllowNull){
						array_push($finalData,"");
					}else{
						$errMsg = "必須上傳圖片";
					}
				}
			}
		}
		$_POST[$columnName] = json_encode($finalData,JSON_UNESCAPED_UNICODE);
		return $errMsg;
	}
	
?>