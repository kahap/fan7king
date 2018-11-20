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
// define ('SYSTEM_DBHOST','cloud_sql_proxy');
// define ('SYSTEM_DBNAME','happyfan_system');
// define ('SYSTEM_DBUSER','nowait-web');
// define ('SYSTEM_DBPWD','');

if($AppMode == "Dev"){
    define ('DOMAIN',$_SERVER['HTTP_HOST']."/fan7king_dev2");
    define('AutoloadAPPId','1947763301978389');
    define('AutoloadAPPSecret','d6f451301cdbc78083712d574d55c201');
  } else if ($AppMode == "Test"){
    define ('DOMAIN','test.nowait.shop');
    define('AutoloadAPPId','1011631165676168');
    define('AutoloadAPPSecret','7002699819af19678edf92f3790def4b');
  } else if ($AppMode == "Prod"){
    define ('DOMAIN','nowait.shop');
    define('AutoloadAPPId','1845375422218419');
    define('AutoloadAPPSecret','1184f8ce3bd0b4cb440f1dd066f3ab26');
  };
  define('FbADVersion','v3.2');

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