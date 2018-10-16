<?php
    header('Content-Type: text/html; charset=utf8');
    require_once('../../model/require_ajax.php');

    session_start();
    date_default_timezone_set('Asia/Taipei');

    $rcno = $_POST['rcno'];

    $orderLock = new API("orderLock");
    $orderLock->setWhereArray(array("rcNo"=>$rcno));
    $orderData = $orderLock->getWithConditions();
    $isLock = $orderData[0]["isLock"];
    $aaNo = $orderData[0]["ServiceName"];

    $result = null;
    
    if(count($orderData)>0){
        $result = array(
            "isLock" => $isLock,
            "aaNo" => $aaNo
        );
    }else{
        $result = array(
            "isLock" => "0",
            "aaNo" => "0"
        );
    }

    $myJson = json_encode($result);
    echo $myJson;
?>