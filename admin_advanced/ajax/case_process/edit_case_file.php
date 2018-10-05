<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');
require_once('../../../cls/File.cls.php');
session_start();
foreach($_FILES as $key=>$value){
	if($value['name'] != ''){
		$pic_name[] = $key;
	}
}
$File = new File();
$apiOr = new API("real_cases");
$rcData = $apiOr->getOne($_POST["rcNo"]);
	if($pic_name !=""){
		foreach($pic_name as $k => $v){
			if($v != 'rcExtraInfoUpload'){
				$picname = $_FILES[$v]['name']; 
				$SystemDirPath = '../../../admin/file/'.$rcData['0']['memNo']."/";
				$rand = rand(100, 999); 
				$Default_file_name = date("YmdHis");
				if ($File->FileCheck($_FILES[$v]['tmp_name'],
								$_FILES[$v]['type'],
								$_FILES[$v]['size'],
								$_FILES[$v]['error'],
								$SystemDirPath,
								$_FILES[$v]['name'])){			
					$type = strstr($picname, '.');
					$FileName = $Default_file_name.$type;
					$aa = $File->SaveImageThumbnail($_FILES[$v]['type'],$_FILES[$v]['tmp_name'],$SystemDirPath,$FileName,'900','600');
					$FileName1 = array($v => 'admin/file/'.$rcData['0']['memNo'].'/'.$FileName);
					$apiOr->update($FileName1,$rcData['0']["rcNo"]);
					
				}
			}else{
				$total = count($_FILES[$v])-1;
				$i = 0;
				for($i=0;$i<=$total;$i++){
					$picname = $_FILES[$v]['name'][$i];
					$SystemDirPath = '../../../admin/file/'.$rcData['0']['memNo']."/";
					$rand = rand(100, 999); 
					$Default_file_name = date("YmdHis");
					if ($File->FileCheck($_FILES[$v]['tmp_name'][$i],
									$_FILES[$v]['type'][$i],
									$_FILES[$v]['size'][$i],
									$_FILES[$v]['error'][$i],
									$SystemDirPath,
									$_FILES[$v]['name'][$i])){			
						$type = strstr($picname, '.');
						$FileName = $Default_file_name.$type;
						$File->SaveImageThumbnail($_FILES[$v]['type'][$i],$_FILES[$v]['tmp_name'][$i],$SystemDirPath,$FileName,'900','600');
						$File_array[] = 'admin/file/'.$rcData['0']['memNo'].'/'.$FileName;
					}
				}
				$FileName1 = array($v => json_encode($File_array,true));
				$apiOr->update($FileName1,$rcData['0']["rcNo"]);
			}
		}
		echo "<script>alert('修改成功');</script>";
		echo "<script>location.href='../../?page=case&type=edit&no=".$rcData['0']["rcNo"]."';</script>";
	}else{
		echo "<script>alert('沒有上傳任何檔案');</script>";
		echo "<script>location.href='../../?page=case&type=edit&no=".$rcData['0']["rcNo"]."';</script>";
	}

?>