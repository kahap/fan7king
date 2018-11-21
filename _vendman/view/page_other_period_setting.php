<?php 
require_once('model/require_general.php');

$ps = new Period_Setting();
$allPSData = $ps->getAllPS();

?>
<style>
#insert-area>*{
	margin:5px 0; 
}
</style>
<!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>分期計算列表</h3>
              <a style="text-decoration:none;" href="?page=other&type=periodSetting&action=insert">
                <button class="btn btn-success">新增分期計算</button>
              </a>
            </div>
          </div>
          <div class="clearfix"></div>

          <div class="row">
			
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_content">
                  <table id="example" class="table table-striped responsive-utilities jambo_table">
                    <thead>
                      <tr class="headings">
                        <th>分期計算編號 </th>
                        <th>期數 </th>
                        <th>總額倍率 </th>
                        <th>排序 </th>
                        <th>建立日期 </th>
                        <th class=" no-link last"><span class="nobr">編輯</span></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
	                    if($allPSData != null){
	                    	foreach($allPSData as $key=>$value){
                    ?>
                      <tr class="pointer">
                        <td class=" "><?php echo $value["psNo"]; ?></td>
                        <td class=" "><?php echo $value["psMonthNum"]; ?></td>
                        <td class=" "><?php echo $value["psRatio"]; ?></td>
                        <td class=" "><?php echo $value["psOrder"]; ?></td>
                        <td class=" "><?php echo $value["psDate"]; ?></td>
                        <td class=" last">
	                        <a href="?page=other&type=periodSetting&action=view&psno=<?php echo $value["psNo"]; ?>">
	                        	<button style="background-color:#FFF;border:1px solid #CCC;" class="btn btn-defult view-details">
	                        		詳細資訊/編輯
	                        	</button>
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

            <br />
            <br />
            <br />

          </div>
        </div>
        
  <!-- Datatables -->
  <script src="js/datatables/js/jquery.dataTables.js"></script>
  <script src="js/datatables/tools/js/dataTables.tableTools.js"></script>

  <!-- pace -->
  <script src="js/pace/pace.min.js"></script>
  <script>
  	$(function(){
  		
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
        "order": [[ 3, "asc" ]],
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