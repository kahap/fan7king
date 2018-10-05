<?php 
$allowed_hosts = array("localhost","127.0.0.1","happyfan7.com","test.happyfan7.com");
if (!isset($_SERVER['HTTP_HOST']) || !in_array($_SERVER['HTTP_HOST'], $allowed_hosts)) {
	$errMsg = "您無權限造訪此頁";
}else{
	if(isset($no) || isset($_GET["no"])){
		$no = $_GET["no"];
		
		$mem = new API("member");
		
		$memData = $mem->getOne($no);
		
		if($memData != null){
			$gps = new API("gps");
			$pt = new API("phone_text");
			$pc = new API("phone_contact");
			$phone = new API("phone_call");
			
			$gps->setWhereArray(array("memNo"=>$no));
			// $gps->setGroupArray(array("gpsLong","gpsLat"));
			$gpsData = $gps->getWithConditions();
			$gpsArr = array();
			$count = 0;
			if($gpsData != null){
				foreach($gpsData as $key=>$value){
					if($value["gpsLong"] != ""){
						$gpsArr[$count][0] = "時間: ".$value["time"];
						$gpsArr[$count][1] = $value["gpsLat"];
						$gpsArr[$count][2] = $value["gpsLong"];
						$count++;
					}
				}
			}
			
			
			$pt->setWhereArray(array("memNo"=>$no));
			$ptData = $pt->getWithConditions();
			
			$phone->setWhereArray(array("memNo"=>$no));
			$phone_callData = $phone->getWithConditions();
			
			$pc->setWhereArray(array("memNo"=>$no));
			$pcData = $pc->getWithConditions();
		}else{
			$errMsg = "查無此會員。";
		}
	}else{
		$errMsg = "錯誤的頁面導向。";
	}
}

?>
<style>
.actions ul li{
	float:left;
}
.btn{
	margin:10px;
}
.each-img .id-pic{
	max-width:80%;
}

</style>
<main class="mn-inner">
	<div class="row">
		<?php if(!isset($errMsg)){ ?>
		<div class="col s12">
			<div class="page-title">會員編號: <?php echo $no; ?></div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<span class="card-title">會員詳細資料</span><br>
					<div class="row">
						<form class="col s12">
							<div class="row">
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $memData[0]["memName"]; ?>">
									<label class="">申請人姓名</label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $memData[0]["memClass"] != "" ? $mem->memClassArr[$memData[0]["memClass"]] : "無"; ?>">
									<label class="">身分別</label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $memData[0]["memIdNum"]; ?>">
									<label class="">身分證字號</label>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-content">
					<span class="card-title">手機通訊錄</span><br>
					<div class="row">
						<table id="example" class="display responsive-table datatable-example">
							<thead>
	     						<tr>
									<th>姓</th>
									<th>名</th>
									<th>電話</th>
								</tr>
							</thead>
	     					<tbody>
		     					<?php
								if($pcData != null){
									foreach($pcData as $key=>$value){
								?>
								<tr>
									<td><?php echo $value["pcLastName"]; ?></td>
									<td><?php echo $value["pcFirstName"]; ?></td>
									<td><?php echo $value["pcCell"]; ?></td>
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
			<div class="card">
				<div class="card-content">
					<span class="card-title">通話紀錄</span><br>
					<div class="row">
						<table id="example" class="display responsive-table datatable-example">
							<thead>
	     						<tr>
									<th>姓名</th>
									<th>電話</th>
									<th>接通方式</th>
									<th>撥打時間</th>
								</tr>
							</thead>
	     					<tbody>
		     					<?php
								
								if($phone_callData != null){
									foreach($phone_callData as $key=>$value){
								?>
								<tr>
									<td><?php echo $value["pcName"]; ?></td>
									<td><?php echo $value["pcNumer"]; ?></td>
									<td><?php echo $value["pcStatus"]; ?></td>
									<td><?php echo $value["pcTime"]; ?></td>
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
			<div class="card">
				<div class="card-content">
					<span class="card-title">簡訊紀錄</span><br>
					<div class="row">
						<table id="example1" class="display responsive-table datatable-example">
							<thead>
	     						<tr>
									<th>簡訊發送來源號碼</th>
									<th>發送時間</th>
									<th>發送內容</th>
								</tr>
							</thead>
	     					<tbody>
		     					<?php
								if($ptData != null){
									foreach($ptData as $key=>$value){
								?>
								<tr>
									<td><?php echo $value["ptPhoneNum"]; ?></td>
									<td><?php echo $value["ptTime"]; ?></td>
									<td><?php echo $value["ptContent"]; ?></td>
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
			<div class="card">
				<div class="card-content">
					<span class="card-title">GPS紀錄</span><br>
					<div class="row">
						<div style="height:600px;" id="map">
						
						</div>
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
<script src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAJEp4_EEpuuQFIdmRcCyABrgTB8eT2tM4&callback=initMap" async defer></script>
<script>
$(function(){
	//GPS
// 	initMap();

	
	$(".confirm-save").click(function(e){
		$(".error").text("");
		e.preventDefault();

		var form = $("form").serialize();
		var url = "ajax/case_process/edit_case.php";
		
		$.ajax({
			url:url,
			type:"post",
			data:form,
			datatype:"json",
			success:function(result){
				if(result.indexOf("OK") != -1){
					alert("儲存成功！");
					location.reload();
				}else{
					var results = JSON.parse(result);
					$.each(results, function(k,v){
						$("#"+k+"Err").text(v);
					});
				}
			}
		});
	});

	$(".confirm-insert").click(function(e){
		$(".error").text("");
		e.preventDefault();

		var form = $("form").serialize();
		var url = "ajax/case_process/edit_case_process.php";
		
		$.ajax({
			url:url,
			type:"post",
			data:form,
			datatype:"json",
			success:function(result){
				if(result.indexOf("OK") != -1){
					alert("儲存成功！");
					location.href = "?page=case";
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
	    "iDisplayLength": 5
    	
    });
    $('.dataTables_length select').addClass('browser-default');
});

$(document).ready(function() {
    $('#example1').DataTable({
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
	    "order": [[ 1 , "asc" ]],
	    "iDisplayLength": 5
    	
    });
    $('.dataTables_length select').addClass('browser-default');
});

function initMap() {
	var locations = <?php echo json_encode($gpsArr,JSON_UNESCAPED_UNICODE); ?>

	var map = new google.maps.Map(document.getElementById('map'), {
		zoom: 7,
		center: new google.maps.LatLng(23.973875, 120.982024),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	});

	var infowindow = new google.maps.InfoWindow();

	var marker, i;

	for (i = 0; i < locations.length; i++) {  
		marker = new google.maps.Marker({
			position: new google.maps.LatLng(locations[i][1], locations[i][2]),
			map: map
		});

		google.maps.event.addListener(marker, 'click', (function(marker, i) {
			return function() {
				infowindow.setContent(locations[i][0]);
				infowindow.open(map, marker);
			}
		})(marker, i));
	}
}
</script>