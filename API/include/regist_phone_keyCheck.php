<?php
    //驗證手機驗證碼
    
    //接收驗證碼
    $phoneNumber = $_POST['phoneNumber'];
    $passNumber = $_POST['passNumber'];
    $token = $_POST['token'];

    //比對資料庫
    $api->getOne($phoneNumber);
    $data = $api -> getData();

    //返回比對結果
    if ($data[0]['passNumber'] == $passNumber) {
        $api->setInformation(TRUE, 1, 1, "驗證成功");        
    }else{
        $api->setInformation(FALSE, 1, 1, "驗證失敗");        
    }
    $api->setResult();
?>