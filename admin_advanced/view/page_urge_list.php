<?php 

$rc = new API("real_cases");
$mem = new API("member");
$sup = new API("supplier");
$aau = new API("admin_advanced_user");
$uu = new API("urge_user");
$up = new API("urge_period");
$tb = new API("transfer_bank");
$urr = new API("urge_request_records");
$tpi = new API("tpi");

$aauNo = $_SESSION["adminUserData"]["aauNo"];
$uu->setWhereArray(array("aauNo"=>$aauNo));
$uuData = $uu->getWithConditions();
if($type == "my"){
	if($uuData != null){
		/*$rc->setWhereArray(array("aauNoUrge"=>$aauNo));
		$rcData = $rc->getWithConditions();*/
		$urr->setWhereArray(array("uuNo"=>$aauNo,"urPayStatus"=>'0'));
		$urrData = $urr->getWithConditions();
	}else{
		$errMsg = "您不是催收人員。";
	}
}else{
 	$urr->setWhereArray(array("urPayStatus"=>'0'));
	$urr->setOrderArray(array("urrCurMValue"=>true));
	$urrData = $urr->getWithConditions();
}

?>
<main class="mn-inner">
	<div class="row">
		<?php if(!isset($errMsg)){?>
		<div class="col s12">
			<div class="page-title"><?php echo $type == "my" ? "我的" : "全部" ;?>催收案件</div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<span class="card-title">催收案件列表</span>
   					<table id="example" class="display responsive-table datatable-example">
						<thead>
     						<tr>
								<th>案件編號</th>
								<th>逾期天數</th>
								<th>本月應繳日期</th>
								<th>契約起始日</th>
								<th>身分證字號</th>
								<th>申請人</th>
								<th>期付款</th>
								<th>期數</th>
								<th>申請總金額</th>
								<th>區間</th>
								<th>催收人員</th>
								<th>派件時間</th>
								<th>銀行</th>
     						</tr>
						</thead>
     					<tbody>
     					<?php 
     					if($urrData != null){
     						foreach($urrData as $key=>$value){
								
								if(!in_array($value["rcNo"],$rcArray)){
									$rcArray[] = $value["rcNo"];
									$rcData = $rc->getOne($value["rcNo"]);
									$memData = $mem->getOne($rcData['0']["memNo"]);
									$supData = $sup->getOne($rcData['0']["supNo"]);
									$tbData = $tb->getOne($rcData['0']["tbNo"]);
									$tpi->setWhereArray(array("rcNo"=>$value["rcNo"],"tpiPeriod"=>$value["urrCurPeriod"]));
									$tpiData = $tpi->getWithConditions();
									$aauCurData = $aau->getOne($value['uuNo']);
								
     					?>
     						<tr>
     							<td>
     								<a href="?page=urge&type=edit&no=<?php echo $value["rcNo"]; ?>" target="_blank"><?php echo $rcData['0']["rcCaseNo"]; ?></a>
     							</td>
     							<td><?php echo $tpiData[0]['tpiOverdueDays']; ?></td>
								<td><?php echo $tpiData[0]['tpiSupposeDate']; ?></td>
								<td><?php echo $rcData['0']["rcPredictGetDate"]; ?></td>
								<td><?php echo $memData[0]["memIdNum"]; ?></td>
								<td><?php echo $memData[0]["memName"]; ?></td>
								<td><?php echo $tpiData[0]["tpiPeriodTotal"]; ?></td>
								<td><?php echo $rcData['0']["rcPeriodAmount"]; ?></td>
     							<td><?php echo $rcData['0']["rcPeriodTotal"]; ?></td>
     							<td><?php echo $value['urrCurMValue']; ?></td>
								<td><?php echo $aauCurData[0]["aauName"];?></td>
     							<td><?php echo $value["urrDate"]; ?></td>
								<td><?php echo $tbData[0]["tbName"]; ?></td>
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
		<?php }else{ ?>
		<div class="col s12">
			<div class="page-title"><?php echo $errMsg; ?></div>
		</div>
		<?php } ?>
	</div>
</main>

<script src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>

<script>
$(function(){
	
});
$(document).ready(function() {
    $('#example').DataTable({
        language: {
            searchPlaceholder: '尋找關鍵字',
            sInfo: "從 _START_ 到 _END_ ，共 _TOTAL_ 筆",
            sSearch: '',
            sLengthMenu: '顯示數 _MENU_',
            sLength: 'dataTables_length',
            oPaginate: {
                sFirst: '<i class="material-icons">chevron_left</i>',
                sPrevious: '<i class="material-icons">chevron_left</i>',
                sNext: '<i class="material-icons">chevron_right</i>',
                sLast: '<i class="material-icons">chevron_right</i>' 
            }
        },
	    "aoColumnDefs": [{
	        'bSortable': false,
	        'aTargets': [0]
	      } //disables sorting for column one
	    ],
	    "order": [[ 1 , "asc" ]],
	    "iDisplayLength": 100
    	
    });
    $('.dataTables_length select').addClass('browser-default');
});
</script>