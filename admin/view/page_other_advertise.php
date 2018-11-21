<?php 
require_once('model/require_general.php');

$ad = new Advertise();
$allData = $ad->getAll();

?>
<style>
#insert-area>*{
	margin:5px 0; 
}
</style>
<!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div style="width:100%;" class="page-title">
            <div style="width:100%;" class="title_left">
              <h3>廣告列表</h3>
              <a style="text-decoration:none;" href="?page=other&type=advertise&action=insert">
                <button class="btn btn-success">新增廣告</button>
              </a>
              <span style="color:red;">【所屬廣告區塊】會自動調整，主要每個區塊圖片大小一樣就會輪撥順暢，各區塊有註解原始套版建議大小。</span>
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
                        <th>廣告編號 </th>
                        <th>廣告順序 </th>
                        <th>廣告圖 </th>
                        <th>所屬廣告區塊 </th>
                        <th>廣告連結 </th>
						<th>是否顯示 </th>
                        <th>建立日期 </th>
                        <th class=" no-link last"><span class="nobr">編輯</span></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
	                    if($allData != null){
	                    	foreach($allData as $key=>$value){
	                    		$area = $ad->areaArr[$value["adArea"]];
                    ?>
                      <tr class="pointer">
                        <td class=" "><?php echo $value["adNo"]; ?></td>
                        <td class=" "><?php echo $value["adOrder"]; ?></td>
                        <td class=" "><img style="max-width:200px;" src="<?php echo $value["adImage"]; ?>"></td>
                        <td class=" "><?php echo $area; ?></td>
                        <td class=" "><a target="_blank" style="text-decoration:underline;color:blue;" href="<?php echo $value["adLink"]; ?>">連結</a></td>
                        <td class=" ">
							<input type="checkbox" class="status" name="adIfShow" <?php if($value["adIfShow"] == 1) echo "checked=true"; ?>>
                        </td>
						<td class=" "><?php echo $value["adDate"]; ?></td>
                        <td class=" last">
	                        <a href="?page=other&type=advertise&action=view&adno=<?php echo $value["adNo"]; ?>">
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
  		$(document).on("change",".status",function(){
			var qaNo = $(this).parent().siblings("td").eq(0).text();
			var val;
			if($(this).is(":checked")){
				val=1;
			}else{
				val=0;
			}
			$.post("ajax/indexImage/edit_if_show.php", {"adIfShow":val,"adNo":qaNo}, function(result){
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
        "order": [[ 2, "asc" ]],
        'iDisplayLength': 100,
        "sPaginationType": "full_numbers",
        "columns":[
          null,
          null,
          {"orderDataType":"dom-checkbox","type":"numic"},
          null,
          null,
          {"orderDataType":"dom-checkbox","type":"numic"},
          null,
          null
        ]
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