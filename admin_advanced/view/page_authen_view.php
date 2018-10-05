<?php 

$api = new API("real_cases");
$apiMem = new API("member");
$apiAau = new API("admin_advanced_user");

$aarNo = array('1','4','6','7','8','9','10');
if($_SESSION['adminUserData']['aauName'] != ""){
	$aarNoS = json_decode($_SESSION['adminUserData']['aarNo']);
	$access_right_counts = count(array_intersect($aarNoS,$aarNo));	
}

if($access_right_counts > 0){
	$str = "";
}else{
	$str = "&& a.aauNoAuthen = '".$_SESSION['adminUserData']['aauNo']."'";
}

if(isset($already)){
	$sql = "select a.rcNo,a.rcCaseNo,a.rcStatus,a.rcIfAuthen,a.rcType,a.memNo,b.memName,b.memIdNum,b.memClass,b.memBday,a.aauNoAuthen,c.aauName from real_cases a,member b, admin_advanced_user c where a.rcIfCredit = 1 and a.memNo = b.memNo && a.aauNoAuthen = c.aauNo ".$str." order by a.rcDate desc limit 5000";
}else{
	$sql = "select a.rcNo,a.rcCaseNo,a.rcStatus,a.rcIfAuthen,a.rcType,a.memNo,a.aauNoAuthen,b.memName,b.memIdNum,b.memClass,b.memBday,c.aauName from real_cases a,member b, admin_advanced_user c where (a.aauNoAuthen != '' or a.aauNoAuthen != NULL) and a.rcIfCredit = 1 and a.rcIfAuthen = 0 and a.rcStatus = 2  and a.memNo = b.memNo and a.aauNoAuthen = c.aauNo  ".$str." order by a.rcCaseNo desc";
}
//echo $sql;
$data = $api->customSql($sql);

?>
<main class="mn-inner">
	<div class="row">
		<div class="col s12">
			<div class="page-title">授信作業 - 可進行授信作業案件列表</div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<span class="card-title">請選擇欲授信之案件</span>
   					<table id="example" class="display responsive-table datatable-example">
						<thead>
     						<tr>
								<th>編號</th>
								<th>案件編號</th>
								<th>申請人姓名</th>
								<th>身分證字號</th>
								<th>身分別</th>
								<th>訂單狀態</th>
								<th>案件類別</th>
								<th>徵信狀態</th>
								<th>授信狀態</th>
								<th>指派授審人員</th>
     						</tr>
						</thead>
     					<tbody>
     					<?php 
     					if($data != null){
     						foreach($data as $key=>$value){
									//未滿20歲註記
									$mark = (AgeOver20($value["memBday"]) < '20') ? '*':'';
     					?>
     						<tr>
     							<td><?php echo $key+1; ?></td>
     							<td>
     								<a href="?page=authen&type=insert&no=<?php echo $value["rcNo"]; ?>"><?php echo $value["rcCaseNo"]; ?></a>
     							</td>
     							<td><?php echo $value["memName"]; ?><font style="color:red"><?php echo $mark; ?></font></td>
     							<td><?php echo $value["memIdNum"]; ?></td>
								<td><?php echo $value["memClass"] != "" ? $apiMem->memClassArr[$value["memClass"]] : "無"; ?></td>
     							<td><?php echo $api->statusArr[$value["rcStatus"]]; ?></td>
								<td><?php echo $api->category[$value["rcType"]]; ?></td>
     							<td><?php echo $value["rcIfCredit"] == "0" ? "尚未審核" : "已審核"; ?></td>
     							<td><?php echo $value["rcIfAuthen"] == "0" ? "尚未審核" : "已審核"; ?></td>
     							<td><?php echo $value["aauName"]; ?></td>
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