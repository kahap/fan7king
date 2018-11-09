<?php
    //設定密碼
    
    //確認手機號碼、帳號
    foreach ($_POST as $key => $value) {
        $$key = $value;
    }
    

    if ($type="regist") {    
        $app_data = new API("app_data");
        $app_data->setWhereArray(array("adTokenId"=>$token));    
        $app_data->getWithWhereAndJoinClause();
        $apData = $app_data->getData();
        $memNo = $apData[0]["memNo"];    
        $sql = "UPDATE member SET memPwd='".$password."' , memCell='".$memCell."' WHERE memNo='".$memNo."' ";
        $api->customSql($sql);
        $api->setInformation(TRUE, 1, 1, "成功修改！");
    }else if($type=="forget"){
        $sql = "UPDATE member SET memPwd='".$password."' WHERE memCell='".$memCell."' ";
        $api->customSql($sql);
        $api->setInformation(TRUE, 1, 1, "成功修改！");
    }else if($type=="change"){
        $api->setWhereArray(array("memNo"=>$memNo,"memPwd"=>$passwordOld));    
        $api->getWithWhereAndJoinClause();
        $result = $api->getData();
        if (count($result)==0) {
            $api->setInformation(FALSE, 1, 1, "原密碼錯誤");
        }else{
            $sql = "UPDATE member SET memPwd='".$password."' WHERE memNo='".$memNo."' and  memPwd='".$passwordOld."' ";
            $api->customSql($sql);
            $api->setInformation(TRUE, 1, 1, "成功修改！");
        }        
    }
    //返回修改狀態
    $api->setResult();
?>