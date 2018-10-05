<?php 

//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//curl_setopt($ch, CURLOPT_COOKIE, "cookieLangId=zh_tw;"); // 傳送cookie


$query = array(
    "Address" => "sander0127@yahoo.com.tw",
    "Subject" => "test",
    "Content" => "abcd",
    "ApiKey" => "說好的瑪莎拉蒂勒"
);

curl_email($query);




//收件人地址 Address ，多個MAIL用逗號分隔
//主旨  Subject
//內容  Content 
//ApiKey ApiKey，"說好的瑪莎拉蒂勒
function curl_email($query) {

    $url = "http://api.21-finance.com/api/mail";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
    $output = curl_exec($ch);
    curl_close($ch);
    var_dump($output);
}
?>