<?php

require_once('model/require_general.php');

$page = isset($_GET['paginate'])? $_GET['paginate'] : '1';
$_POS = isset($_POS)? $_POS : '';


$search = new Search();
$rcData = $search->searchData($_POS, ($page-1)*30 , 30);
$totalProData = $search->getSearchDataCount($_POS);
$lastPage = ceil($totalProData/30);


$rc = new API("real_cases");
$or = new API("orders");
$mco = new API("motorbike_cellphone_orders");
$NotStatus = array('110');


$key_word = isset($key_word)? $key_word : '';

?>
<main class="mn-inner">
	<div class="row">
		<div class="col s12">
			<div class="page-title">案件列表 - 關鍵字: <?php echo trim($key_word) == "" ? "查全部" : $key_word; ?></div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<?php include "view/page_search_area.php"; ?>
				</div>
			</div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">


                    <div class="top dataTables_wrapper no-footer">
                        <div class="dataTables_info" id="example_info2" role="status" aria-live="polite">顯示 第 <?php echo ($page-1)*30+1;?> 筆 到 第 <?php echo ($page)*30;?> 筆，總共 <?php echo $totalProData;?> 筆</div>

                        <div class="dataTables_paginate paging_simple_numbers" id="example_paginate">
<!--                            <a href="?page=orders_view_general&type=list&paginate=1" class="paginate_button first disabled" aria-controls="example" data-dt-idx="0" tabindex="0" id="example_first">-->
<!--                                第一頁-->
<!--                            </a>-->
                            <?php if ($page>1){ ?>
                                <a href="?page=orders_view_general&type=list&paginate=<?php echo $page-1;?>" class="paginate_button previous disabled" aria-controls="example" data-dt-idx="1" tabindex="0" id="example_previous">
                                    <i class="material-icons">chevron_left</i>
                                </a>
                            <?php } ?>

                            <?php for ($i=1;$i<=$lastPage;$i++){ ?>
                            <span>
                                <a href="?page=orders_view_general&type=list&paginate=<?php echo $i;?>" class="paginate_button <?php if ($page==$i)echo 'current';?>" aria-controls="example" data-dt-idx="2" tabindex="0">
                                <?php echo $i; ?>
                                </a>
                            </span>
                            <?php } ?>
<!--                            <input class="paginate_button choosePage" value="--><?php //echo $page;?><!--" data-href="?page=orders_view_general&type=list">-->

                            <?php if ($page<$lastPage){ ?>
                                <a href="?page=orders_view_general&type=list&paginate=<?php echo $page+1;?>" class="paginate_button next disabled" aria-controls="example" data-dt-idx="3" tabindex="0" id="example_next">
                                    <i class="material-icons">chevron_right</i>
                                </a>
                            <?php } ?>
