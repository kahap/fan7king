<?php 
require_once('model/require_general.php');

$or = new Orders();
$sr = new Status_Record();
$pm = new Product_Manage();
$pro = new Product();
$sup = new Supplier();
$mem = new Member();

//時間
date_default_timezone_set('Asia/Taipei');
$date = date('Y-m-d', time());

$allOrData = array();

foreach($_GET as $key=>$value){
	$$key = $value;
}
if(isset($orDateFrom) && isset($orDateTo)){
	$allOrData = $or->getAllOrderByDateAndMethod($orDateFrom, $orDateTo, $method);
}else{
	$allOrData = $or->getOrderByMethod(0);
}


?>
<!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>直購案件查詢</h3>
            </div>
          </div>
          <div class="clearfix"></div>
		  
          <div class="row">

            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_content">
                  <div class="query-area" style="margin:5px;">
                  	<label>日期： </label>
                  	<input id="or-date-from" name="orDateFrom" class="date-picker with-name" type="text" value="<?php if(isset($_GET["orDateFrom"])){ echo $_GET["orDateFrom"]; } ?>">
                  	至
                  	<input id="or-date-to" name="orDateTo" class="date-picker with-name" type="text" value="<?php if(isset($_GET["orDateTo"])){ echo $_GET["orDateTo"]; } ?>">
                  	<button id="query" style="margin-left:15px;" class="btn btn-success">查詢</button>
                  </div>
                  <table id="example" class="table bulk_action table-striped responsive-utilities jambo_table">
                    <thead>
                      <tr class="headings">
                      	<th>訂單狀態 </th>
                      	<th>訂單編號 </th>
                        <th>訂購日期</th>
                        <th>訂購人 </th>
                        <th>商品名稱 </th>
                        <th>商品規格 </th>
                        <th>總金額 </th>
                        <th>供應商 </th>
                        <th>訂貨日期 </th>
                        <th>出貨日期 </th>
                        <th>宅配公司 </th>
                        <th>宅配單號 </th>
                        <th>到貨日期 </th>
                        <th>換貨簽收日期 </th>
                        <th>退貨簽收日期 </th>
                        <th>撥款日期 </th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
	                    if($allOrData != null && !empty(array_filter($allOrData))){
	                    	foreach($allOrData as $key=>$value){
	                    		$statusNo = $value["orStatus"];
	                    		$or->changeToReadable($value,0);
	                    		$memData = $mem->getOneMemberByNo($value["memNo"]);
	                    		$pmData = $pm->getOnePMByNo($value["pmNo"]);
	                    		$proData = $pro->getOneProByNo($pmData[0]["proNo"]);
	                    		$supData = $sup->getOneSupplierByNo($value["supNo"])
                    ?>
                      <tr class="pointer">
                      	<td class=" "><a style="color:blue;text-decoration:underline;" href="?page=order&method=0&status=<?php echo $statusNo; ?>&orDateFrom=2016-04-01&orDateTo=<?php echo $date; ?>"><?php echo $value["orStatus"]; ?></a></td>
                      	<td class=" "><a style="color:blue;text-decoration:underline;" href="?page=order&action=view&method=0&orno=<?php echo $value["orNo"]; ?>"><?php echo $value["orCaseNo"]; ?></a></td>
                        <td class=" "><?php echo $value["orDate"]; ?></td>
                        <td class=" "><?php echo $memData[0]["memName"]; ?></td>
                        <td class=" "><a style="color:blue;text-decoration:underline;" href="?page=product&type=productManage&action=view&prono=<?php echo $proData[0]["proNo"]; ?>"><?php echo $proData[0]["proName"]; ?></td>
                        <td class=" "><?php echo $value["orProSpec"]; ?></td>
                        <td class=" "><?php echo number_format($pmData[0]["pmDirectAmnt"]*$value["orAmount"]); ?></td>
                        <td class=" "><?php echo $supData[0]["supName"]; ?></td>
                        <td class=" "><?php echo $value["orHandleOrderFromSupDate"]; ?></td>
                        <td class=" "><?php echo $value["orHandleSupOutDate"]; ?></td>
                        <td class=" "><?php echo $value["orHandleTransportComp"]; ?></td>
                        <td class=" "><?php echo $value["orHandleTransportSerialNum"]; ?></td>
                        <td class=" "><?php echo $value["orHandleGetFromSupDate"]; ?></td>
                        <td class=" "><?php echo $value["orHandleChangeProDate"]; ?></td>
                        <td class=" "><?php echo $value["orHandleRefundDate"]; ?></td>
                        <td class=" "><?php echo $value["orHandlePaySupDate"]; ?></td>
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
            
			<iframe style="display:none;" src="view/print_status_report.php?<?php echo $ulrIframe; ?>"></iframe>
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
		$("#query").click(function(){
			var url = "?page=order&action=query&method=0";
			if($(".with-name").val() != ""){
				for(var i=0; i<$(".with-name").length; i++){
					url = url + "&" + $(".with-name").eq(i).attr("name") + "=" + $(".with-name").eq(i).val();
				}
			}
			location.href = url;
		});

		//選擇日期
		$('#or-date-from, #or-date-to').daterangepicker({
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
    });

    var asInitVals = new Array();
    $(document).ready(function() {
      var oTable = $('#example').dataTable({
        "oLanguage": {
          "sSearch": "搜尋: "
        },
        "order": [[ 3, "desc" ]],
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
  </script>