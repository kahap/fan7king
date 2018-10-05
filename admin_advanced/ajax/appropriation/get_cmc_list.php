<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

$allowFile = array();
$date = str_replace("-","",$_POST["date"]);
$pluginPath = '../../appro_file/'.$date."/";
if(file_exists($pluginPath)){
	$plugins = new DirectoryIterator($pluginPath);
	foreach($plugins as $fileinfo){
		//isDot => 判斷是否是上層連結，如果是的話跳過
		//isDir => 判斷是否是資料夾
		if(!$fileinfo->isDir() && $fileinfo->isFile()){
			$filePath = $fileinfo->getFilename();
			$fileExt = end((explode('.',$filePath)));
			$allowType = array("xml");
			if(in_array($fileExt,$allowType)){
				$allowFile[] = $filePath;
			}
		}
	}
}

$outputData = array();
if(!empty(array_filter($allowFile))){
	foreach($allowFile as $key=>$value){
		$outputData[$key]["key"] = $key+1;
		$outputData[$key]["path"] = "/admin_advanced/appro_file/".$date."/";
		$outputData[$key]["filename"] = $value;
		$outputData[$key]["download"] = '<a download href="appro_file/'.$date.'/'.$value.'">下載</a>';
	}
}

echo json_encode($outputData,JSON_UNESCAPED_UNICODE);

?>