<?php 
    $updata = json_encode($_POST);

	$url="http://35.201.230.202:55501/api-bop/produce-barcode";    
    
    $ch = curl_init();//建立CURL連線
    curl_setopt( $ch,CURLOPT_URL, $url );
    curl_setopt( $ch,CURLOPT_POST, true );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $updata );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );

	$result = curl_exec($ch );

	curl_close( $ch );
	echo $result;
?>
