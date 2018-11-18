<?php
    //設定密碼
    
    //確認手機號碼、帳號
    foreach ($_POST as $key => $value) {
        $$key = $value;
    }
    
    if (isset($token)) {
        $app_data = new API("app_data");
        $app_data->setWhereArray(array("adTokenId"=>$token));    
        $app_data->getWithWhereAndJoinClause();
        $apData = $app_data->getData();
        $memNo = $apData[0]["memNo"];
        if ($memNo=="") {
            $api->setInformation(FALSE, 0, 0, "搜尋失敗");
        }else{
            $addsql="";
            if (isset($memClass)) {
                $addsql=", memClass='".$memClass."' ";
            }
            $sql = "UPDATE member SET memPwd='".$password."' ".$addsql." WHERE memNo='".$memNo."' ";
            $api->customSql($sql);
            $api->setInformation(TRUE, 1, 1, "成功修改！");    
        }
    }else{
        $forSave = new API("member");
        $forSave->setWhereArray(array("memCell"=>$_POST["memCell"]));
        $forSave->getWithWhereAndJoinClause();
        $apData = $forSave->getData();
        $memNo = $apData[0]["memNo"];
        if ($memNo=="") {
            $api->setInformation(FALSE, 0, 0, "搜尋失敗");            
        }else{            
            $sql = "UPDATE member SET memPwd='".$password."' WHERE memCell='".$memCell."' ";
            $api->customSql($sql);
            $api->setInformation(TRUE, 1, 1, "成功修改！");
        }        
    }    
    //返回修改狀態
    $api->setResult();
?>