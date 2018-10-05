<?php 
require_once('model/require_general.php');

$or = new Orders();
$os = new Other_Setting();
$setting = $os->getAll();
$days = $setting[0]["receiptDays"];

$orData = $or->getOrdersForReceiptSet();

//時間
date_default_timezone_set('Asia/Taipei');
$date = date('Y-m-d', time());



?>
<!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>未開立發票訂單</h3>
            </div>
          </div>
          <div class="clearfix"></div>
		  
          <div class="row">

            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_content">
                  <h2>上傳EXCEL檔案</h2>
                  <form>
                  	<input style="margin-top:20px;" id="upload" type="file" name="upload">
                  	<span id="error" style="display:block;color:red;margin-bottom:20px;"></span>
                  	<button type="submit" id="submit" class="btn btn-success">確認上傳</button>
                  </form>
                  <table style="display: none;" id="example" class="table bulk_action table-striped responsive-utilities jambo_table">
                    <thead>
                      <tr class="headings">
                        <th>訂單編號 </th>
                        <th>內部訂單編號 </th>
                        <th>訂購人 </th>
                        <th>發票號碼 </th>
                        <th>發票日期</th>
                        <th>是否已開立發票</th>
                      </tr>
                    </thead>
                    <tbody>
                    
                    </tbody>
                  </table>
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
		

		//匯入EXCEL
		$("#export-excel").click(function(e){
			$("#upload").click();
		});

		$("#submit").click(function(e){
			e.preventDefault();
			$("#error").text("");
			$.ajax({
				url:"ajax/receipt/import_excel.php",
				type:"post",
				data: new FormData($("form")[0]),
				datatype:"json",
				contentType:false,
				processData: false,
				success:function(result){
					if(result.indexOf("檔") != -1){
						$("#error").text(result);
					}else{
						results = JSON.parse(result);
						$("#example").show();
						for(var i=0; i<results.orCaseNo.length; i++){
							$("tbody").append('<tr class="pointer">'
									+'<td class=" ">'+results.orCaseNo[i]+'</td>'
									+'<td class=" ">'+results.orInternalCaseNo[i]+'</td>'
									+'<td class=" ">'+results.memName[i]+'</td>'
									+'<td class=" ">'+results.receiptNo[i]+'</td>'
									+'<td class=" ">'+results.receiptDate[i]+'</td>'
									+'<td class=" ">開立成功</td>'
									+'</tr>');
						}
						var asInitVals = new Array();
						var oTable = $('#example').dataTable({
					        "oLanguage": {
					          "sSearch": "搜尋: "
					        },
					        "order": [[ 1, "asc" ]],
					        'iDisplayLength': 100,
					        "sPaginationType": "full_numbers"
					      });
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
					}
				}
			});
		});


  	});
  </script>