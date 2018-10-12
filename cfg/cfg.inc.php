<?php

//預設日期
define('DATE_START','2016-01-01');

$AppMode = "Dev" ;//Dev-測試, Prov--正式
/************* 資料庫定義  ****************/
//define ('SYSTEM_DBHOST','35.229.195.99');
//define ('SYSTEM_DBHOST','35.194.183.49:3306');
define ('SYSTEM_DBHOST','35.201.175.22:3306');
define ('SYSTEM_DBNAME','happyfan_system');
define ('SYSTEM_DBUSER','root');
define ('SYSTEM_DBPWD','b7Jl7qzubC635rkg');

if($AppMode !="Dev")
{
    if($AppMode == "Prod")
    {
        define ('DOMAIN','happyfan7.com');
        define('AutoloadAPPId','1557221921249508');
        define('AutoloadAPPSecret','aaf94a1bd83321900f71695a99f458b5');
    }
    else
    {
        define ('DOMAIN','test.happyfan7.com');
        define('AutoloadAPPId','358306487860510');
        define('AutoloadAPPSecret','bbff3360de628c59397f27261daceb02');
    }
    define('FbADVersion','v2.6');
}
else
{
//    define ('DOMAIN','127.0.0.1/happyfan7');
    define ('DOMAIN','127.0.0.1/fan7king_dev2');
    define('AutoloadAPPId','358306487860510');
    define('AutoloadAPPSecret','bbff3360de628c59397f27261daceb02');
    define('FbADVersion','v2.9');
}

define('MerchantID','1292961');
define('HashKey','30GyZwBZjs2bgqDt');
define('HashIV','HarKMTK6vPVM9Vvg');

//付款完成資料拋回
define('ReturnURL',DOMAIN. '/app/__returnurl.php?type=returnurl');
//建立訂單完成資料拋回
define('PaymentInfoURL',DOMAIN. '/app/__returnurl.php?type=paymentinfo');
//線上付款完成成功頁
define('OrderResultURL',DOMAIN. '/account/store_record/');
//取號完成顯示back button
define('ClientBackURL',DOMAIN. '/account/store_record/');


//21世紀API網址
define('APP_API_URL','http://api.21-finance.com/app.aspx');

?>