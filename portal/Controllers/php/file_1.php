<?php
session_start();
// 关闭错误报告
error_reporting(0);
include('../../model/php_model.php');

$or = new Orders();
$action = $_GET['act'];

if($_SESSION['ord_code'] != "" && isset($_SESSION['shopping_user'][0])){
	if($action=='delimg'){ //删除图片 
		$filename = $_POST['imagename']; 
		if(!empty($filename)){ 
			unlink('../../../admin/file/'.$_SESSION['shopping_user'][0]['memNo']."/".$filename);
			$or->updateorAppAuthenIdImgBot('',$_SESSION['ord_code']);
			echo '1'; 
		}else{ 
			echo '删除失败.'; 
		} 
	}else{ //
		$picname = $_FILES['mypic_1']['name']; 
		$picsize = $_FILES['mypic_1']['size']; 
		if(!is_dir('../../../admin/file/'.$_SESSION['shopping_user'][0]['memNo'])){
			mkdir('../../../admin/file/'.$_SESSION['shopping_user'][0]['memNo']);
			chmod('../../../admin/file/'.$_SESSION['shopping_user'][0]['memNo'],0777);
		} 
		
		$File = new File();
		$SystemDirPath = '../../../admin/file/'.$_SESSION['shopping_user'][0]['memNo']."/";
		$rand = rand(100, 999); 
		$Default_file_name = date("YmdHis");
		if ($File->FileCheck($_FILES['mypic_1']['tmp_name'],
						$_FILES['mypic_1']['type'],
						$_FILES['mypic_1']['size'],
						$_FILES['mypic_1']['error'],
						$SystemDirPath,
						$_FILES['mypic_1']['name'])){			
			$type = strstr($picname, '.');
			$FileName = $Default_file_name.$type;
			$aa = $File->SaveImageThumbnail($_FILES['mypic_1']['type'],$_FILES['mypic_1']['tmp_name'],$SystemDirPath,$FileName,'900','600');
			$FileName1 = $SystemDirPath.$FileName;
			$or->updateorAppAuthenIdImgBot(substr($FileName1,3),$_SESSION['ord_code']);
		}
		
		if(!is_file($FileName1)){
			$FileName = '';
		}
		
		$size = round($picsize/1024,2); //转换成kb 
		$arr = array( 
			'name'=>$picname, 
			'pic'=>$FileName,
            'size'=>$size,
            'status' => 1
        );
        echo json_encode($arr); //输出json数据
    }
}else{
    $arr = array(
        'message'=> "no find SESSION, 編號遺失，請重新操作",
        'status' => 0
    );
    echo json_encode($arr); //输出json数据
}