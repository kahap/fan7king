<?php 

$sup = new API("supplier");

$data = $sup->getAll();

?>
<main class="mn-inner">
	<div class="row">
		<?php if(!isset($errMsg)){ ?>
		<div class="col s12">
			<div class="page-title">供應商管理</div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<span class="card-title">供應商列表</span>
					<div style="padding-bottom:20px;">
						<a href="?page=supplier&action=insert" class="waves-effect waves-light btn green m-b-xs">新增供應商</a>
					</div>
   					<table id="example" class="display responsive-table datatable-example">
						<thead>
     						<tr>
								<th>編號</th>
								<th>供應商名稱</th>
								<th>聯絡人</th>
								<th>電話</th>
								<th>傳真</th>
								<th>編輯</th>
     						</tr>
						</thead>
     					<tbody>
     					<?php 
     					if($data != null){
     						foreach($data as $key=>$value){
     					?>
     						<tr>
     							<td><?php echo $value["supNo"];?></td>
     							<td><?php echo $value["supName"];?></td>
     							<td><?php echo $value["supContactName"];?></td>
     							<td><?php echo $value["supPhone"];?></td>
     							<td><?php echo $value["supFax"];?></td>
     							<td>
									<a href="?page=supplier&action=edit&no=<?php echo $value["supNo"]; ?>" class="waves-effect waves-light btn orange m-b-xs">
										瀏覽/編輯
									</a>
								</td>
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
		<?php }else{ ?>
		<div class="col s12">
			<div class="page-title"><?php echo $errMsg; ?></div>
		</div>
		<?php } ?>
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
        }
    });
    $('.dataTables_length select').addClass('browser-default');
});
</script>