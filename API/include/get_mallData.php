<?php
$api->setWhereArray(array("catIfDisplay"=>1));
$api->setRetrieveArray(array("catNo","catName","catImage","catIcon"));
$api->setOrderArray("catOrder");
$api->getWithWhereAndJoinClause();
$data = $api->getData();

$shop = array();

foreach ($data as $number => $data1) {
    $re = array();
    foreach ($data1 as $key => $value) {
        $re[$key] = $value;
    }
    $which = 'pmSpecial';
    $product = new API("product");
    $product->setJoinArray(array("product_manage"=>"proNo"));
    $product->setGroupArray(array("product`.`proNo"));
    $product->setLimitArray("3");
    $product->setRetrieveArray(array("product.proNo","product_manage.pmNo","product.catNo","product.braNo","product.biNo","proName","pmIfDirect","proImage","proSpec","pmDirectAmnt","pmPeriodAmnt","pmBuyAmnt","pmStatus"));
    $product->setWhereArray(array("product_manage`.`pmStatus"=>1,"catNo"=>$data1['catNo']));
    $product->setOrderArray($which."Order");
    $product->getWithWhereAndJoinClause();

    $re['commodity'] = $product->getData();;
    $shop[$number] = $re;
}

$api->setInformation($shop, 1, count($data), "商城品項");
$api->setResult(false);
// print_r($shop); 
?>
