<?php 
require_once('model/require_general.php');

$items = new B_items();
$allItemData = $items->getAllItems();

?>
<style>
#insert-area > * {
	margin:5px 0; 
}
</style>
<!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>品項列表</h3>
              <div id="insert-area" style="display:inline-block;margin:5px;padding:10px 20px;border:1px solid #AAA;">
	              <label>新增品項:　</label><br>
	              <span>品項名稱:　</span><input id="biName" name="biName" type="text"><br>
	              <span style="display: none;">排列順序(數字):　</span><input id="biOrder" name="biOrder" type="text" value="0" style="display: none;"><br>
	              <button style="margin-left:15px;" class="btn btn-success insert-confirm">確定</button>
	              <span id="insertErr" style="color:red;"></span>
              </div>
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
                        <th>品項編號 </th>
                        <th>品項名稱 </th>
<!--                        <th>品項順序 </th>-->
                        <th>是否顯示 </th>
                        <th>建立日期 </th>
                        <th class=" no-link last"><span class="nobr">編輯</span></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
                    if($allItemData != null){
                        foreach($allItemData as $key=>$value){
                            ?>
                              <tr class="pointer">
                                <td class=" "><?php echo $value["biNo"]; ?></td>
                                <td class=" "><?php echo $value["biName"]; ?></td>
<!--                                <td class=" ">--><?php //echo $value["biOrder"]; ?><!--</td>-->
                                <td class=" "><input class="biIfDisplayBox" type="checkbox" <?php if($value["biShow"] == 1) echo "checked";?>></td>
                                <td class=" "><?php echo $value["biDate"]; ?></td>
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
                                    <a class="content-edit" style="text-decoration: none;" href="#">
                                        <span style="margin-right:10px;" class="glyphicon glyphicon-pencil"></span>
                                    </a>
                                    <!--  
                                    <a class="content-remove" style="text-decoration: none;" href="#">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </a>
                                    -->
                                    <button style="margin-left:15px;background-color:#FFF;border:1px solid #CCC;display:none;" class="btn btn-defult edit-confirm">確定修改</button>
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
  	  	var mouseClicked = false;
  		var cur;
  		var curItemName;
  		var curItemOrder;
  		var curItemNameVal;
  		var curItemOrderVal;
  		var curNo;

  		//新增
		$(document).on("click",".insert-confirm",function(){
			$("#insertErr").text("");
			data = {
					"biName":$("#biName").val(),
					"biOrder":$("#biOrder").val()
					};
			$.post("ajax/b_items/insert.php", data, function(result){
				var results = JSON.parse(result);
				if(results.errMsg != ""){
					$("#insertErr").text(results.errMsg.biNameErr);
					$("#biName").focus();
				}else if(results.errMsg == ""){
					alert(results.success);
					location.href = "?page=product&type=general&which=items&pageIndex=last";
				}
			});
		});

		//更換顯示/隱藏
		$(document).on("change",".biIfDisplayBox",function(){
			var biNo = $(this).parent().siblings("td").eq(0).text();
			var val;
			if($(this).is(":checked")){
				val=1;
			}else{
				val=0;
			}
			var data = {"biNo":biNo, "biIfDisplay":val};
			
			$.post("ajax/b_items/update.php", data, function(result){
				alert(result);
			});
		});
		
		
  		//編輯
		$(document).on("click",".content-edit",function(e){
			e.preventDefault();
			cur = $(this);
			curNo = $(this).parent().parent().children().eq(0).text();
			curItemName = $(this).parent().parent().children().eq(1);
			curItemOrder = $(this).parent().parent().children().eq(2);
			if(curItemName.children("input").length == 0){
				curItemNameVal = curItemName.text();
				curItemName.html('<input class="input-option biName" name="biName" value="'+curItemNameVal+'">');
				cur.siblings(".edit-confirm").show();
				curItemName.children("input").select();
			}
			if(curItemOrder.children("input").length == 0){
				curItemOrderVal = curItemOrder.text();
				curItemOrder.html('<input class="input-option biOrder" name="biOrder" value="'+curItemOrderVal+'">');
			}
		});
		$(document).on("mousedown",".edit-confirm",function(){
			data = {"biNo":curNo,"biOrder":curItemOrder.children("input").val(),"biName":curItemName.children("input").val()};
			$.ajax({
				type: 'POST',
				url: "ajax/b_items/update.php",
				data: data,
				async: true,
				success:function(result){
					if(result.indexOf("成功") != -1){
						alert(result);
						location.reload();
					}else{
						alert(result);
					}
				}
			});
		});
		//點在INPUT上不會BLUR
		$(document).on("mousedown","body",function(e){
			if($(e.target).is(".biOrder") || $(e.target).is(".biName")){
				mouseClicked = true;
				$(this).focus();
			}else{
				mouseClicked = false;
			}
		});
		$(document).on("keypress",function(e){
			if(e.keyCode == 13 && $(".input-option").is(":focus")){
				data = {"biNo":curNo,"biOrder":curItemOrder.children("input").val(),"biName":curItemName.children("input").val()};
				$.ajax({
					type: 'POST',
					url: "ajax/b_items/update.php",
					data: data,
					async:false,
					success:function(result){
						if(result.indexOf("成功") != -1){
							alert(result);
							location.reload();
						}else{
							alert(result);
						}
					}
				});
			}
		});

		$(document).on("blur",".input-option",function(e){
			if(mouseClicked){
				mouseClicked = false;
				return false;
			}else{
				curItemName.text(curItemNameVal);
				curItemOrder.text(curItemOrderVal);
				$(".edit-confirm").hide();
			}
		});

// 		//刪除
// 		$(document).on("click",".content-remove",function(e){
// 			e.preventDefault();
// 			curNo = $(this).parent().parent().children().eq(0).text();
// 			if(window.confirm("確定要刪除嗎？\n*若將此種類刪除，所屬品項之產品、上架商品等有關資訊將一併刪除。")){
// 				data = {"catNo":curNo};
// 				$.post("ajax/bind/delete.php", data, function(result){
// 					alert(result);
// 					location.href = "?page=product&type=bind";
// 				});
// 			}
// 		});
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