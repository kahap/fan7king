<?php 
require_once('model/require_general.php');

$qaa = new Qa_App();
$allQAData = $qaa->getAll();

?>
<!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>常見問題列表-APP版本</h3>
              <a style="text-decoration:none;" href="?page=qaapp&action=insert">
                <button class="btn btn-success">新增問答</button>
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
                        <th>編號 </th>
                        <th>問題 </th>
                        <th>成立時間 </th>
                        <th>排序 </th>
						<th>是否顯示 </th>
                        <th class=" no-link last"><span class="nobr">詳細資訊</span></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
	                    if($allQAData != null){
	                    	foreach($allQAData as $key=>$value){
                    ?>
                      <tr class="pointer">
                        <td class=" "><?php echo $value["qaaNo"]; ?></td>
                        <td class=" "><?php echo $value["qaaQues"]; ?></td>
                        <td class=" "><?php echo $value["qaaDate"]; ?></td>
                        <td class=" "><?php echo $value["qaaOrder"]; ?></td>
						<td class=" ">
							<input type="checkbox" class="status" name="qaaIfShow" <?php if($value["qaaIfShow"] == 1) echo "checked=true"; ?>>
                        </td>
						<td class=" last">
	                        <a href="?page=qaapp&action=view&qaano=<?php echo $value["qaaNo"]; ?>">
	                        	<button style="background-color:#FFF;border:1px solid #CCC;" class="btn btn-defult view-details">
	                        		詳細資訊/編輯
	                        	</button>
	                        </a>
                        </td>
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
		$(document).on("change",".status",function(){
			var qaNo = $(this).parent().siblings("td").eq(0).text();
			var val;
			if($(this).is(":checked")){
				val=1;
			}else{
				val=0;
			}
			$.post("ajax/qanda_app/edit_if_show.php", {"qaaIfShow":val,"qaaNo":qaNo}, function(result){
				if(result.indexOf("成功") != -1){
					alert(result);
				}else{
					alert(result);
				}
			});
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
        'iDisplayLength': 100,
        "sPaginationType": "full_numbers",
        "order": [[ 3, "asc" ]]
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