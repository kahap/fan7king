<?php
$api->setWhereArray(array("adTokenId"=>$_POST["adTokenId"]));
$api->getWithWhereAndJoinClause();
$data = $api->getData();
$api->update(array("adDeviceId"=>$_POST["adDeviceId"]), $data[0]["apNo"]);

?>