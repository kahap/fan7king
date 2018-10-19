<meta charset="UTF-8">
<?php
	session_start();
	unset($_SESSION['user']);
	unset($_SESSION['ord_code']);
	unset($_SESSION['shopping_user']['0']);
	unset($_SESSION['shopping_product']['0']);
	echo "<script>alert('已登出');location.href='../index.php';</script>";
?>