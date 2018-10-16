<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

session_start();
date_default_timezone_set('Asia/Taipei');

$rcNo = $_POST['rcNo'];
$aaNo = $_SESSION['adminUserData']['aauNo'];

$aaUser = new API("admin_advanced_user");
$User = $aaUser->getOne($aaNo);

$orderLock = new API("orderLock");
$testData = array("rcNo"=>$rcNo);
$orderLock->delete($testData);

$testData = array(
    "rcNo"=>$rcNo,
    "isLock"=>1,
    "ServiceName"=>$User[0]['aauName'],
    "lastDate"=>date("Y-m-d H:i:s",time())
);
$w = $orderLock->insert($testData);
?>