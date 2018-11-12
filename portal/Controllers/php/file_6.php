<?php
session_start();
// 关闭错误报告
error_reporting(0);
include('../../model/php_model.php');

$or = new Orders();
$action = $_GET['act'];

if($_SESSION['ord_code'] !=""){
    $data = $_POST['imagedata'];
	$filename = date("YmdHis").'sign_1.png';
	//Need to remove the stuff at the beginning of the string
	$data = substr($data, strpos($data, ",")+1);
	$data = base64_decode($data);
	$imgRes = imagecreatefromstring($data);
	if(!is_dir('../../../admin/file/'.$_SESSION['shopping_user'][0]['memNo'])){
			mkdir('../../../admin/file/'.$_SESSION['shopping_user'][0]['memNo']);
			chmod('../../../admin/file/'.$_SESSION['shopping_user'][0]['memNo'],0777);
	}
	  $path = '../../../admin/file/'.$_SESSION['shopping_user'][0]['memNo']."/".$filename;
	  $fp = fopen($path, 'wb' );
	  $or->updateorAppAuthenPromiseLetter(substr($path,3),$_SESSION['ord_code']);
	  imagepng($imgRes, $path);

}

?>