<!--                            <a href="?page=orders_view_general&type=list&paginate=--><?php //echo $lastPage;?><!--" class="paginate_button last disabled" aria-controls="example" data-dt-idx="4" tabindex="0" id="example_last">-->
<!--                                最後一頁-->
<!--                            </a>-->
                        </div>
                    </div>
                    <br />


                    <div  style="overflow-x:scroll; ">
   					<table id="example" class="display responsive-table datatable-example">
						<thead>
     						<tr>
								<th>案件編號</th>
								<th>訂單編號</th>
								<th>案件狀態</th>
								<th>姓名</th>
								<th>身分別</th>
								<th>身分證字號</th>
								<th>分期數</th>
								<th>分期總金額</th>
								<th>案件種類</th>
								<th>下單時間</th>
     						</tr>
						</thead>
     					<tbody>
                             <?php
     					if($rcData != null){


     						foreach($rcData as $key=>$value){
     						    // =__=
                                $value['rcStatus6Time'] = isset($value['rcStatus6Time'])? $value['rcStatus6Time'] : '';
                                // =__=

								$status = '';
								if(!in_array($value["rcStatus"],$NotStatus)){
									if($value['rcType'] == '0'){
										$orData = $or->getOne($value["rcRelateDataNo"]);
									}else{
										$mcoData = $mco->getOne($value["rcRelateDataNo"]);
                                    //Ben for Test     echo "rctype->".$value['rcType'].";no->".$value["rcRelateDataNo"] ;
									}
									switch($value["rcStatus"]){
										case "0":
											if($value['rcType'] == '0'){
												if($orData['0']["orStatus"] == '0' ){
													$status = "已下單-EMAIL未認證";
												}
												if($orData['0']["orStatus"] == '110' ){
													$status = "未完成下單";
												}
											}else{
												if($mcoData['0']["mcoStatus"] == '0' ){
													$status = "已下單-EMAIL未認證";
												}
												if($mcoData['0']["mcoStatus"] == '110' ){
													$status = "未完成下單";
												}
											}
										break;

										case "2":
											if($value["aauNoCredit"] == "" && $value["rcIfCredit"] == "0"){
												$status = "進件(尚未派件)";
											}

											if($value["aauNoCredit"] != "" && $value["rcIfCredit"] == "0" && $value['rcStatus5Time'] != '' or $value['rcStatus6Time'] != ''){
												$status = "徵信中(補件)";
											}elseif($value["aauNoCredit"] != "" && $value["rcIfCredit"] == "0"){
												$status = "徵信派件";
											}

											if($value["aauNoCredit"] != "" && $value["rcIfCredit"] == "1"){
												$status = "徵信中";
											}

											if($value["aauNoCredit"] != "" && $value["rcIfCredit"] == "1" &&
												$value["aauNoAuthen"] == "" && $value["rcIfAuthen"] == "0"){
												$status = "徵信完成";
											}

											if($value["aauNoCredit"] != "" && $value["rcIfCredit"] == "1" &&
												$value["aauNoAuthen"] != "" && $value["rcIfAuthen"] == "0"){
												$status = "授信派件";
											}

											if($value["aauNoCredit"] != "" && $value["rcIfCredit"] == "1" &&
												$value["aauNoAuthen"] != "" && $value["rcIfAuthen"] == "1"){
												$status = "授信中";
											}
										break;

										case "3":
											if($value["rcType"] == "0"){
												if($orData['0']["orStatus"] == "8"){
													$status = "出貨中";
												}else if($orData['0']["orStatus"] == "9"){
													$status = "已收貨";
												}else if($orData['0']["orStatus"] == "10"){
													$status = "已完成";
												}else{
													$status = "授信完成";
												}
											}else{
												if($value["rcApproStatus"] == '4'){
													$status = "已完成";
												}else{
													$status = "授信完成";
												}
											}
										break;

										case "4":
											if($value["aauNoCredit"] == "" && $value["rcIfCredit"] == "0" &&
												$value["aauNoAuthen"] == "" && $value["rcIfAuthen"] == "0"){
												$status = "未進件-婉拒";
											}
											if($value["aauNoCredit"] != "" && $value["rcIfCredit"] == "1" &&
												$value["aauNoAuthen"] != "" && $value["rcIfAuthen"] == "0"){
												$status = "授信-婉拒";
											}
											if($value["aauNoCredit"] != "" && $value["rcIfCredit"] == "1" &&
												$value["aauNoAuthen"] != "" && $value["rcIfAuthen"] == "1"){
												$status = "授信-婉拒";
											}
										break;

										case "5":
											if($value["aauNoCredit"] == "" && $value["rcIfCredit"] == "0" &&
												$value["aauNoAuthen"] == "" && $value["rcIfAuthen"] == "0"){
												$status = "未進件-待補";
											}
											if($value["aauNoCredit"] != "" && $value["rcIfCredit"] == "1" &&
												$value["aauNoAuthen"] != "" && $value["rcIfAuthen"] == "0"){
												$status = "授信-待補";
											}
											if($value["aauNoCredit"] != "" && $value["rcIfCredit"] == "1" &&
												$value["aauNoAuthen"] != "" && $value["rcIfAuthen"] == "1"){
												$status = "授信-待補";
											}
										break;

										case "6":
											if($value["aauNoCredit"] == "" && $value["rcIfCredit"] == "0" &&
												$value["aauNoAuthen"] == "" && $value["rcIfAuthen"] == "0"){
												$status = "未進件-客戶自行補件";
											}
											if($value["aauNoCredit"] != "" && $value["rcIfCredit"] == "1" &&
												$value["aauNoAuthen"] != "" && $value["rcIfAuthen"] == "0"){
												$status = "授信-客戶自行補件";
											}

										break;

										case "7":
											if($value["aauNoCredit"] == "" && $value["rcIfCredit"] == "0" &&
												$value["aauNoAuthen"] == "" && $value["rcIfAuthen"] == "0"){
												$status = "未進件-取消訂單";
											}
											if($value["aauNoCredit"] != "" && $value["rcIfCredit"] == "1" &&
												$value["aauNoAuthen"] != "" && $value["rcIfAuthen"] == "1"){
												$status = "授信-取消訂單";
											}
											if($value["aauNoCredit"] != "" && $value["rcIfCredit"] == "1" &&
												$value["aauNoAuthen"] != "" && $value["rcIfAuthen"] == "0"){
												$status = "授信-取消訂單";
											}

										break;

										case "701":
											if($value["aauNoCredit"] == "" && $value["rcIfCredit"] == "0" &&
												$value["aauNoAuthen"] == "" && $value["rcIfAuthen"] == "0"){
												$status = "未進件-客戶自行撤件";
											}
											if($value["aauNoCredit"] != "" && $value["rcIfCredit"] == "1" &&
												$value["aauNoAuthen"] != "" && $value["rcIfAuthen"] == "1"){
												$status = "授信-客戶自行撤件";
											}
											if($value["aauNoCredit"] != "" && $value["rcIfCredit"] == "1" &&
												$value["aauNoAuthen"] != "" && $value["rcIfAuthen"] == "0"){
												$status = "授信-客戶自行撤件";
											}
										break;

										default:
											$status = $rc->statusArr[$value["rcStatus"]];
										break;

									}

                             ?>
     						<tr>
     							<td><a target="_blank" href="?page=orders_view&type=view&no=<?php echo $value["rcNo"]; ?>"><?php echo $value["rcCaseNo"]; ?></a></td>
								<td><a target="_blank" href="?page=orders_view&type=view&no=<?php echo $value["rcNo"]; ?>"><?php echo ($value["rcType"] == '0') ? $orData['0']["orCaseNo"]:$mcoData['0']["mcoCaseNo"]; ?></a></td>
     							<td><?php echo $status; ?></td>
								<td><?php echo $value["memName"]; ?></td>
								<td><?php echo $rc->memClassArr[$value["memClass"]].(($value['rcPosition'] != "") ? "[".$value['rcPosition']."]":""); ?></td>
     							<td><?php echo $value["memIdNum"]; ?></td>
     							<td><?php echo $value["rcPeriodAmount"]; ?></td>
     							<td><?php echo ($value["rcType"] == '0') ? $value["rcPeriodTotal"]:$mcoData['0']['mcoPeriodTotal']; ?></td>
								<td><?php echo $rc->caseTypeArr[$value["rcType"]]; ?></td>
     							<td><?php echo $value["rcDate"]; ?></td>
     						</tr>
     					<?php
								}
     						}
     					}
     					?>
     					</tbody>
					</table>
                    </div>

			</div>
		</div>
	</div>
