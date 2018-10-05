<?php
    require_once('../../model/require_login.php');
    
    $sup = new Supplier_sales();
    $supNo = $_POST["supNo"];

    //錯誤訊息
    $errMsg = array();

    //成功訊息
    $success = "";
    
    $delete = $sup->delete($supNo);
    if ($delete) {
        $success = "0";
    } else {
        $success = "1";
    }
    $result = array("success" => $success,"errMsg" => $errMsg);
    echo json_encode($result);
    exit;
?>