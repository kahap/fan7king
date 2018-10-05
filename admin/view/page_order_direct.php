<?php 
require_once('model/require_general.php');

$or = new Orders();
$sr = new Status_Record();
$pm = new Product_Manage();
$pro = new Product();
$sup = new Supplier();
$mem = new Member();

$allOrData = array();

foreach($_GET as $key=>$value){
	$$key = $value;
}

$allOrData = $or->getAllOrderByDateAndMethodAndStatus($orDateFrom, $orDateTo, $method, $status);


?>
<style>
.sup-input-area{
	margin:20px;
	float:left;
}
.sup-input-area div,.sup-input-area button{
	margin-top:15px;
}
</style>
<!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3><?php echo $or->statusDirectArr[$status] ?></h3>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row">

            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_content">
                  <div class="query-area" style="margin:10px 5px;padding-bottom:10px;border-bottom:1px solid #AAA;">
                  	<label>日期： </label>
                  	<input id="or-date-from" name="orDateFrom" class="date-picker with-name" type="text" value="<?php if(isset($_GET["orDateFrom"])){ echo $_GET["orDateFrom"]; } ?>">
                  	至
                  	<input id="or-date-to" name="orDateTo" class="date-picker with-name" type="text" value="<?php if(isset($_GET["orDateTo"])){ echo $_GET["orDateTo"]; } ?>">
                  	<button id="query" style="margin-left:15px;" class="btn btn-success">查詢</button>
                  </div>
                  <?php if($status == 0){ ?>
                  <div style="text-align:center;" class="col-md-12 col-sm-12 col-xs-12">
                  	<button id="submit-confirm" class="btn btn-success">打勾選項設定出貨中</button><br>
                  	<span style="color:red;float:left;margin:10px 0;">*付款狀態需為[已付]而且之後與供應商確認下訂收到回覆內容後，才設定出貨中。*</span>
                  </div>
                  <?php } ?>
                  <?php if($status == 1){ ?>
                  <div style="text-align:center;" class="col-md-12 col-sm-12 col-xs-12">
                  	<span style="color:red;float:left;margin:10px 0;">*顧客在[出貨中]前可以於前台[訂單查詢]自行[取消訂單]，[出貨中]後則需要聯絡客服後台設定退換貨*</span>
                  </div>
                  <?php }?>
                  <?php if($status == 2){ ?>
                  <div class="sup-input-area" style="border:1px solid #AAA; padding:10px;display:inline-block;">
	                  <div class="query-area">
	                  	<label>出貨日期： </label>
	                  	<input name="orHandleSupOutDate" class="date-picker" type="text">
	                  </div>
	                  <div class="query-area">
	                  	<label>宅配公司： </label>
	                  	<input name="orHandleTransportComp" type="text"><br>
	                  	<button id="confirm-sup-out" class="btn btn-success">打勾選項設定出貨日期及宅配公司</button>
	                  </div>
                  </div>
                  <div class="sup-input-area" style="border:1px solid #AAA; padding:10px;display:inline-block;">
	                  <div class="query-area">
	                  	<label>到貨日期： </label>
	                  	<input name="orHandleGetFromSupDate" class="date-picker" type="text"><br>
	                  	<button id="confirm-sup-arrive" class="btn btn-success">打勾選項設定到貨日期</button>
	                  </div>
                  </div>
                  <div style="margin:10px 0;" class="col-md-4 col-sm-12 col-xs-12">
                  	<button id="change-3" class="btn btn-success change-status">打勾選項設定已收貨</button>
                  </div>
                  <?php } ?>
                  <?php if($status == 3){ ?>
                  <div class="sup-input-area" style="border:1px solid #AAA; padding:10px;display:inline-block;">
	                  <div class="query-area">
	                  	<label>撥款日期： </label>
	                  	<input name="orHandlePaySupDate" class="date-picker" type="text"><br>
	                  	<button id="confirm-sup-pay" class="btn btn-success">打勾選項設定撥款日期</button>
	                  </div>
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                  	<span style="color:red;float:left;margin:10px 0;">*請務必確認已經輸入撥款日期後，才能設定已完成*</span>
                  </div>
                  <div style="margin:10px 0;" class="col-md-12 col-sm-12 col-xs-12">
                  	<button id="change-4" class="btn btn-success change-status">打勾選項設定已完成</button>
                  	<button id="change-5" class="btn btn-success change-status">打勾選項設定換貨中</button>
                  	<button id="change-6" class="btn btn-success change-status">打勾選項設定退貨中</button>
                  </div>
                  <?php } ?>
                  <?php if($status == 4){ ?>
                  <div style="text-align:center;" class="col-md-12 col-sm-12 col-xs-12">
                  	<button id="submit-process-1" class="btn btn-success process-time-setting">打勾選項設定有處理時間</button>
                  	<button id="submit-process-0" class="btn btn-warning process-time-setting">打勾選項標示無處理時間</button>
                  </div>
                  <?php } ?>
                  <?php if($status == 5){ ?>
                  <div class="sup-input-area" style="border:1px solid #AAA; padding:10px;display:inline-block;">
	                  <div class="query-area">
	                  	<label>換貨簽收日期： </label>
	                  	<input name="orHandleChangeProDate" class="date-picker" type="text"><br>
	                  	<button id="confirm-sup-change" class="btn btn-success">打勾選項設定換貨簽收日期</button>
	                  </div>
                  </div>
                  <div style="margin:10px 0;" class="col-md-12 col-sm-12 col-xs-12">
                  	<button id="change-3" class="btn btn-success change-status">打勾選項設定已收貨</button>
                  </div>
                  <?php } ?>
                  <?php if($status == 6){ ?>
                  <div class="sup-input-area" style="border:1px solid #AAA; padding:10px;display:inline-block;">
	                  <div class="query-area">
	                  	<label>退貨簽收日期： </label>
	                  	<input name="orHandleRefundDate" class="date-picker" type="text"><br>
	                  	<button id="confirm-sup-refund" class="btn btn-success">打勾選項設定退貨簽收日期</button>
	                  </div>
                  </div>
                  <div style="margin:10px 0;" class="col-md-12 col-sm-12 col-xs-12">
                  	<button id="change-7" class="btn btn-success change-status">打勾選項設定完成退貨</button>
                  </div>
                  <?php } ?>
                  <?php if($status == 7){ ?>
                  <div style="text-align:center;" class="col-md-12 col-sm-12 col-xs-12">
                  	<button id="submit-process-1" class="btn btn-success process-time-setting">打勾選項設定有處理時間</button>
                  	<button id="submit-process-0" class="btn btn-warning process-time-setting">打勾選項標示無處理時間</button>
                  </div>
                  <?php } ?>
                  <?php if($status == 2 || $status == 3 || $status == 5 || $status == 6){ ?>
                  <div style="text-align:center;" class="col-md-12 col-sm-12 col-xs-12">
                  	<button id="submit-confirm" class="btn btn-success">確認儲存</button>
                  </div>
                  <?php } ?>
                  
                  
                  <form>
                  <table id="example" class="table bulk_action table-striped responsive-utilities jambo_table">
                    <thead>
                      <tr class="headings">
                      	<?php if($status == 0 || $status == 2 || $status == 3 || $status == 4 || $status == 5 || $status == 6 || $status == 7){ ?>
                      	<th>
                          <input id="check-all" type="checkbox" class="tableflat">
                        </th>
                        <?php } ?>
                        <th>訂單狀態 </th>
                        <th>訂單編號 </th>
                        <th>訂單日期 </th>
                        <th>訂購人 </th>
                        <th>商品名稱 </th>
                        <th>商品規格 </th>
                        <th>總金額</th>
                        <th>供應商 </th>
                        <?php if($status == 0){ ?>
                        <th>付款狀態</th>
                        <?php } ?>
                        <?php if($status == 2 || $status == 3 || $status == 4 || $status == 5 || $status == 6 || $status == 7){ ?>
                        <th>訂貨日期</th>
                        <th>出貨日期</th>
                        <th>宅配公司</th>
                        <th>宅配單號</th>
                        <th>到貨日期</th>
                        <?php } ?>
                        <?php if($status == 3 || $status == 5){ ?>
                        <th>撥款日期</th>
                        <th>換貨簽收日期</th>
                        <?php }else if($status == 6){ ?>
                        <th>退貨簽收日期</th>
                        <?php }else if($status == 4){ ?>
                        <th>換貨簽收日期</th>
                        <th>撥款日期</th>
                        <th>處理時間</th>
                        <?php }else if($status == 7){ ?>
                        <th>換貨簽收日期</th>
                        <th>退貨簽收日期</th>
                        <th>處理時間</th>
                        <?php } ?>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
	                    if($allOrData != null && !empty(array_filter($allOrData))){
	                    	//
	                    	foreach($allOrData as $key=>$value){
	                    		$or->changeToReadable($value,$method);
	                    		$memData = $mem->getOneMemberByNo($value["memNo"]);
	                    		$pmData = $pm->getOnePMByNo($value["pmNo"]);
	                    		$proData = $pro->getOneProByNo($pmData[0]["proNo"]);
	                    		$supData = $sup->getOneSupplierByNo($value["supNo"])
                    ?>
                      <tr class="pointer">
                      	<?php if($status == 0 || $status == 2 || $status == 3 || $status == 4 || $status == 5 || $status == 6 || $status == 7){ ?>
                      	<td class="a-center ">
                          <input type="checkbox" class="tableflat for-checked">
                          <input type="hidden" name="orNo[]" value="<?php echo $value["orNo"] ?>">
                        </td>
                        <?php } ?>
                        <?php if($status == 0 || $status == 1 || $status == 4 || $status == 7){ //狀態不可編輯  ?>
                      	<td class=" "><?php echo $value["orStatus"]; ?></td>
                      	<?php }else{ //可編輯?>
                      	<td class=" ">
                      		<select name="orStatus[]" class="which-status">
                      			<?php 
                      			switch($status){
                      				case 2:
                      			?>
                      				<option value="2">出貨中</option>
                      				<option value="3">已收貨</option>
                      				<option value="1">取消訂單</option>
                      			<?php
                      				break;
									case 3:
                      			?>
                      				<option value="3">已收貨</option>
                      				<option value="4">已完成</option>
                      				<option value="5">換貨中</option>
                      				<option value="6">退貨中</option>
                      				<option value="1">取消訂單</option>
                      			<?php
                      				break;
									case 5:
                      			?>
                      				<option value="5">換貨中</option>
                      				<option value="3">已收貨</option>
                      				<option value="1">取消訂單</option>
                      			<?php
                      				break;
									case 6:
                      			?>
                      				<option value="6">退貨中</option>
                      				<option value="7">完成退貨</option>
                      				<option value="1">取消訂單</option>
                      			<?php
                      				break;
                      			}
                      			?>
                      		</select>
                      	</td>
                      	<?php } ?>
                      	<td class=" "><a data-case="<?php echo $value["orCaseNo"]; ?>" style="color:blue;text-decoration:underline;" href="?page=order&action=view&method=0&orno=<?php echo $value["orNo"]; ?>"><?php echo $value["orCaseNo"]; ?></a></td>
                        <td class=" "><?php echo $value["orDate"]; ?></td>
                        <td class=" "><?php echo $memData[0]["memName"]; ?></td>
                        <td class=" "><a style="color:blue;text-decoration:underline;" href="?page=product&type=productManage&action=view&prono=<?php echo $proData[0]["proNo"]; ?>"><?php echo $proData[0]["proName"]; ?></td>
                        <td class=" "><?php echo $value["orProSpec"]; ?></td>
                        <td class=" "><?php echo number_format($pmData[0]["pmDirectAmnt"]*$value["orAmount"]); ?></td>
                        <td class=" "><?php echo $supData[0]["supName"]; ?></td>
                        <?php if($status == 0){ ?>
                        <td class=" "><?php echo $value["orPaySuccess"]; ?></td>
                        <?php } ?>
                        <?php if($status == 2 || $status == 3 || $status == 4 || $status == 5 || $status == 6 || $status == 7){ ?>
                        <td class=" "><?php echo $value["orHandleOrderFromSupDate"] ?></td>
                        	<?php if($status == 2){ ?>
	                        <td class=" "><input size="10" type="text" name="orHandleSupOutDate[]" value="<?php echo $value["orHandleSupOutDate"] ?>"></td>
	                        <td class=" "><input size="10" type="text" name="orHandleTransportComp[]" value="<?php echo $value["orHandleTransportComp"] ?>"></td>
	                        <td class=" "><input size="10" type="text" name="orHandleTransportSerialNum[]" value="<?php echo $value["orHandleTransportSerialNum"] ?>"></td>
	                        <td class=" "><input size="10"type="text" name="orHandleGetFromSupDate[]" value="<?php echo $value["orHandleGetFromSupDate"] ?>"></td>
                        	<?php }else{ ?>
                        	<td class=" "><?php echo $value["orHandleSupOutDate"] ?></td>
	                        <td class=" "><?php echo $value["orHandleTransportComp"] ?></td>
	                        <td class=" "><?php echo $value["orHandleTransportSerialNum"] ?></td>
	                        <td class=" "><?php echo $value["orHandleGetFromSupDate"] ?></td>
                        	<?php } ?>
                        <?php } ?>
                        <?php if($status == 3 || $status == 5 || $status == 11){ ?>
                        	<?php if($status == 3){ ?>
	                        <td class=" "><input size="10" type="text" name="orHandlePaySupDate[]" value="<?php echo $value["orHandlePaySupDate"] ?>"></td>
                        	<?php }else{ ?>
                        	<td class=" "><?php echo $value["orHandlePaySupDate"] ?></td>
                        	<?php } ?>
                        	<?php if($status == 5){ ?>
	                        <td class=" "><input size="10" type="text" name="orHandleChangeProDate[]" value="<?php echo $value["orHandleChangeProDate"] ?>"></td>
                        	<?php }else{ ?>
                        	<td class=" "><?php echo $value["orHandleChangeProDate"] ?></td>
                        	<?php } ?>
                        <?php }else if($status == 4 || $status == 6 || $status == 7){ ?>
                        	<?php if($status == 6){ ?>
	                        <td class=" "><input size="10" type="text" name="orHandleRefundDate[]" value="<?php echo $value["orHandleRefundDate"] ?>"></td>
                        	<?php }else if($status == 4){ ?>
                        	<td class=" "><?php echo $value["orHandleChangeProDate"] ?></td>
                        	<td class=" "><?php echo $value["orHandlePaySupDate"] ?></td>
                        	<td class=" "><?php echo $value["orProcessTime"] ?></td>
                        	<?php }else if($status == 7){ ?>
                        	<td class=" "><?php echo $value["orHandleChangeProDate"] ?></td>
                        	<td class=" "><?php echo $value["orHandleRefundDate"] ?></td>
                        	<td class=" "><?php echo $value["orProcessTime"] ?></td>
                        	<?php } ?>
                        <?php } ?>
                        <!--  若欄位少
                        <td class=" last">
	                        <a class="content-edit" style="text-decoration: none;" href="#">
	                        	<span style="margin-right:10px;" class="glyphicon glyphicon-pencil"></span>
	                        </a>
	                        <a class="content-remove" style="text-decoration: none;" href="#">
	                        	<span class="glyphicon glyphicon-remove"></span>
	                        </a>
                        </td>
                        -->
                      </tr>
                     <?php 
                    		}
                    	}
                     ?>
                    </tbody>
                  </table>
        		  </form>
                </div>
              </div>
            </div>

            <br />
            <br />
            <br />

          </div>
        </div>
  <!-- Datatables -->
  <script src="js/datatables/js/jquery.dataTables.js"></script>
  <script src="js/datatables/tools/js/dataTables.tableTools.js"></script>
  <!-- daterangepicker -->
  <script type="text/javascript" src="js/moment/moment.min.js"></script>
  <script type="text/javascript" src="js/datepicker/daterangepicker.js"></script>
  <!-- pace -->
  <script src="js/pace/pace.min.js"></script>
  
  <script>
  	$(function(){
  		//查詢
		$("#query").click(function(e){
			e.preventDefault();
			var url = "?page=order&method=0&status=<?php echo $status; ?>";
			if($(".with-name").val() != ""){
				for(var i=0; i<$(".with-name").length; i++){
					url = url + "&" + $(".with-name").eq(i).attr("name") + "=" + $(".with-name").eq(i).val();
				}
			}
			location.href = url;
		});

		//當選訂出貨入期及宅配公司並按確定時
		$("#confirm-sup-out").click(function(e){
			e.preventDefault();
			var supOutDateVal = $(".query-area input[name='orHandleSupOutDate']").val();
			var supOutComp = $(".query-area input[name='orHandleTransportComp']").val();;
			
			if($(".for-checked:checked").length == 0){
				alert("請先勾選要加入出貨日期及宅配公司的訂單選項");
			}else{
				for(var i=0; i<$(".for-checked:checked").length; i++){
					var cur = $(".for-checked:checked").eq(i);
					var curSupOutDate = cur.parent().parent().siblings("td").eq(9).find("input");
					var curSupOutComp = cur.parent().parent().siblings("td").eq(10).find("input");
					curSupOutDate.val(supOutDateVal);
					curSupOutComp.val(supOutComp);
				}
			}
		});

		//當選訂到貨日期並按確定時
		$("#confirm-sup-arrive").click(function(e){
			e.preventDefault();
			var supArriveDateVal = $(".query-area input[name='orHandleGetFromSupDate']").val();
			
			if($(".for-checked:checked").length == 0){
				alert("請先勾選要加入到貨日期的訂單選項");
			}else{
				for(var i=0; i<$(".for-checked:checked").length; i++){
					var cur = $(".for-checked:checked").eq(i);
					var cursupArriveDate = cur.parent().parent().siblings("td").eq(12).find("input");
					cursupArriveDate.val(supArriveDateVal);
				}
			}
		});

		//設定撥款日期
		$("#confirm-sup-pay").click(function(e){
			e.preventDefault();
			var supPayDateVal = $(".query-area input[name='orHandlePaySupDate']").val();
			
			if($(".for-checked:checked").length == 0){
				alert("請先勾選要加入撥款日期的訂單選項");
			}else{
				for(var i=0; i<$(".for-checked:checked").length; i++){
					var cur = $(".for-checked:checked").eq(i);
					var curPayDateVal = cur.parent().parent().siblings("td").eq(13).find("input");
					curPayDateVal.val(supPayDateVal);
				}
			}
		});

		//設定狀態
		$(".change-status").click(function(){
			changeCheckedStatus($(this));
		});

		//設定換貨簽收日期
		$("#confirm-sup-change").click(function(e){
			e.preventDefault();
			var supPayDateVal = $(".query-area input[name='orHandleChangeProDate']").val();
			
			if($(".for-checked:checked").length == 0){
				alert("請先勾選要加入換貨簽收日期的訂單選項");
			}else{
				for(var i=0; i<$(".for-checked:checked").length; i++){
					var cur = $(".for-checked:checked").eq(i);
					var curPayDateVal = cur.parent().parent().siblings("td").eq(14).find("input");
					curPayDateVal.val(supPayDateVal);
				}
			}
		});

		//設定退貨簽收日期
		$("#confirm-sup-refund").click(function(e){
			e.preventDefault();
			var supPayDateVal = $(".query-area input[name='orHandleRefundDate']").val();
			
			if($(".for-checked:checked").length == 0){
				alert("請先勾選要加入退貨簽收日期的訂單選項");
			}else{
				for(var i=0; i<$(".for-checked:checked").length; i++){
					var cur = $(".for-checked:checked").eq(i);
					var curPayDateVal = cur.parent().parent().siblings("td").eq(13).find("input");
					curPayDateVal.val(supPayDateVal);
				}
			}
		});

		//設定有/無處理時間(完成退貨)
		$(".process-time-setting").click(function(e){
			e.preventDefault();
			var curVal = $(this).attr("id").split("-")[2];
			if($(".for-checked:checked").length == 0){
				if(curVal == 1){
					alert("請先勾選要設定處理時間的訂單選項");
				}else{
					alert("請先勾選要設定無處理時間的訂單選項");
				}
			}else{
				var url = "ajax/order/edit_direct_<?php echo $status; ?>.php";
				var checked = [];
				for(var i=0; i<$(".for-checked:checked").length; i++){
					checked.push($(".for-checked:checked").eq(i).parent().siblings("input[name='orNo[]']").val());
				}
				$.ajax({
					url:url,
					type:"post",
					data:{"orNo[]":checked,"ifSet":curVal},
					success:function(result){
						alert("更新成功！");
						for(var i=0; i<$(".for-checked:checked").length; i++){
							$(".for-checked:checked").eq(i).parent().parent().siblings("td").eq(15).text(result);
						}
					}
				});
			}
		});

		//儲存修改 OR 設定出貨中
		$("#submit-confirm").click(function(e){
			e.preventDefault();
			<?php if($status == 0){ ?>
			if($(".for-checked:checked").length == 0){
				alert("請先勾選要改成出貨的訂單選項");
			}else{
				
			<?php }?>
				var form = $("form").serialize();
				<?php if($status == 0){ ?>
				var checked = [];
				for(var i=0; i<$(".for-checked:checked").length; i++){
					checked.push($(".for-checked:checked").eq(i).parent().siblings("input[name='orNo[]']").val());
				}
				<?php }?>
				var url = "ajax/order/edit_direct_<?php echo $status; ?>.php";
				$.ajax({
					url:url,
					type:"post",
					data:<?php if($status == 0){ ?>{"orNo[]":checked}<?php }else{ ?>form<?php }?>,
					success:function(result){
						if(result.indexOf("成功") != -1){
							alert(result);
							location.reload();
						}else{
							<?php 
							switch($status){
								case 0:
							?>
								alert(result);
							<?php 
								break;
								case 2:
							?>
								var msg = "";
								for(var i=0; i<(result.split("#").length/2); i++){
									var orCaseNo = result.split("#")[1+i*2];
									msg = msg + result.split("#")[0+i*2];
									$("a[data-case='"+orCaseNo+"']").parent().parent().find("input[name='orHandleGetFromSupDate[]']").css("border","1px solid red");
								}
								alert(msg);
							<?php 
								break;
								case 3:
							?>
								var msg = "";
								for(var i=0; i<(result.split("#").length/2); i++){
									var orCaseNo = result.split("#")[1+i*2];
									msg = msg + result.split("#")[0+i*2];
									$("a[data-case='"+orCaseNo+"']").parent().parent().find("input[name='orHandlePaySupDate[]']").css("border","1px solid red");
								}
								alert(msg);
							
							<?php 
								break;
								case 5:
							?>
								var msg = "";
								for(var i=0; i<(result.split("#").length/2); i++){
									var orCaseNo = result.split("#")[1+i*2];
									msg = msg + result.split("#")[0+i*2];
									$("a[data-case='"+orCaseNo+"']").parent().parent().find("input[name='orHandleChangeProDate[]']").css("border","1px solid red");
								}
								alert(msg);
							<?php 
								break;
								case 6:
							?>
							var msg = "";
							for(var i=0; i<(result.split("#").length/2); i++){
								var orCaseNo = result.split("#")[1+i*2];
								msg = msg + result.split("#")[0+i*2];
								$("a[data-case='"+orCaseNo+"']").parent().parent().find("input[name='orHandleRefundDate[]']").css("border","1px solid red");
							}
							alert(msg);
							<?php 
								break;
								case 12:
							?>
								var index = result.split("#")[1];
								alert(result.split("#")[0]);
								$("input[name='orHandleRefundDate[]']").eq(index).css("border","1px solid red").focus();
							<?php 
								break;
							}
							?>
						}
					}
				});
			<?php if($status == 0){ ?>
			}
			<?php }?>
		});

		//選擇日期
		$('.date-picker').daterangepicker({
            singleDatePicker: true,
            calender_style: "picker_4",
            format: 'YYYY-MM-DD'
          });

        
  	});
    $(document).ready(function() {
        $('input.tableflat').iCheck({
          checkboxClass: 'icheckbox_flat-green',
          radioClass: 'iradio_flat-green'
        });
        
    	//勾全部
		var checkAll = $('#check-all');
	    var checkboxes = $('input.for-checked');
	
	    checkAll.on('ifChecked ifUnchecked', function(event) {        
	        if (event.type == 'ifChecked') {
	            checkboxes.iCheck('check');
	        } else {
	            checkboxes.iCheck('uncheck');
	        }
	    });
	
	    checkboxes.on('ifChanged', function(event){
	        if(checkboxes.filter(':checked').length == checkboxes.length) {
	            checkAll.prop('checked', 'checked');
	        } else {
	            checkAll.removeProp('checked');
	        }
	        checkAll.iCheck('update');
	    });
	    
    });

    var asInitVals = new Array();
    $(document).ready(function() {
      var oTable = $('#example').dataTable({
        "oLanguage": {
          "sSearch": "搜尋: "
        },
        "aoColumnDefs": [{
            'bSortable': false,
            'aTargets': [0]
          } //disables sorting for column one
        ],
        "order": [[ 
                   <?php 
                   switch($status){ 
                   	case 0:
                   	case 2:
                   	case 3:
                    case 4:
                   	case 5:
                    case 6:
                    case 7:
                    	echo "3";
                    	break;
                    case 1:
                    	echo "2";
                    	break;
           		}
           		?>, "desc" ]],
        'iDisplayLength': 100,
        "sPaginationType": "full_numbers"
      })<?php if(isset($_GET["pageIndex"]) && $_GET["pageIndex"]=='last') echo ".fnPageChange( 'last' );$(window).scrollTop($(document).height())";?>;
      $("tfoot input").keyup(function() {
        /* Filter on the column based on the index of this element's parent <th> */
        oTable.fnFilter(this.value, $("tfoot th").index($(this).parent()));
      });
      $("tfoot input").each(function(i) {
        asInitVals[i] = this.value;
      });
      $("tfoot input").focus(function() {
        if (this.className == "search_init") {
          this.className = "";
          this.value = "";
        }
      });
      $("tfoot input").blur(function(i) {
        if (this.value == "") {
          this.className = "search_init";
          this.value = asInitVals[$("tfoot input").index(this)];
        }
      });
    });
    
    function changeCheckedStatus(selectorId){
    	var statusVal = selectorId.attr("id").split("-")[1];
    	var statusName = $(".for-checked").eq(0).parent().parent().siblings("td").eq(0).find("select").find("option[value="+statusVal+"]").text();
    	
    	if($(".for-checked:checked").length == 0){
    		alert("請先勾選要改成["+statusName+"]狀態的訂單選項");
    	}else{
    		for(var i=0; i<$(".for-checked:checked").length; i++){
    			$(".for-checked:checked").eq(i).parent().parent().siblings("td").eq(0).find("select").find("option[value="+statusVal+"]").prop("selected",true);
    		}
    	}
    }
  </script>