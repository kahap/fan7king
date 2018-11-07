<?php


$api->setJoinArray(array("product_manage"=>"proNo"));
$api->setOrArray(array("product_manage`.`pmStatus"=>2));
$api->setGroupArray(array("product`.`proNo"));
$api->setLimitArray("3");
$api->setRetrieveArray(array("product.proNo","product_manage.pmNo","product.catNo","product.braNo","product.biNo","proName","pmIfDirect","proImage","proSpec","pmDirectAmnt","pmPeriodAmnt","pmBuyAmnt","pmStatus"));

for ($caseNumber=0; $caseNumber < 3; $caseNumber++) { 
    switch ($caseNumber) {
        //取得精選商品
        case 0:
            $which = 'pmSpecial';            
        break;
        //取得熱門(限時)商品
        case 1:
            $which = 'pmHot';
            break;
        //取得最新商品
        case 2:
            $which = 'pmNewest';
            break;
    }
    $api->setWhereArray(array("product_manage`.`".$which=>1,"product_manage`.`pmMainSup"=>1,"product_manage`.`pmStatus"=>1));
    $api->setOrderArray($which."Order");
    $api->getWithWhereAndJoinClause();
    $data = $api->getData();
    $result[$which] = $data;
}
    
    $api->setInformation($result, 1, count($result), "首頁商品");
    $api->setResult(false);
?>