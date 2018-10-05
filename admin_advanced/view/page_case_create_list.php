<?php 

$api = new API("real_cases");
$apiMem = new API("member");
$or = new API("orders");
$pm = new API("product_manage");
$pro = new API("product");
$mco = new API("motorbike_cellphone_orders");
$lg = new API("loyal_guest");

$lgData = $lg->getAll();
$lgArr = array();
foreach($lgData as $key=>$value){
	$lgArr[] = $value["lgIdNum"];
}
switch($type){
	case "fixed":
		$pageTitle = "進件作業 - 未進件但已完成補件案件列表";
		$cardTitle = "未進件但已完成補件案件";
		$api->setWhereArray(array("rcStatus"=>6,"rcIfCredit"=>"0","rcIfAuthen"=>"0"));
		$str = "a.rcStatus = '6' && a.rcIfCredit = '0' && a.rcIfAuthen = '0'";
		break;
	case "unfixed":
		$pageTitle = "進件作業 - 未進件補件案件案件列表";
		$cardTitle = "未進件補件案件";
		$api->setWhereArray(array("rcStatus"=>5,"rcIfCredit"=>"0","rcIfAuthen"=>"0"));
		$str = "a.rcStatus = '5' && a.rcIfCredit = '0' && a.rcIfAuthen = '0'  && YEAR(a.rcDate) = YEAR(CURDATE())";
		break;
	case "after_fixed":
		$pageTitle = "進件作業 - 授信後但已完成補件案件列表";
		$cardTitle = "授信後但已完成補件案件";
		$api->setWhereArray(array("rcStatus"=>6,"rcIfCredit"=>"1","rcIfAuthen"=>">0"));
		$str = "a.rcStatus = '6' && a.rcIfCredit = '1' && a.rcIfAuthen = '0'";
		break;
	case "after_unfixed":
		$pageTitle = "進件作業 - 授信後待補案件列表";
		$cardTitle = "授信後待補案件";
		$api->setWhereArray(array("rcStatus"=>5,"rcIfCredit"=>"1","rcIfAuthen"=>"0"));
		$str = "a.rcStatus = '5' && a.rcIfCredit = '1' && a.rcIfAuthen = '0'"; 
		break;
		
	case "EmailNotIdentify":
		$pageTitle = "進件作業 - 已下單-Email未認證案件列表";
		$cardTitle = "Email未認證";
		$api->setWhereArray(array("rcStatus"=>0));
		$api->setOrderArray(array("rcStatus0Time"=>"desc"));
		$api->limitArr("400");
		$str = "a.rcStatus = '0'  && YEAR(a.rcDate) = YEAR(CURDATE()) && c.memClass =  '0' && c.memEmailAuthen = '0'";
		break;	
		
	default:
		$pageTitle = "進件作業 - 未進件案件列表";
		$cardTitle = "未進件案件";
		$api->setWhereArray(array("rcStatus"=>1));
		$str = "a.rcStatus = '1'";
		break;
}
$sql = "SELECT a.rcCaseNo,a.rcNo,a.rcType,a.rcDate,a.rcStatus,a.rcRelateDataNo,a.memNo,b.orCaseNo,b.orIfProcessInCurrentStatus,b.orProSpec,c.memName,c.memIdNum,c.memBday,c.memClass,d.proNo,e.proName,f.aauNo
		FROM `real_cases` a, orders b,member c,product_manage d,product e,supplier f
		where 
			".$str."&& a.rcType = '0'
			&& a.rcStatus = b.orStatus && a.rcRelateDataNo = b.orNo && b.pmNo = d.pmNo && d.proNo = e.proNo && a.memNo = c.memNo && a.supNo = f.supNo".(($type == 'EmailNotIdentify') ? ' limit 500':'');
$data = $api->customSql($sql);

$sql1 = "SELECT a.rcCaseNo,a.rcNo,a.rcType,a.rcDate,a.rcStatus,a.rcRelateDataNo,a.memNo,b.mcoCaseNo,b.mcoIfProcessInCurrentStatus,c.memName,c.memIdNum,c.memBday,c.memClass
		FROM `real_cases` a, motorbike_cellphone_orders b,member c
		where 
			".$str."&& a.rcType > '0'
			&& a.rcStatus = b.mcoStatus && a.rcRelateDataNo = b.mcoNo  && a.memNo = c.memNo";
