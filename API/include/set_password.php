<?php
    //設定密碼
    
    //確認手機號碼、帳號
    foreach ($_POST as $key => $value) {
        $$key = $value;
    }
    
    if (isset($token) ) {
        $mem = new Member();
        $memData = $mem->check_FBtoken($token);
        if(empty($memData)){            
            $api->setInformation(FALSE, 0, 0, "搜尋失敗");
        }else{
            $addsql="";
            if (isset($memClass)) {
                $addsql=", memClass='".$memClass."' ";
            }
            $sql = "UPDATE member SET memPwd='".$password."' ".$addsql." WHERE memNo='".$memData['memNo']."' ";
            $result = $api->customSql($sql);
            $api->setInformation(TRUE, 1, 1, "成功修改！");    
        }
    }else{
        $api->setWhereArray(array("memPhone"=>$phoneNumber));    
        $result=$api->getWithWhereAndJoinClause();
        if (($result['records'])=="0") {
            $api->setInformation(FALSE, 0, 0, "搜尋失敗");
        }else{
            $sql = "UPDATE member SET memPwd='".$password."' WHERE memPhone='".$phoneNumber."' ";
            $api->customSql($sql);
            $api->setInformation(TRUE, 1, 1, "成功修改！");
        }
        
    }
    
    //返回修改狀態
    $api->setResult();
?>