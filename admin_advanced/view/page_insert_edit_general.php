<?php
switch ($type) {
	case "roles" :
		$goBackUrl = "?page=param&type=roles";
		$table = "admin_advanced_roles";
		$tableName = "腳色";
		$notInsertable = array (
				"aarNo",
				"aafID" 
		);
		$dataType = array (
				"aarName" => "text" 
		);
		break;
	case "note_person" :
		$goBackUrl = "?page=param&type=note_person";
		$table = "note_person";
		$tableName = "照會對象";
		$notInsertable = array (
				"npNo",
				"npIfActivate" 
		);
		$dataType = array (
				"npName" => "text" 
		);
		break;
	case "account" :
		$aau = new API ( "admin_advanced_user" );
		$goBackUrl = "?page=param&type=account";
		$table = "admin_advanced_user";
		$tableName = "管理員";
		$notInsertable = array (
				"aauNo",
				"aarNo",
				"aauNoUpdate",
				"aauNoCreate",
				"aauUpdateDate",
				"aauDate" 
		);
		$dataType = array (
				"aauStartDay" => "date",
				"aauLeaveDay" => "date" 
		);
		// print_r($_REQUEST);
		// exit();
		break;
}

$allowTables = array (
		"admin_advanced_roles",
		"note_person",
		"admin_advanced_user" 
);

if (isset ( $table ) && in_array ( $table, $allowTables )) {
	$api = new API ( $table );
	$columns = $api->getAllColumnNames ();
	
	if ($action == "edit") {
		$data = $api->getOne ( $no );
	}
	
	// 客製區
	switch ($type) {
		case "roles" :
			$apiFunctions = new API ( "admin_advanced_functions" );
			$apiFunctions->setOrderArray ( array (
					"aafID" => false 
			) );
			$funcData = $apiFunctions->getWithConditions ();
			if ($action == "edit") {
				$curFuncArr = json_decode ( $data [0] ["aafID"] );
			}
			break;
	}
} else {
	$errMsg = "錯誤的頁面導向。";
}
?>
<style>
input:not ([type] ), input[type=text], input[type=password], input[type=email],
	input[type=url], input[type=time], input[type=date], input[type=datetime],
	input[type=datetime-local], input[type=tel], input[type=number], input[type=search],
	textarea.materialize-textarea {
	margin: 0;
}

.p-v-xs {
	padding-left: 30px;
}

