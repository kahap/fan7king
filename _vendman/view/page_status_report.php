<?php 
require_once('model/require_general.php');

$or = new Orders();

//時間
date_default_timezone_set('Asia/Taipei');
$date = date('Y-m-d', time());

$allOrData = array();
$ulrIframe = "a=a";

foreach($_GET as $key=>$value){
	$$key = $value;
	$ulrIframe .= "&".$key."=".$value;
}
if(isset($orDateFrom) && isset($orDateTo)){
	$allOrData = $or->getAllOrderForReport($orDateFrom, $orDateTo);
}


?>
<!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>案件審查時間統計表</h3>
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
                  <button id="export-excel" class="btn btn-success">匯出Excel</button>
                  <table id="example" class="table bulk_action table-striped responsive-utilities jambo_table">
                    <thead>
                      <tr class="headings">
                        <th>訂單編號 </th>
                        <th>下單時間</th>
                        <th>審查中時間 </th>
                        <th>核准時間 </th>
                        <th>婉拒時間 </th>
                        <th>待補時間 </th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
	                    if($allOrData != null && !empty(array_filter($allOrData))){
	                    	foreach($allOrData as $key=>$value){
                    ?>
                      <tr class="pointer">
                      	<td class=" "><a style="color:blue;text-decoration:underline;" href="?page=order&action=view&method=<?php echo $value["orMethod"];  ?>&orno=<?php echo $value["orNo"]; ?>"><?php echo $value["orCaseNo"]; ?></a></td>
                      	<td class=" "><?php echo $value["orDate"]; ?></td>
                      	<td class=" "><?php echo $value["orReportPeriod2Date"]; ?></td>
                        <td class=" "><?php echo $value["orReportPeriod3Date"]; ?></td>
                        <td class=" "><?php echo $value["orReportPeriod4Date"]; ?></td>
                        <td class=" "><?php echo $value["orReportPeriod5Date"]; ?></td>
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
		

		//匯出EXCEL
		$("#export-excel").click(function(){
			//匯出區塊
			var tab_text = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
		    tab_text = tab_text + '<head><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>';
		    tab_text = tab_text + '<x:Name>案件審查時間表</x:Name>';
		    tab_text = tab_text + '<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet>';
		    tab_text = tab_text + '</x:ExcelWorksheets></x:ExcelWorkbook></xml></head><body>';
		    tab_text = tab_text + "<table x:str border='1px'>";
		    tab_text = tab_text + $("iframe").contents().find("table").html();
		    tab_text = tab_text + '</table></body></html>';
			window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
			e.preventDefault();
		});

		//查詢
		$("#query").click(function(){
			var url = "?page=report&type=statusReport";
			if($(".with-name").val() != ""){
				for(var i=0; i<$(".with-name").length; i++){
					url = url + "&" + $(".with-name").eq(i).attr("name") + "=" + $(".with-name").eq(i).val();
				}
			}
			location.href = url;
		});

		//選擇日期
		$('#or-date-from, #or-date-to, #or-sup-date-from, #or-sup-date-to').daterangepicker({
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
        "order": [[ 1, "asc" ]],
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