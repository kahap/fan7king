<?php
    //設定密碼
    
    //確認手機號碼、帳號
    $phoneNumber = $_POST['phoneNumber'];
    $password = $_POST['password'];
    
    $sql = "UPDATE member SET memPwd='".$password."' WHERE memPhone='".$phoneNumber."' ";
    $result = $api->customSql($sql);    

    //返回修改狀態
    print_r($result);
?>