<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

session_start();
date_default_timezone_set('Asia/Taipei');

$rc = new API("real_cases");

$rc->update($_POST, $_POST["rcNo"]);

echo "OK";


?>