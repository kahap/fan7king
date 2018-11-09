<?php
    //儲存手機驗證碼
    
    //4碼亂數
    $passNumber = rand(1000,9999);
    $phoneNumber = $_POST['phoneNumber'];
    $regtime = $_POST['time'];
    $token = $_POST['token'];
    $type = $_POST['type'];
    $cangodome = TRUE;
    $app_data = new API("app_data");
    $member = new API("member");
    $member->setWhereArray(array("memCell"=>$phoneNumber));
    $member->getWithWhereAndJoinClause();
    $data = $member->getData();

    if ($type=="regist") {
        if (count($data)!=0) {
            $api->setInformation(FALSE, 1, 1, "此手機已被註冊");       
            $cangodome = false;     
        }
    }

    if ($cangodome) {
        //存到驗證資料表
        $api->getOne($phoneNumber);
        $data = $api -> getData();
        $updata = array(
            "phoneNumber"=>$phoneNumber,
            "passNumber"=>$passNumber,
            "dateTime"=>$regtime
        );
        if (count($data)!=0) {        
            $api->update($updata,$phoneNumber);
        }else{
            $api->insert($updata);
        }
        $api->getOne($phoneNumber);
        $data = $api -> getData();
        
        //發送訊息

        $sms = new SMSHttp();
        
        $subject = "發送驗證碼";	//簡訊主旨，主旨不會隨著簡訊內容發送出去。用以註記本次發送之用途。可傳入空字串。
        $content = "Nowait驗證碼:".$passNumber;	//簡訊內容
        $mobile = $phoneNumber;	//接收人之手機號碼。格式為: +886912345678或09123456789。多筆接收人時，請以半形逗點隔開( , )，如0912345678,0922333444。
        $sendTime= "";		//簡訊預定發送時間。-立即發送：請傳入空字串。-預約發送：請傳入預計發送時間，若傳送時間小於系統接單時間，將不予傳送。格式為YYYYMMDDhhmnss；例如:預約2009/01/31 15:30:00發送，則傳入20090131153000。若傳遞時間已逾現在之時間，將立即發送。
        
        //取餘額
        if($sms->getCredit()){
            echo "取得餘額成功，餘額為：" . $sms->credit ;
        } else {
            echo "取得餘額失敗，" . $sms->processMsg ;
        }
        
        //傳送簡訊
        // if($sms->sendSMS($subject,$content,$mobile,$sendTime)){
        //     $api->setInformation(TRUE, 1, 1, "驗證碼簡訊發送成功");
        // } else {
        //     $api->setInformation(FALSE, 1, 1, "驗證碼簡訊發送失敗");
        // }
    }
    
    $api->setResult();
    
?>