</main>

<script src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="assets/js/datatables/js/jquery.dataTables.js"></script>
<script src="assets/js/datatables/tools/js/dataTables.tableTools.js"></script>

<script>
$(function(){
	
});
$(document).ready(function() {
    // var oTable = $('#example').dataTable({
    //     "paging": false,
    //     "processing": true,
    //     "oLanguage": {
    //         "sSearch": "搜尋: "
    //     },
    //     "sPaginationType": "full_numbers"
    // })
    $('#example').DataTable({
        language: {
            searchPlaceholder: '尋找關鍵字',
            sInfo: "從 _START_ 到 _END_ ，共 _TOTAL_ 筆",
            sSearch: '',
            sLengthMenu: '顯示數 _MENU_',
            sLength: 'dataTables_length',
            // oPaginate: {
            //     sFirst: '<i class="material-icons">chevron_left</i>',
            //     sPrevious: '<i class="material-icons">chevron_left</i>',
            //     sNext: '<i class="material-icons">chevron_right</i>',
            //     sLast: '<i class="material-icons">chevron_right</i>'
            // }
        },
        "paging": false,
        "processing": true,
	    // "order": [[ 0 , "asc" ]],
	    "iDisplayLength": 30,
		"dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
        // "sPaginationType": "full_numbers"
    });
    $('.dataTables_length select').addClass('browser-default');

    /*
    *
    */
    $('#example_info').hide();
    $('.bottom').next('.dataTables_info').hide();

    $('.choosePage').change(function () {
        location.href = $(this).data('href') + '&paginate=' + $(this).val();
    });
});
</script>