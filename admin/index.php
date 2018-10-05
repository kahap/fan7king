<?php
	session_start();
	if(!isset($_SESSION['userdata'])){
		include('view/page_login.html');
	}else{
		echo "<script>location.href='admin.php';</script>";
	}
?>