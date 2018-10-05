<?php
    
    require_once('../../model/require_login.php');
    
    $sup = new Supplier_sales();
    
    //將傳入參數去除空白
    $ssName = trim($_POST["ssName"]);
    $ssLogId = trim($_POST["ssLogId"]);
    $ssPwd = trim($_POST["ssPwd"]);
    
    //錯誤訊息
    $errMsg = array();
    //成功訊息
    $success = "";

    //判斷參數是否為空
    if (empty($ssName)) {
        $errMsg["ssNameErr"] = "'業務人員姓名'不可為空";
    }   
    if (empty($ssLogId)) {
        $errMsg["ssLogIdErr"] = "'業務人員登入帳號'不可為空";
    }
    if (empty($ssPwd)) {
        $errMsg["ssPwdErr"] = "'業務人員登入密碼'不可為空";
    }
    if (!empty($ssLogId) && !empty($ssPwd)) {
        $supData = $sup->getSupplier_sales_num($ssLogId);
        if ($supData[0]['num'] > 0) {
            $errMsg["errMsg"] = "'此帳號已經有其他人使用'";
        }
    }
    //如果沒有錯誤訊息
    if (empty(array_filter($errMsg))) {
        $update = $sup->update($_POST, $_POST["ssNo"]);
        $success = "更新成功";
    }
    
    $result = array("success" => $success, "errMsg" => $errMsg);
    echo json_encode($result);
    exit;
?>
