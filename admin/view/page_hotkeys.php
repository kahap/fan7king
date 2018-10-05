<?php 
require_once('model/require_general.php');

$hk = new Hotkeys();
$allHKData = $hk->getAllHK();

?>
<!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>熱門字列表</h3>
              <label>新增熱門字:　</label><input id="hkKey" name="hkKey" type="text">
              <button style="margin-left:15px;" class="btn btn-success insert-confirm">確定</button>
              <span id="insertErr" style="color:red;"></span>
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
                        <th>熱門字 </th>
                        <th>是否啟用</th>
                        <th class=" no-link last"><span class="nobr">刪除</span></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
	                    if($allHKData != null){
	                    	foreach($allHKData as $key=>$value){
                    ?>
                      <tr class="pointer">
                        <td class=" "><?php echo $value["hkNo"]; ?></td>
                        <td class=" "><?php echo $value["hkKey"]; ?></td>
                        <td class=" ">
                        	<input type="checkbox" class="enable" name="hkEnable" <?php if($value["hkEnable"] == 1) echo "checked=true"; ?>>
                        </td>
                        <!-- 若欄位多
                        <td class=" last">
	                        <a href="?page=member&type=member&action=view&memno=">
	                        	<button style="background-color:#FFF;border:1px solid #CCC;" class="btn btn-defult view-details">
	                        		詳細資訊/編輯
	                        	</button>
	                        </a>
                        </td>
                         -->
                        
                        <td class=" last">
	                        <a class="content-remove" style="text-decoration: none;" href="#">
	                        	<span class="glyphicon glyphicon-remove"></span>
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
  		//新增
		$(document).on("click",".insert-confirm",function(){
			$("#insertErr").text("");
			data = {"hkKey":$("#hkKey").val()};
			$.post("ajax/hotkeys/insert.php", data, function(result){
				var results = JSON.parse(result);
				if(results.errMsg != ""){
					$("#insertErr").text(results.errMsg.hkKeyErr);
					$("#hkKey").focus();
				}else if(results.errMsg == ""){
					alert(results.success);
					location.href = "?page=other&type=hotkeys";
				}
			});
		});

		//編輯啟用
		$(document).on("change",".enable",function(){
			var cur = $(this);
			var curName = cur.attr("name");
			var curPro = cur.parent().parent().children().eq(0).text();
			var curVal;
			if(cur.is(":checked")){
				curVal = 1;
			}else{
				curVal = 0;
			}
			var data = {"hkNo":curPro, "hkEnable":curVal};
			
			$.post("ajax/hotkeys/update.php", data, function(result){
				if(result.indexOf("成功") != -1){
					alert(result);
				}else{
					alert(result);
				}
			});
		});

		//刪除
		$(document).on("click",".content-remove",function(e){
			e.preventDefault();
			curLgNo = $(this).parent().parent().children().eq(0).text();
			if(window.confirm("確定要刪除嗎？")){
				data = {"hkNo":curLgNo};
				$.post("ajax/hotkeys/delete.php", data, function(result){
					alert(result);
					location.href = "?page=other&type=hotkeys";
				});
			}
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