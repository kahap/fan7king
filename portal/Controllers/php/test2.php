<?php
	$h = fopen('test.txt', "a+");
	fwrite($h,date("Y-m-d H:i:s")."\n");
	echo "111";
?>