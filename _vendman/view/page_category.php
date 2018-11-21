<?php 
require_once('model/require_general.php');

$cat = new Category();
$allCatData = $cat->getAllCat();

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
              <h3>分類列表</h3>
              <a style="text-decoration:none;" href="?page=product&type=general&which=category&action=insert">
                <button class="btn btn-success">新增分類</button>
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
                        <th>分類編號 </th>
                        <th>分類名稱 </th>
                        <th>分類順序 </th>
                        <th>是否顯示 </th>
                        <th>分類圖片 </th>
                        <th>分類圖示 </th>
						<th>分類背景色 </th>
                        <th>建立日期 </th>
                        <th class=" no-link last"><span class="nobr">編輯</span></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
	                    if($allCatData != null){
	                    	foreach($allCatData as $key=>$value){
                    ?>
                      <tr class="pointer">
                        <td class=" "><?php echo $value["catNo"]; ?></td>
                        <td class=" "><?php echo $value["catName"]; ?></td>
                        <td class=" "><?php echo $value["catOrder"]; ?></td>
                        <td class=" "><input class="catIfDisplayBox" type="checkbox" <?php if($value["catIfDisplay"] == 1) echo "checked";?>></td>
                        <td class=" "><img style="max-width:80px;" src="<?php echo $value["catImage"]; ?>"></td>
                        <td class=" "><img style="max-width:80px;" src="<?php echo $value["catIcon"]; ?>"></td>
                        <td class=" "><div style="display:inline-block;padding:10px;background-color:<?php echo $value["catColor"]; ?>;"></div></td>
						<td class=" "><?php echo $value["catDate"]; ?></td>
                        <td class=" last">
	                        <a href="?page=product&type=general&which=category&action=view&catno=<?php echo $value["catNo"]; ?>">
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
  		//更換顯示/隱藏
		$(document).on("change",".catIfDisplayBox",function(){
			var catNo = $(this).parent().siblings("td").eq(0).text();
			var val;
			if($(this).is(":checked")){
				val=1;
			}else{
				val=0;
			}
			var data = {"catNo":catNo, "catIfDisplay":val};
			
			$.post("ajax/category/edit_ifDisplay.php", data, function(result){
				alert(result);
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