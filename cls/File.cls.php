<?php
class File
{
	//允許上傳的檔名
	var $allowedExts = array("jpg","jpeg","gif","png","bmp","flv","doc","docx","ppt","pps","pdf","htm","html","txt","mpeg","mpg","mp3","mp4","wav","avi","wmv","mov","zip","rar","xlsx");
	//允許上傳的檔案類型
	var $allowType = array("image/gif", "image/jpeg", "image/png", "image/pjpeg");
	//允許上傳的檔案大小
	var $filesizeS = '60000000';
	//錯誤訊息
	var $errorstring;
	
	public function FileCheck($tmp_name,$type,$size,$error,$path,$name){
		$return_boolean = false;
		$allowedExts = $this->allowedExts;
		$allowType = $this->allowType;
		$filesize = $this->filesizeS;
		
		$extension = strtolower(end(explode(".", $name))); //副檔名
		if(in_array($extension, $allowedExts)){
			if($size < $filesize){
				if ($error == '0'){
					if (file_exists($path . $name)){
						//$ReturnStr =  "Already Exists";
						$return_boolean = false;
						$this->errorstring = '檔名重複';
					}else{
						$return_boolean = true;
						$this->errorstring = '';
					}
				}else{
					//$ReturnStr = GetFileError($files["error"]);
					$this->errorstring = $this->GetFileError($error);
					$return_boolean = false;
				}
			}else{
				//$ReturnStr = "檔案大小超過系統限制";
				$this->errorstring = '檔案大小超過系統限制';
				$return_boolean = false;
			}
		}else{
			//$ReturnStr = "副檔名不允許";
			$this->errorstring = '副檔名不允許';
			$return_boolean = false;
		}
		return $return_boolean;
	}
	
	public function Geterrorstring(){
		return $this->errorstring;
	}
	
	public function SaveFile($tmp_name,$path,$name){
		copy($tmp_name,iconv("utf-8", "big5" , $path.$name));
		return $path.$name;
	}
	
		
	public function SaveImageThumbnail($type,$tmp_name,$path,$name,$width,$height){
		switch($type){
			case 'image/png':
				$path = $this->SaveImageThumbnailPng($tmp_name,$path,$name,$width,$height);
			break;
			case 'image/pjpeg':
				$path = $this->SaveImageThumbnailJpg($tmp_name,$path,$name,$width,$height);			
			break;
			case 'image/jpeg':
				$path = $this->SaveImageThumbnailJpg($tmp_name,$path,$name,$width,$height);			
			break;
			case 'image/gif':
				$path = $this->SaveImageThumbnailGif($tmp_name,$path,$name,$width,$height);			
			break;
			
			default:
				$path = $this->SaveImageThumbnailJpg($tmp_name,$path,$name,$width,$height);
			break;
		}
		return $path;
	}
	
	private function SaveImageThumbnailPng($tmp_name,$path,$name,$width,$height){
		$src = imagecreatefrompng($tmp_name);
		// 取得來源圖片長寬
		$src_w = imagesx($src);
		$src_h = imagesy($src);		
		$size = $this->GetSize($src_w,$src_h,$width,$height);		
		$thumb_w = $size['width'];
		$thumb_h = $size['height'];		
		//建立縮圖空白畫面
		$thumb = imagecreatetruecolor($thumb_w, $thumb_h);
		imagealphablending($thumb,false);
		imagesavealpha($thumb,true);
		//開始縮圖
		imagecopyresampled($thumb, $src, 0, 0, 0, 0, $thumb_w, $thumb_h, $src_w, $src_h);
		//儲存縮圖到指定 thumb 目錄
		imagepng($thumb, iconv("utf-8", "big5" , $path.$name));
		//銷毀圖形
		imagedestroy($src);
		return $path.$name;
	}
	
	private function SaveImageThumbnailJpg($tmp_name,$path,$name,$width,$height){
		$src = imagecreatefromjpeg($tmp_name);
		// 取得來源圖片長寬
		$src_w = imagesx($src);
		$src_h = imagesy($src);		
		$size = $this->GetSize($src_w,$src_h,$width,$height);		
		$thumb_w = $size['width'];
		$thumb_h = $size['height'];		
		//建立縮圖空白畫面
		$thumb = imagecreatetruecolor($thumb_w, $thumb_h);
		imagealphablending($thumb,false);
		imagesavealpha($thumb,true);
		//開始縮圖
		imagecopyresampled($thumb, $src, 0, 0, 0, 0, $thumb_w, $thumb_h, $src_w, $src_h);
		//儲存縮圖到指定 thumb 目錄
		imagejpeg($thumb, iconv("utf-8", "big5" , $path.$name));
		//銷毀圖形
		imagedestroy($src);
		return $path.$name;
	}
	
	private function SaveImageThumbnailGif($tmp_name,$path,$name,$width,$height){
		$src = imagecreatefromgif($tmp_name);
		// 取得來源圖片長寬
		$src_w = imagesx($src);
		$src_h = imagesy($src);		
		$size = $this->GetSize($src_w,$src_h,$width,$height);		
		$thumb_w = $size['width'];
		$thumb_h = $size['height'];		
		//建立縮圖空白畫面
		$thumb = imagecreatetruecolor($thumb_w, $thumb_h);
		imagealphablending($thumb,false);
		imagesavealpha($thumb,true);
		//開始縮圖
		imagecopyresampled($thumb, $src, 0, 0, 0, 0, $thumb_w, $thumb_h, $src_w, $src_h);
		//儲存縮圖到指定 thumb 目錄
		imagegif($thumb, iconv("utf-8", "big5" , $path.$name));
		//銷毀圖形
		imagedestroy($src);
		return $path.$name;
	}
	
	//輸入 原始圖的寬高 跟 預期的寬跟高 返回 預期寬跟高內等比例的寬高   (同等比例縮放，縮圖用)
	private function GetSize($width,$height,$after_width,$after_height){
		// 105  159
		//$default_width = 105;
		//$default_height = 159;
		//while ($width > $default_width or $height > $default_height){
			
		while ($width > $after_width or $height > $after_height){
			$width = intval($width*0.9);
			$height = intval($height*0.9);
		}
		return array('width'=>$width,'height'=>$height);
	}	
	
	private function GetFileError($error_number){
		switch($error_number){
			case 1:
				// 檔案大小超出了伺服器上傳限制 UPLOAD_ERR_INI_SIZE
				$str="The file is too large (server).";
				break;
			case 2:
				// 要上傳的檔案大小超出瀏覽器限制 UPLOAD_ERR_FORM_SIZE
				$str="The file is too large (form).";
				break;    
			case 3:
				//檔案僅部分被上傳 UPLOAD_ERR_PARTIAL
				$str="The file was only partially uploaded.";
				break;
			case 4:
				//沒有找到要上傳的檔案 UPLOAD_ERR_NO_FILE
				$str="No file was uploaded.";
				break;
			case 5:
				//伺服器臨時檔案遺失
				$str="The servers temporary folder is missing.";
				break;
			case 6:
				//檔案寫入到站存資料夾錯誤 UPLOAD_ERR_NO_TMP_DIR
				$str="Failed to write to the temporary folder.";
				break;
			case 7:
				//無法寫入硬碟 UPLOAD_ERR_CANT_WRITE
				$str="Failed to write file to disk.";
				break;
			case 8:
				//UPLOAD_ERR_EXTENSION
				$str="File upload stopped by extension.";
				break;
		}
		return $str;
	}
	
}
?>