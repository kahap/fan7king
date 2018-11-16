<?php
	session_start();
	include('../../model/php_model.php');	
	$supplier_sales = new Supplier_sales();
	$_POST['supNo'] = (empty($_POST['supNo'])) ? '帳密碼錯誤':$_POST['supNo'];
	$_POST['memAccount'] = (empty($_POST['memAccount'])) ? '帳密碼錯誤':$_POST['memAccount'];
	$_POST['memPwd'] = (empty($_POST['memPwd'])) ? '帳密碼錯誤':$_POST['memPwd'];
	$supplier_sales_data = $supplier_sales->getSupplier_sales($_POST['supNo'],$_POST['memAccount'],$_POST['memPwd']);
	if($supplier_sales_data != ""){
			$_SESSION['ss_supNo']=(string)$supplier_sales_data['supNo'];
			// $_SESSION['ss_ssNo']=(string)$supplier_sales_data['ssNo'];
			$_SESSION['ss_aauNo']=(string)$supplier_sales_data['aauNo'];
			echo 0;
	}else{
		echo 1;
	}

	function message($message,$path){
		return "<script>alert('".$message."');location.href='".$path."'</script>";
	}
?> 