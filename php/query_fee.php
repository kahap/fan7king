<?php
		include('../model/php_model.php');
		$url = "http://api.21-finance.com/query.aspx?id=201606260012";
		$content = @file_get_contents($url);
		print_r($content);
?>