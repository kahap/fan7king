<?php
	session_start();
	include('../model/php_model.php');
	$product = new Product();
	$product_data = $product->getSearchProduct($_POST);
	print_r($product_data);
	
?>