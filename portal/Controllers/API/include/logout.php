<?php
//登出

$api->delete($_POST);
$api->setInformation($_POST, 1, 1, "成功登出！");
$api->setResult();


?>