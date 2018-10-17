<?php

/*
 *  這段沒用到了 server slid ajax php
 */

$sort_arr = [];
$search_arr = [];
$search_word =    @$_REQUEST['sSearch'] ? @$_REQUEST['sSearch'] : '' ;
$iDisplayLength = @$_REQUEST['iDisplayLength'] ? @$_REQUEST['iDisplayLength'] : 0 ;
$iDisplayStart =  @$_REQUEST['iDisplayStart'] ? @$_REQUEST['iDisplayStart'] : 0 ;
$sEcho =          @$_REQUEST['sEcho' ] ? @$_REQUEST['sEcho'] : '' ;
$column_arr =     @$_REQUEST['sColumns' ] ? @$_REQUEST['sColumns'] : '' ;
$column_arr = explode( ',', $column_arr );
foreach ($column_arr as $key => $item)
{
    if ($item == "") {
        unset( $column_arr[$key] );
        continue;
    }
    if (@$_REQUEST[ 'bSearchable_' . $key ] == "true") {
        $search_arr[$key] = $item;
    }
    if (@$_REQUEST[ 'bSortable_' . $key ] == "true") {
        $sort_arr[$key] = $item;
    }
}
$sort_name = $sort_arr[ @$_REQUEST[ 'iSortCol_0' ] ];
$sort_dir = @$_REQUEST[ 'sSortDir_0' ];



//require_once('../../model/require_general.php');//載入Class
function __autoload($ClassName)
{
    include_once('../../cls/'.$ClassName.".cls.php");
}

require_once('../../cfg/cfg.inc.php');

require_once('../../lib/function.php');

$cat = new Category();
$pro = new Product();
$bra = new Brand();

//$allProData = $pro->getAllPro();
$allProData = $pro->getAllProWhitDT($sort_arr,$search_word, $sort_name, $sort_dir ,$iDisplayStart ,$iDisplayLength);
$total_count = $pro->db->iNoOfRecords;


//if(isset($_GET["catname"]) && $_GET["catname"] != "all"){
//    $allProData = $pro->getAllProByCatName($_GET["catname"]);
//    $total_count = $pro->db->iNoOfRecords;
//}
//if(isset($_GET["braname"]) && $_GET["braname"] != "all"){
//    $allProData = $pro->getAllProByBraName($_GET["braname"]);
//    $total_count = $pro->db->iNoOfRecords;
//}


$allCatData = $cat->getAllCat();
$allBraData = $bra->getAllBrand();



//$data_arr = ModMessage::query()->where($map)
//    ->where(function( $query ) use ( $sort_arr, $search_word ) {
//        foreach ($sort_arr as $item) {
//            $query->orWhere( $item, 'like', '%' . $search_word . '%' );
//        }
//    })
//    ->where('iType', '>', 50)
//    ->orderBy( $sort_name, $sort_dir )
//    ->skip( $iDisplayStart )
//    ->take( $iDisplayLength )
//    ->get();


if($allProData != null){
    //foreach ($data_arr as $key => $var)
    foreach($allProData as $key => $value)
    {
        $cateName = $cat->getOneCatByNo($value["catNo"]);
        $braName = $bra->getOneBrandByNo($value["braNo"]);

        $value['catName'] = isset($cateName[0]["catName"])? $cateName[0]["catName"] : '';
        $value['braName'] = isset($braName[0]["braName"])? $braName[0]["braName"] : '';
        $value['proSpec'] = isset($value["proSpec"]) ? $pro->changeSign($value["proSpec"],"<br>") : '';
    }
}

$rtndata ['status'] = 1;
$rtndata ['sEcho'] = isset($sEcho) ? $sEcho : '';
$rtndata ['iTotalDisplayRecords'] = $total_count;
$rtndata ['iTotalRecords'] = $total_count;
$rtndata ['aaData'] = $total_count ? $allProData : [];

echo json_encode( $rtndata );