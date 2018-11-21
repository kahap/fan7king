<?php
	session_start();
	if(!isset($_SESSION['supplieruserdata'])){
		include('view/page_login.html');
	}else{
		echo "<script>location.href='admin.php';</script>";
	}
?>