if( $_GET['type'] != ''){
	$data1 = $api->customSql($sql1);
}
?>
<main class="mn-inner">
	<div class="row">
		<div class="col s12">
			<div class="page-title"><?php echo $pageTitle; ?></div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<span class="card-title"><?php echo $cardTitle; ?></span>
   					<table id="example" class="display responsive-table datatable-example">
						<thead>
     						<tr>
								<th>編號</th>
								<?php if(!isset($type)){ ?>
								<th>處理</th>
								<?php } ?>
								<th>案件編號</th>
								<th>訂單編號</th>
								<th>商品名稱</th>
								<th>商品規格</th>
								<th>申請人姓名</th>
								<th>身分別</th>
								<th>身分證字號</th>
								<th>老客戶</th>
								<th>訂單狀態</th>
								<th>下單日期</th>
     						</tr>
						</thead>
     					<tbody>
     					<?php
						$i = 0;
     					if($data != null){
     						foreach($data as $key=>$value){	
								$mark = (AgeOver20($value["memBday"]) < '20') ? '*':'';
							
     					?>
     						<tr>
     							<td><?php echo $i+=1; ?></td>
								<?php if(!isset($type)){ ?>
								<td>
									<?php if($value["orIfProcessInCurrentStatus"] == 1){
										echo "<font style='color:black'>已處理</font>";
									}else{
										echo "<font style='color:red'>未處理</font>";
									}?>
								</td>
								<?php } ?>
     							<td><?php echo $value["rcCaseNo"]; ?></td>
     							<td><a href="?page=case&type=edit&no=<?php echo $value["rcNo"]; ?>"><?php echo $value['orCaseNo'] ?></a></td>
								<td style="width:400px"><?php echo $value["proName"]; ?></td>
								<td><?php echo  $value["orProSpec"]; ?></td>
								<td><?php echo $value["memName"]; ?><font style="color:red"><?php echo $mark;?></font></td>
								<td><?php echo $or->memClassArr[$value["memClass"]]; ?></td>
     							<td><?php echo $value["memIdNum"]; ?></td>
     							<td><?php echo in_array($value["memIdNum"],$lgArr) ? "是" : "否"; ?></td>
     							<td><?php echo ($value["aauNo"] != '') ? "通訊行":"平台"; ?></td>
     							<td><?php echo $value["rcDate"]; ?></td>
     						</tr>
     					<?php
								}
						}
						if($data1 != null){
							foreach($data1 as $key=> $value){
						?>		
							<tr>
     							<td><?php echo $i+=1; ?></td>
								<?php if(!isset($type)){ ?>
								<td>
									<?php if($value["mcoIfProcessInCurrentStatus"] == 1){
										echo "<font style='color:black'>已處理</font>";
									}else{
										echo "<font style='color:red'>未處理</font>";
									}?>
								</td>
								<?php } ?>
     							<td><?php echo $value["rcCaseNo"]; ?></td>
     							<td><a href="?page=case&type=edit&no=<?php echo $value["rcNo"]; ?>"><?php echo $value['mcoCaseNo'] ?></a></td>
								<td style="width:400px"><?php echo $api->category[$value["rcType"]];; ?></td>
								<td><?php echo  ""; ?></td>
								<td><?php echo $value["memName"]; ?><font style="color:red"><?php echo $mark;?></font></td>
								<td><?php echo $or->memClassArr[$value["memClass"]]; ?></td>
     							<td><?php echo $value["memIdNum"]; ?></td>
     							<td><?php echo in_array($value["memIdNum"],$lgArr) ? "是" : "否"; ?></td>
     							<td><?php echo $api->category[$value["rcType"]]; ?></td>
     							<td><?php echo $value["rcDate"]; ?></td>
     						</tr>
						<?php
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

<script>
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
        "order": [[ 1, "desc" ]],
		"iDisplayLength": 100
    });
    $('.dataTables_length select').addClass('browser-default');
});
</script>