.input-title {
	font-weight: bold;
	font-size: 18px;
	margin-bottom: 10px;
	display: inline-block;
}
</style>
<main class="mn-inner">
<div class="row">
		<?php if(!isset($errMsg)){ ?>
		<div class="actions clearfix">
		<ul role="menu" aria-label="Pagination">
			<li aria-hidden="false" aria-disabled="false"><a
				href="<?php echo $goBackUrl; ?>" role="menuitem"
				class="waves-effect waves-blue btn-flat">回去列表</a></li>
		</ul>
	</div>
	<div class="col s12">
		<div class="page-title"><?php echo $action == "edit" ? "編輯".$tableName : "新增".$tableName; ?></div>
	</div>
	<div class="col s12 m12 l12">
		<div class="card">
			<div class="card-content">
				<div class="row">
					<form class="col s12">
						<input type="hidden" name="table" value="<?php echo $table; ?>"> <input
							type="hidden" name="action" value="<?php echo $action; ?>">
							<?php if($action == "edit"){?>
							<input type="hidden" name="no" value="<?php echo $no; ?>">
							<?php } ?>
							<div class="row" style="text-align: center;">
							<a class="waves-effect waves-light btn green m-b-xs confirm-save">確認儲存</a>
						</div>
							<?php
			foreach ( $columns as $keyIn => $valueIn ) {
				// 判斷是否是可新增的欄位
				if (! in_array ( $valueIn ["COLUMN_NAME"], $notInsertable )) {
					// 判斷是哪種資料格式
					switch ($dataType [$valueIn ["COLUMN_NAME"]]) {
						case "text" :
							include "view/part_input_text.php";
							break;
						case "readonly" :
							include "view/part_input_readonly.php";
							break;
						case "date" :
							include "view/part_input_text_date.php";
							break;
						case "select" :
							include "view/part_input_select.php";
							break;
						default :
							include "view/part_input_text.php";
							break;
					}
				}
			}
			// 客製區
			switch ($type) {
				case "roles" :
					?>
							<div class="row">
							<div class="input-field col s12">
								<span class="input-title">擁有權限</span> <label id="aafNoErr"
									class="error"></label>
									<?php foreach($funcData as $key=>$value){ ?>
									<p class="p-v-xs">
									<input
										<?php echo isset($curFuncArr) && in_array($value["aafID"], $curFuncArr) ? "checked" : ""; ?>
										type="checkbox" name="aafID[]"
										value="<?php echo $value["aafID"]; ?>"
										class="tableflat for-checked"> <span><?php echo $value["aafID"]; ?>： <?php echo $value["aafName"]; ?></span>
								</p>
                                    <?php } ?>
								</div>
						</div>
							<?php
					break;
				case "note_person" :
					?>
							<div class="row">
							<div class="input-field col s12">
								<span class="input-title">是否啟用</span>
								<p class="p-v-xs">
									<input name="npIfActivate"
										<?php echo (isset($data) && $data[0]["npIfActivate"] == "1") ? "checked" : ""; ?>
										value="1" type="radio" id="input1" /> <label for="input1">啟用</label>
								</p>
								<p class="p-v-xs">
									<input name="npIfActivate"
										<?php echo (isset($data) && $data[0]["npIfActivate"] == "0") ? "checked" : ""; ?>
										value="0" type="radio" id="input2" /> <label for="input2">不啟用</label>
								</p>
							</div>
						</div>
                            <?php
					break;
				case "account" :
					?>
						<!-- 
							<div class="row">
							<div class="input-field col s12">
								
									<select name="aarNo">
										<?php
					$aar = new API ( "admin_advanced_roles" );
					$aarData = $aar->getAll ();
					foreach ( $aarData as $key => $value ) {
						?>
										<option <?php echo (isset($data) && $data[0]["aarNo"] == $value["aarNo"]) ? "selected" : ""; ?> value="<?php echo $value["aarNo"];?>"><?php echo $value["aarName"];?></option>
										<?php
					}
					?>
									</select>
									 -->
						<?php
					
					$aar = new API ( "admin_advanced_roles" );
					$aarData = $aar->getAll ();
					$i =0;
					foreach ( $aarData as $key => $value ) {
						if ($i % 6 == 0) {
							print '<div class="row">';
						}
						
						$db__dc_json = json_decode ( $data [0] ["aarNo"] );
						print '<div class="col s2">';
						print '<input type="checkbox" ';
						echo (isset ( $data ) && in_array ( $value ["aarNo"], $db__dc_json )) ? "checked" : "";
						print ' name="aarNo[]" id="r' . $value ["aarNo"] . '" value="' . $value ["aarNo"] . '"/><label class="" for="r' . $value ["aarNo"] . '" >' . $value ["aarName"] . '</label>';
						print " </div> ";
						if ($i % 6 == 5 || $i == count ( $aarData ) - 1) {
							print '</div>';
						}
						$i ++;
					}
					
					?>

						<!-- <label class="">所屬腳色</label> <label id="aarNoErr" class="error"></label>
							</div>
						</div> -->
						<div class="row">
							<div class="input-field col s12">
								<input type="text" readonly
									value="<?php echo isset($data) ? $data[0]["aauUpdateDate"] : ""; ?>">
								<label class="">更新日期</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s12">
								<input type="text" readonly
									value="<?php echo isset($data) ? $aau->getOne($data[0]["aauNoUpdate"])[0]["aauName"] : ""; ?>">
								<label class="">更新人員</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s12">
								<input type="text" readonly
									value="<?php echo isset($data) ? $data[0]["aauDate"] : ""; ?>">
								<label class="">建立日期</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s12">
								<input type="text" readonly
									value="<?php echo isset($data) ? $aau->getOne($data[0]["aauNoCreate"])[0]["aauName"] : ""; ?>">
								<label class="">建立人員</label>
							</div>
						</div>
							<?php
					break;
			}
			?>
							<div class="row" style="text-align: center;">
							<a class="waves-effect waves-light btn green m-b-xs confirm-save" id="enabling">確認儲存</a>
							<?php if($tableName=="管理員"){?><a class="waves-effect waves-light btn orange m-b-xs confirm-save" id="disabling">停用儲存</a><?php }; ?>
						</div>
					</form>
				</div>
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
<script src="assets/js/pages/form_elements.js"></script>
<script src="assets/js/pages/ui-modals.js"></script>
<script>
$(function(){
	$(".confirm-save").click(function(e){
		if($(this).attr("id")=="disabling"){
			$('input[name="aarNo[]"]').attr('checked', false);
		}
		$(".error").text("");
		e.preventDefault();
		var form = $("form").serialize();
		var url = "ajax/general/insert_edit.php";
		
		$.ajax({
			url:url,
			type:"post",
			data:form,
			datatype:"json",
			error: function(xhr, ajaxOptions, thrownError) {
				confirm(xhr.responseText);
			},
			success:function(result){
				// confirm(result);
				if(result.indexOf("OK") != -1){
					alert("儲存成功！");
					<?php if($action == "edit"){ ?>
					location.reload();
					<?php }else{ ?>
					location.href = "<?php echo $goBackUrl; ?>";
					<?php }?>
				}else{
					var results = JSON.parse(result);
					$.each(results, function(k,v){
						$("#"+k+"Err").text(v);
					});
				}
			}
		});
	});

});
</script>