<?php
session_start();
	include('../model/php_model.php');
	$or = new Orders();
$action = $_GET['act'];
if($_SESSION['ord_code'] !=""){ 
	if (isset($GLOBALS["HTTP_RAW_POST_DATA"]))
	{
	  // Get the data
	  $imageData=$GLOBALS['HTTP_RAW_POST_DATA'];
	 
	  // Remove the headers (data:,) part.
	  // A real application should use them according to needs such as to check image type
	  $filteredData=substr($imageData, strpos($imageData, ",")+1);
	 
	  // Need to decode before saving since the data we received is already base64 encoded
	  $unencodedData=base64_decode($filteredData);
	 
	  //echo "unencodedData".$unencodedData;
	  if(!is_dir('../admin/file/'.$_SESSION['shopping_user'][0]['memNo'])){ 
	  	mkdir('../admin/file/'.$_SESSION['shopping_user'][0]['memNo']);
	  	chmod('../admin/file/'.$_SESSION['shopping_user'][0]['memNo'],0777);
	  }
	  // Save file. This example uses a hard coded filename for testing,
	  // but a real application can specify filename in POST variable
	  $path = '../admin/file/'.$_SESSION['shopping_user'][0]['memNo']."/sign_1.png";
	  $fp = fopen($path, 'wb' );
	  $or->updateorAppAuthenSignature(substr($path,3),$_SESSION['ord_code']);
	  fwrite( $fp, $unencodedData);
	  fclose( $fp );
	}

}

?>