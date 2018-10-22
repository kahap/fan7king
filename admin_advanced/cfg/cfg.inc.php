<?php
//預設日期
define('DATE_START','2016-01-01');

$AppMode = "Dev" ;//Dev-測試, Prov--正式
/************* 資料庫定義  ****************/
define ('SYSTEM_DBHOST','35.201.175.22:3306');
define ('SYSTEM_DBNAME','happyfan_system');
define ('SYSTEM_DBUSER','root');
define ('SYSTEM_DBPWD','b7Jl7qzubC635rkg');

// define ('SYSTEM_DBUSER','root');
// define ('SYSTEM_DBPWD','Cc120911810');
// define ('SYSTEM_DBUSER','root');
// define ('SYSTEM_DBPWD','1234');

if($AppMode !="Dev")
{
    if($AppMode == "Prod")
    {
        define ('DOMAIN','happyfan7.com');
    }
    else
    {
        // define ('DOMAIN','test.happyfan7.com');
        define ('DOMAIN','test.perfecthome.com.tw');
    }
    define('FbADVersion','v2.6');
}
else
{
//    define ('DOMAIN','develop.perfecthome.com.tw');
    define ('DOMAIN','127.0.0.1/fan7king_dev2');
    define('FbADVersion','v2.9');
}
    $allowed_hosts = array("localhost","127.0.0.1","happyfan7.com","test.happyfan7.com");    
    define("ALLOWED_HOSTS",json_encode($allowed_hosts));
// define ('IMG_ROOT','../admin/');
    define ('IMG_ROOT','../');
?>