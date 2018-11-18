<?php
    //設定密碼
    
    //確認手機號碼、帳號
    foreach ($_POST as $key => $value) {
        $$key = $value;
    }
    $app_data = new API("app_data");
    $app_data->setWhereArray(array("adTokenId"=>$token));    
    $app_data->getWithWhereAndJoinClause();
    $apData = $app_data->getData();
    $memNo = $apData[0]["memNo"];    
    $Cpwd = password_hash($password,PASSWORD_DEFAULT);
    

    if ($type=="regist") {
        
        $sql = "UPDATE member SET memClass='".$memClass."', memPwd='".$Cpwd."' , memCell='".$memCell."' WHERE memNo='".$memNo."' ";
        $api->customSql($sql);
        $api->setInformation(TRUE, 1, 1, "密碼設定成功");
    }else if($type=="WebRegist"){
        $member = new API("member");
        $member->setWhereArray(array("memFBtoken"=>$token));    
        $member->getWithWhereAndJoinClause();
        $apData = $member->getData();
        $memNo = $apData[0]["memNo"];    
        if ($memNo!=""){
            $sql = "UPDATE member SET memClass='".$memClass."', memPwd='".$Cpwd."' , memCell='".$memCell."' WHERE memNo='".$memNo."' ";
            $api->customSql($sql);
            $api->setInformation(TRUE, 1, 1, "密碼設定成功");
        }
        
    }else if($type=="forget"){
        $sql = "UPDATE member SET memPwd='".$Cpwd."' WHERE memCell='".$memCell."' ";
        $api->customSql($sql);
        $api->setInformation(TRUE, 1, 1, "密碼設定成功");

    }else if($type=="change"){
        $api->setWhereArray(array("memNo"=>$memNo));
        $api->getWithWhereAndJoinClause();
        $result = $api->getData();
        
        if (password_verify($oldpassword,$result[0]['memPwd'])) {
            $sql = "UPDATE member SET memPwd='".$Cpwd."' WHERE memNo='".$memNo."' ";
            $api->customSql($sql);
            $api->setInformation(TRUE, 1, 1, "密碼修改成功");
        }else{
            $api->setInformation(FALSE, 1, 1, "原密碼錯誤");
        }     
    }
    //返回修改狀態
    $api->setResult();
?>