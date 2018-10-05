<?php 

switch($type){
	case "roles":
		$table = "admin_advanced_roles";
		$tableName = "腳色";
		$dataShow = array("aarNo","aarName");
		//是否可新增
		$insertable = true;
		//是否可修改
		$editable = true;
		break;
	case "note_person":
		$table = "note_person";
		$tableName = "照會對象";
		$dataShow = array("npNo","npName");
		//是否可新增
		$insertable = true;
		//是否可修改
		$editable = true;
		break;
	case "note_list":
		$table = "note_list";
		$tableName = "照會方式";
		$dataShow = array("nlNo","npNo","nlName","nlIfMultiple");
		//是否可新增
		$insertable = true;
		//是否可修改
		$editable = true;
		break;
	case "account":
		$table = "admin_advanced_user";
		$tableName = "管理員";
		$dataShow = array("aauNo","aarNo","aauAccount","aauName","aauDate");
		$requiredOtherTableColumns = array(
				"aarNo"=>array("index"=>"aarNo","table"=>"admin_advanced_roles","column"=>"aarName")
		);
		//是否可新增
		$insertable = true;
		//是否可修改
		$editable = true;
		break;
		
}

$allowTables = array("admin_advanced_roles","note_person","admin_advanced_user");

if(isset($table) && in_array($table,$allowTables)){
	$api = new API($table);
	$columns = $api->getAllColumnNames();
	
	$data = $api->getAll();
}else{
	$errMsg = "錯誤的頁面導向。";
}
?>
<main class="mn-inner">
	<div class="row">
		<?php if(!isset($errMsg)){ ?>
		<div class="col s12">
			<div class="page-title">參數設定</div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<span class="card-title"><?php echo $tableName; ?></span>
					<?php if($insertable){ ?>
					<div style="padding-bottom:20px;">
						<a href="?page=param&type=<?php echo $type; ?>&action=insert" class="waves-effect waves-light btn green m-b-xs">新增<?php echo $tableName; ?></a>
					</div>
					<?php } ?>
   					<table id="example" class="display responsive-table datatable-example">
						<thead>
     						<tr>
								<?php 
								foreach($columns as $key=>$value){
									if(in_array($value["COLUMN_NAME"], $dataShow)){
								?>
								<th><?php echo $value["COLUMN_COMMENT"]; ?></th>
								<?php 
									}
								} 
								?>
								<?php if($editable){?>
								<th>編輯</th>
								<?php } ?>
     						</tr>
						</thead>
     					<tbody>
     					<?php 
     					if($data != null){
     						foreach($data as $key=>$value){
     					?>
     						<tr>
     							<?php 
     							foreach($columns as $keyIn=>$valueIn){ 
     								if(in_array($valueIn["COLUMN_NAME"], $dataShow)){
										if(isset($requiredOtherTableColumns) && array_key_exists($valueIn["COLUMN_NAME"],$requiredOtherTableColumns)){
											$apiForOther = new API($requiredOtherTableColumns[$valueIn["COLUMN_NAME"]]["table"]);
											$dataForOthers= null;
											$dataForOther= null;
											if($valueIn["COLUMN_NAME"]=="aarNo"){
												foreach(json_decode($value[$valueIn["COLUMN_NAME"]])as $kk =>$vv){
													$apiForOther->setWhereArray(array($requiredOtherTableColumns[$valueIn["COLUMN_NAME"]]["index"]=>$vv));
													$dataForOther1 = $apiForOther->getWithConditions();
													$dataForOthers[]=$dataForOther1;
												}
											}else{
												$apiForOther->setWhereArray(array($requiredOtherTableColumns[$valueIn["COLUMN_NAME"]]["index"]=>$value[$valueIn["COLUMN_NAME"]]));
												$dataForOther = $apiForOther->getWithConditions();
											}
     									
     							?>
     							<td><?php
								if (isset ( $dataForOthers )) {
									print "<small>";
									foreach ( $dataForOthers as $vvv ) {
										print $vvv [0] [$requiredOtherTableColumns [$valueIn ["COLUMN_NAME"]] ["column"]] . ". ";
									}
									print "</small>";
								} else {
									echo $dataForOther [0] [$requiredOtherTableColumns [$valueIn ["COLUMN_NAME"]] ["column"]];
								}
     							?></td>
								<?php 
										}else{
								?>
     							<td><?php echo $value[$valueIn["COLUMN_NAME"]]; ?></td>
     							<?php 
										}
     								}
     							} 
     							?>
     							<?php if($editable){?>
								<td>
									<a href="?page=param&type=<?php echo $type; ?>&action=edit&no=<?php echo $value[$api->getIdColumn()] ?>" class="waves-effect waves-light btn orange m-b-xs">
										瀏覽/編輯
									</a>
								</td>
								<?php } ?>
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