<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

date_default_timezone_set('Asia/Taipei');


if($_POST["rctype"] > 0){
	// echo getNextMonthDate($_POST["date"]);
	echo date("Y-m-d",strtotime("+29 day", strtotime($_POST["date"])));
}else{
	// echo getNextMonthDate($_POST["date"]);
	echo date("Y-m-d",strtotime("+12 day", strtotime($_POST["date"])));
}
?>