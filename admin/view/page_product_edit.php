<?php 
require_once('model/require_general.php');

if($_GET["action"] == "edit"){
	$pro = new Product();
	$proNo = $_GET["prono"];
	$proData = $pro->getOneProByNo_view($proNo);
	$proImageArray = json_decode($proData[0]["proImage"]);
}

$cat = new Category();
$allCat = $cat->getAllCatOrder();

$bra = new Brand();
$allBra = $bra->getAllBrandOrder();

$items = new B_items();
$allItems = $items->getAllItemsOrder();

//相簿圖片
$imgArr = getAllImgs();
$imgArr = isset($imgArr) ?: [];


$tabIndex = 0;

?>

	<link href="http://netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css" rel="stylesheet">
	<link href="css/editor/external/google-code-prettify/prettify.css" rel="stylesheet">
	<link href="css/editor/index.css" rel="stylesheet">
<!-- page content -->
      <div class="right_col" role="main" style="min-height: 949px;">
        <div class="">

          <div class="page-title">
            <div class="title_left">
            <?php if($_GET["action"]=="edit"){?>
              <h3>編輯商品資料</h3>
            <?php }else{ ?>
              <h3>新增商品</h3>
            <?php } ?>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2 style="text-align:center;float:none;">
                  <?php if($_GET["action"]=="edit"){ ?>
                  	  商品編號: <?php echo $proData[0]["proCaseNo"]; ?> &nbsp&nbsp&nbsp&nbsp
                  	<?php if(isset($_SERVER['HTTP_REFERER'])){ ?>
                  	<a style="color:#FFF;" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">
	                  <button class="btn btn-success">回上頁</button>
	                </a>
	                <?php } ?>
                  <?php }else{ ?>
                  	<a style="color:#FFF;" href="?page=product&type=product">
	                  <button class="btn btn-success">回商品列表</button>
	                </a>
                  <?php }?>
                  </h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br>
                  <form action="ajax/product/edit.php" method="POST" enctype="multipart/form-data" class="form-horizontal form-label-left">
                  <?php if($_GET["action"]=="edit"){ ?>
                    <input type="hidden" name="proNo" value="<?php echo $proNo; ?>">
                  <?php } ?>
                  	<input id="imgMethod" type="hidden" name="imgMethod" value="">
                  	<input id="album" type="hidden" name="album" value="">
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	所屬分類 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
	                      <select name="catNo" class="form-control">
	                      	<option selected value="">請選擇</option>
	                      	<?php foreach($allCat as $key=>$value){ ?>
	                        	<option <?php if($_GET["action"]=="edit") if($proData[0]["catNo"]==$value["catNo"]) echo "selected"; ?> value="<?php echo $value["catNo"]; ?>"><?php echo $value["catName"]; ?></option>
	                        <?php } ?>
	                      </select>
	                      <ul class="parsley-errors-list"><li id="catErr"></li></ul>
	                  </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	所屬品牌 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
	                      <select name="braNo" class="form-control">
	                      	<option selected value="">請選擇</option>
	                      	<?php foreach($allBra as $key=>$value){ ?>
	                        	<option <?php if($_GET["action"]=="edit") if($proData[0]["braNo"]==$value["braNo"]) echo "selected"; ?> value="<?php echo $value["braNo"]; ?>"><?php echo $value["braName"]; ?></option>
	                        <?php } ?>
	                      </select>
	                      <ul class="parsley-errors-list"><li id="braErr"></li></ul>
	                  </div>
                    </div>
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                              所屬品項 :
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <select name="biNo" class="form-control">
                                  <option selected value="">請選擇</option>
                                  <?php foreach($allItems as $key=>$value){ ?>
                                      <option <?php if($_GET["action"]=="edit") if($proData[0]["biNo"]==$value["biNo"]) echo "selected"; ?> value="<?php echo $value["biNo"]; ?>"><?php echo $value["biName"]; ?></option>
                                  <?php } ?>
                              </select>
                              <ul class="parsley-errors-list"><li id="biErr"></li></ul>
                          </div>
                      </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	商品名稱 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value='<?php if($_GET["action"]=="edit") echo $proData[0]["proName"]; ?>' type="text" class="form-control" name="proName" />
                      	<ul class="parsley-errors-list"><li id="nameErr"></li></ul>
                      </div>
                    </div>

                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                              主題優惠 :
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <input value='<?php /*if($_GET["action"]=="edit") echo $proData[0]["proName"];*/ ?>' type="text" class="form-control" name="themeOffer" />
                              <ul class="parsley-errors-list"><li id="themeOfferErr"></li></ul>
                          </div>
                      </div>
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                              商品加贈 :
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <input value='<?php /*if($_GET["action"]=="edit") echo $proData[0]["proName"];*/ ?>' type="text" class="form-control" name="productGift" />
                              <ul class="parsley-errors-list"><li id="productGiftErr"></li></ul>
                          </div>
                      </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	商品型號(#號分開內容): 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php if($_GET["action"]=="edit") echo $proData[0]["proModelID"]; ?>" type="text" class="form-control" name="proModelID" />
                      	<ul class="parsley-errors-list"><li id="cellErr"></li></ul>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	商品規格(#號分開內容) : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php if($_GET["action"]=="edit") echo $proData[0]["proSpec"]; ?>" type="text" class="form-control" name="proSpec" />
                      	<ul class="parsley-errors-list"><li id="addrErr"></li></ul>
                      </div>
                    </div>
                    <div class="form-group">
                        <label style="text-align:left;" class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	  商品說明 : 
                        </label><br>
                        <div class="col-md-12 col-sm-12 col-xs-12">
							<div id="alerts"></div>
							<div class="btn-toolbar editor" data-role="editor-toolbar" data-target="#editor">
							  <div class="btn-group">
								<a class="btn dropdown-toggle" data-toggle="dropdown" title="Font"><i class="fa icon-font"></i><b class="caret"></b></a>
								<ul class="dropdown-menu">
								</ul>
							  </div>
							  <div class="btn-group">
								<a class="btn dropdown-toggle" data-toggle="dropdown" title="Font Size"><i class="icon-text-height"></i>&nbsp;<b class="caret"></b></a>
								<ul class="dropdown-menu">
								  <li>
									<a data-edit="fontSize 5">
									  <p style="font-size:17px">Huge</p>
									</a>
								  </li>
								  <li>
									<a data-edit="fontSize 3">
									  <p style="font-size:14px">Normal</p>
									</a>
								  </li>
								  <li>
									<a data-edit="fontSize 1">
									  <p style="font-size:11px">Small</p>
									</a>
								  </li>
								</ul>
							  </div>
							  <div class="btn-group">
								<a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="icon-bold"></i></a>
								<a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="icon-italic"></i></a>
								<a class="btn" data-edit="strikethrough" title="Strikethrough"><i class="icon-strikethrough"></i></a>
								<a class="btn" data-edit="underline" title="Underline (Ctrl/Cmd+U)"><i class="icon-underline"></i></a>
							  </div>
							  <div class="btn-group">
								<a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i class="icon-list-ul"></i></a>
								<a class="btn" data-edit="insertorderedlist" title="Number list"><i class="icon-list-ol"></i></a>
								<a class="btn" data-edit="outdent" title="Reduce indent (Shift+Tab)"><i class="icon-indent-left"></i></a>
								<a class="btn" data-edit="indent" title="Indent (Tab)"><i class="icon-indent-right"></i></a>
							  </div>
							  <div class="btn-group">
								<a class="btn" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="icon-align-left"></i></a>
								<a class="btn" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="icon-align-center"></i></a>
								<a class="btn" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="icon-align-right"></i></a>
								<a class="btn" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="icon-align-justify"></i></a>
							  </div>
							  <div class="btn-group">
								<a class="btn dropdown-toggle" data-toggle="dropdown" title="Hyperlink"><i class="icon-link"></i></a>
								<div class="dropdown-menu input-append">
								  <input class="span2" placeholder="URL" type="text" data-edit="createLink" />
								  <button class="btn" type="button">Add</button>
								</div>
								<a class="btn" data-edit="unlink" title="Remove Hyperlink"><i class="icon-cut"></i></a>
		
							  </div>
		
							  <div class="btn-group">
								<a class="btn" title="Insert picture (or just drag & drop)" id="pictureBtn"><i class="icon-picture"></i></a>
								<input type="file" data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" />
							  </div>
							  <div class="btn-group">
								<a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="icon-undo"></i></a>
								<a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="icon-repeat"></i></a>
							  </div>
							</div>
		
							<div id="editor"><?php if($_GET["action"]=="edit") echo $proData[0]["proDetail"]; ?></div>
							<textarea name="proDetail" id="descr" style="display:none;"></textarea>
							<br />
                      </div>
                    </div>
                    <div class="form-group">
                      <label style="height:100px;" class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	商品圖片(可上傳多張) : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
			              <div class="x_panel">
			                <div class="x_content">
			                  <div class="" role="tabpanel" data-example-id="togglable-tabs">
			                    <ul id="upload-method" class="nav nav-tabs bar_tabs" role="tablist">
			                      <li role="presentation" class="active"><a href="#tab_content-multiple" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">快速上傳(一次上傳多張)</a>
			                      </li>
			                      <li role="presentation" class=""><a href="#tab_content-single" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">單張上傳/從歷史相簿選擇</a>
			                      </li>
			                    </ul>
			                    <div id="preview" class="tab-content">
			                      <div role="tabpanel" class="tab-pane fade active in" id="tab_content-multiple" aria-labelledby="home-tab">
			                          <input style="display:inline-block;" name="proImage[]" type="file" multiple id="stamp-upload">
                      	  			  <span id="stampImgErr" style="color:red;"></span><br>
			                          <div id="preview-area-multiple" class="col-md-6 col-sm-6 col-xs-12">
				                        <?php 
				                        if($_GET["action"] == "edit" && !empty($proImageArray)){
				                        	foreach($proImageArray as $value){ 
												if($value != ""){
				                        ?>
											
				                        	<div class="old" style="border:2px solid #AAA;padding:5px;margin:20px;display:inline-block;text-align:center;">
				                        		<img style="max-width:300px;margin-bottom:10px;" src="<?php echo $value; ?>">
				                        		<br>
				                        		<button data-index="<?php echo $value; ?>" class="remove-img btn btn-danger">刪除</button>
				                        	</div>
				                        <?php 
												}
				                        	} 
				                        }
				                        ?>
				                      </div>
			                      </div>
			                      <div role="tabpanel" class="tab-pane fade" id="tab_content-single" aria-labelledby="profile-tab">
			                        <div>
				                      <div style="float:right;" class="btn btn-success" data-toggle="modal" data-target=".bs-example-modal-lg">從歷史相簿選擇</div>
	                      	  		  <div id="insert-one" style="float:left;" class="btn btn-success">上傳單張</div>
			                          <div class="clearfix"></div>
			                        </div>
			                        <div id="preview-area-single" class="col-md-6 col-sm-6 col-xs-12">
			                        	<?php 
				                        if($_GET["action"] == "edit" && !empty($proImageArray)){
				                        	foreach($proImageArray as $value){ 
												if($value != ""){
				                        ?>
											
				                        	<div class="old" style="border:2px solid #AAA;padding:5px;margin:20px;display:inline-block;text-align:center;">
				                        		<img style="max-width:300px;margin-bottom:10px;" src="<?php echo $value; ?>">
				                        		<br>
				                        		<button data-index="<?php echo $value; ?>" class="remove-img btn btn-danger">刪除</button>
				                        	</div>
				                        <?php 
												}
				                        	} 
				                        }
				                        ?>
			                        </div>
			                      </div>
			                    </div>
			                  </div>
			                </div>
			              </div>
			            </div>
                    </div>
                    
                    <div style="margin:30px;"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                         <button id="confirm-form" type="button" class="btn btn-primary">
                         	<?php if($_GET["action"]=="edit") echo "確認修改"; else echo "確認新增" ?>
                         </button>
                     
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
        
        	<!-- 相簿區 -->
        		<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div style="max-height:500px;" class="modal-content">

                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                        </button>
                        <h4 style="display:inline-block;" class="modal-title" id="myModalLabel">歷史相簿</h4>
                        <div style="float:right;margin-right:20px;display:none;" id="history-search-area">
                        	搜尋：
                        	<input type="text" id="history-search">
                        </div>
                      </div>
                      <div style="max-height:374px;overflow-y:scroll;" class="modal-body">
                        <form>
                        	<input type="hidden" name="proNo" value="">
                        	<div class="row">
					            <div class="col-md-12">
					              <div class="x_panel">
					                <div class="x_content">
					                  <div class="" role="tabpanel" data-example-id="togglable-tabs">
					                  	<?php 
					                	  if(!is_null(array_filter($imgArr))){
					                	?>
					                	<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
					                	  <?php 
						                	  $folderIndex = 0;
						                	  /*foreach($imgArr as $key=>$value){ 
					                	  ?>
						                      <li role="presentation" class="<?php if($folderIndex == 0) echo "active"; ?>">
						                      	<a href="#tab_content<?php echo $folderIndex; ?>" id="" role="tab" data-toggle="tab" aria-expanded="<?php if($folderIndex == 0) echo "true"; else echo "false";?>"><?php echo $key; ?></a>
						                      </li>
					                      <?php 
					                      		$folderIndex++;
					                	  	}*/ 
					                	  ?>
					                	    <li role="presentation" class="">
						                      <a href="#tab_content<?php echo $folderIndex; ?>" id="" role="tab" data-toggle="tab" aria-expanded="false">搜尋區</a>
						                    </li>
					                    </ul>
					                  <div id="myTabContent" class="tab-content">
					                  	<?php 
					                  	$tabIndex = 0;
					                  	/*foreach($imgArr as $key=>$value){ 
					                  	?>
						                  <div role="tabpanel" class="tab-pane fade <?php if($tabIndex == 0) echo "active in";?>" id="tab_content<?php echo $tabIndex; ?>">
						                    <?php foreach($imgArr[$key] as $img){?>
						                    <div class="col-md-55">
						                      <div class="thumbnail">
						                        <div class="image view view-first">
						                          <img style="width: 100%; display: block;" src="images/product/<?php echo $key."/".$img ?>" alt="image" />
						                          <div class="mask">
						                            <p> </p>
						                            <div class="tools tools-bottom">
						                              <a class="select-img" href="#">選取</a>
						                            </div>
						                          </div>
						                        </div>
						                        <div class="caption">
						                          <p style="text-align:center;"></p>
						                        </div>
						                      </div>
						                    </div>
						                    <?php } ?>
						                  </div>
						                  <?php 
						                 		$tabIndex++;
					                  	 	}*/
					                  	  ?>
					                  	  <div role="tabpanel" class="tab-pane fade" id="tab_content<?php echo $tabIndex; ?>">
						                    	請輸入商品名稱關鍵字
						                  </div>
					                    </div>
						                 <?php 
					                	 }else{
					                	 ?>
					                	 	<div class="row">尚無任何圖片</div>
					                	 <?php 	
					                	 }
					                  	 ?>
					                 </div>
					                </div>
					              </div>
					            </div>
					          </div>
                        </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
                        <button id="confirm-insert" data-dismiss="modal" type="button" class="btn btn-primary">確認</button>
                      </div>

                    </div>
                  </div>
                 </div>
                 
  <!-- richtext editor -->
  <script src="js/editor/bootstrap-wysiwyg.js"></script>
  <script src="js/editor/external/jquery.hotkeys.js"></script>
  <script src="js/editor/external/google-code-prettify/prettify.js"></script>
<script>
$(function(){
	//歷史相簿搜尋
	$(document).on("click","li[role='presentation']",function(){
		//if($(this).index() == <?php //echo $tabIndex; ?>//){
		//	$("#history-search-area").stop(true,false).fadeIn(300);
		//}else{
		//	$("#history-search-area").stop(true,false).fadeOut(300);
		//}
	});
	
	$("#history-search").on("keyup",function(){
		$.ajax({
			url:"ajax/product/get_search_history.php",
			type:"post",
			data:{"query":$("#history-search").val()},
			datatype:"json",
			success:function(result){
				var results = JSON.parse(result);
				if(results[0].indexOf("empty") != -1){
					$("#tab_content<?php echo $tabIndex; ?>").text("查無資料");
				}else{
					$("#tab_content<?php echo $tabIndex; ?>").text("");
					for(var i=0; i<results.length; i++){
						for(var n=0; n<results[i].length; n++){
							$("#tab_content<?php echo $tabIndex; ?>").append('<div class="col-md-55">'+
				                    '<div class="thumbnail">'+
				                      '<div class="image view view-first">'+
				                        '<img style="width: 100%; display: block;" src="'+results[i][n]+'" alt="image" />'+
				                        '<div class="mask">'+
				                          '<p> </p>'+
				                          '<div class="tools tools-bottom">'+
				                            '<a class="select-img" href="#">選取</a>'+
				                          '</div>'+
				                        '</div>'+
				                      '</div>'+
				                      '<div class="caption">'+
				                        '<p style="text-align:center;"></p>'+
				                      '</div>'+
				                    '</div>'+
				                  '</div>');
						}
					}
				  
				}
			}
		});
	});

	var curOrder = 0;
	//上傳單張
	$("#insert-one").on("click",function(){
		$("form").eq(0).append('<input class="single-file order'+curOrder+'" style="display:none;" type="file" name="single[]">');
		
	});
	$("body").on("DOMNodeInserted",".single-file",function(){
		$(this).click();
	});

	//相簿區
	var imgList = [];
	$(document).on("click",".select-img",function(e){
		e.preventDefault();
		$(this).removeClass("select-img").addClass("cancel-img").text("取消選取");
		$(this).parent().parent().parent().siblings(".caption").children("p").text("已選取");
		var src = $(this).parent().parent().siblings("img").attr("src");
		imgList.push(src);
	});

	$(document).on("click",".cancel-img",function(e){
		e.preventDefault();
		$(this).removeClass("cancel-img").addClass("select-img").text("選取");
		$(this).parent().parent().parent().siblings(".caption").children("p").text("");
		var src = $(this).parent().parent().siblings("img").attr("src");
		var index = imgList.indexOf(src);
		imgList.splice(index, 1);
	});
	$("#confirm-insert").click(function(){
		$('#preview-area-single div').not(".single-area").not(".old").remove();
		for(var i=0; i<imgList.length; i++){
			$('#preview-area-single').append('<div style="display:inline-block;border:2px solid #AAA;padding:5px;margin:20px;position:relative;">'+
	    	          '<img class="remove-new" style="cursor:pointer;position:absolute;top:-14px;right:-14px;background-color:#EEE;border-radius:100%;" src="images/remove_icon.png">'+
	    	          '<img class="news-insert-img" style="max-width:300px;" src="' + imgList[i] + '" />'+
	    	          '</div>');
		}
	});

	//移除單個新增
	$(document).on("click",".remove-new",function(){
		var cur = $(this);
		if($(this).parent().hasClass("single-area")){
			//上傳移除
			var index = cur.parent().attr("class").split(" ")[1].slice(-1);
			$(".order"+index).remove();
		}else{
			//相簿移除
			var theImg = $(this).siblings(".news-insert-img");
			var src = theImg.attr("src");
			var index = imgList.indexOf(src);
			var imgInAlbum = $(".cancel-img").parent().parent().siblings("img[src='"+src+"']");
			var curCancelImgArea = imgInAlbum.siblings("div").children("div").children(".cancel-img");
			curCancelImgArea.removeClass("cancel-img").addClass("select-img").text("選取");
			curCancelImgArea.parent().parent().parent().siblings(".caption").children("p").text("");
			imgList.splice(index, 1);
			theImg.parent("div").remove();
		}
	});


	//登錄新產品
	<?php if($_GET["action"] == "edit"){?>
	$("#insert-btn").click(function(e){
		e.preventDefault();
		var url = "ajax/product/insert_as.php";
		
		$.ajax({
			url:url,
			type:"post",
			data:{"proNo":<?php echo $proNo; ?>},
			success:function(result){
				alert("已成功登錄成新產品");
			}
		});
	});
	<?php } ?>

	<?php if($_GET["action"] == "edit"){?>
	//刪除圖片
	$(document).on("click",".remove-img",function(e){
		e.preventDefault();
		if(window.confirm("確定要刪除此圖片嗎？")){
			var index = $(this).attr("data-index");
			var cur = $(this);
			$.ajax({
				url:"ajax/product/delete_pic.php",
				type:"post",
				data:{"index":index,"proNo":<?php echo $proNo; ?>},
				success:function(result){
					alert(result);
					$("button[data-index='"+index+"']").parent().remove();
				}
			});
		}
	});
	<?php } ?>

	$("#descr").val("");
	$("#confirm-form").click(function(e){
		$('#descr').val($('#editor').html());
		$("#stampImgErr").text("");
		$(".parsley-errors-list li").text("");
		e.preventDefault();

		//清空空白input
		for(var i=0; i<$(".single-file").length; i++){
			if($(".single-file").eq(i).get(0).files.length == 0){
				$(".single-file").eq(i).remove();
			}
		}

		if($("#upload-method li").eq(1).hasClass("active")){
			$("#imgMethod").val("album");
			$("#album").val(imgList);
		}else{
			$("#imgMethod").val("upload");
		}
		var form = new FormData($("form")[0]);
		var url = "ajax/product/<?php echo $_GET["action"]; ?>.php";
		var redirect = "?page=product&type=product<?php if($_GET["action"]=="edit") echo "&action=view&prono=".$proNo; ?>";
		var insertUrl = "?page=product&type=product&action=insert";
		var manageUrl = "?page=product&type=productManage&action=insert&procaseno="
		
		$.ajax({
			url:url,
			type:"post",
			data:form,
			datatype:"json",
			contentType:false,
			processData: false,
			success:function(result){
				var results = JSON.parse(result);
                if(results.errMsg != ""){
				// if(!results.status){
					addError($("#catErr"),results.errMsg.catNoErr);
					addError($("#braErr"),results.errMsg.braNoErr);
                    addError($("#biErr"),results.errMsg.biNoErr);
					addError($("#nameErr"),results.errMsg.proNameErr);
					addError($("#stampImgErr"),results.errMsg.supStampImgErr);
				}else if(results.errMsg == ""){
                // } else {
					alert(results.success);
					<?php if($_GET["action"]=="edit"){ ?>
						location.href= redirect;
					<?php }else{ ?>
					if(window.confirm("是否繼續新增？")){
						location.href= insertUrl;
					}else{
						location.href= manageUrl + results.procaseno;
					}
					<?php } ?>
				}
			}
		});
	});

	//多張圖片上傳預覽
	$("#stamp-upload").change(function(){
		readMultipleURL(imgList,this);
	});

	//單張
	$(document).on("change",".single-file",function(){
	    readURL(this,curOrder);
	    curOrder++;
	});
});
//讀單張
function readURL(input,curOrder) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
        	$('#preview-area-single').append('<div class="single-area order'+curOrder+'" style="display:inline-block;border:2px solid #AAA;padding:5px;margin:20px;position:relative;">'+
	    	          '<img class="remove-new" style="cursor:pointer;position:absolute;top:-14px;right:-14px;background-color:#EEE;border-radius:100%;" src="images/remove_icon.png">'+
	    	          '<img class="news-insert-img" style="max-width:300px;" src="' + e.target.result + '" />'+
	    	          '</div>');
        }

        reader.readAsDataURL(input.files[0]);
    }
}
//讀多張
function readMultipleURL(imgList,input){
	$('#preview-area-multiple div').remove();
// 	$(".select-img").text("選取");
// 	$(".cancel-img").removeClass("cancel-img").addClass("select-img").text("選取");
// 	$(".caption p").text("");
// 	imgList.splice(0,imgList.length);
	
	if (input.files && input.files[0]) {
		var fileList = input.files;
	
	    var anyWindow = window.URL || window.webkitURL;
	
	        for(var i = 0; i < fileList.length; i++){
	          //get a blob to play with
	          var objectUrl = anyWindow.createObjectURL(fileList[i]);
	          // for the next line to work, you need something class="preview-area" in your html
	          $('#preview-area-multiple').append('<div style="display:inline-block;border:2px solid #AAA;padding:5px;margin:20px;position:relative;">'+
	    	          '<img style="max-width:300px;" src="' + objectUrl + '" />'+
	    	          '</div>');
	          // get rid of the blob
	          window.URL.revokeObjectURL(fileList[i]);
	        }
	}
}
function addError(selector, errMsg){
	selector.text(errMsg);
}

$(function() {
    function initToolbarBootstrapBindings() {
      var fonts = ['Serif', 'Sans', 'Arial', 'Arial Black', 'Courier',
          'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Lucida Grande', 'Lucida Sans', 'Tahoma', 'Times',
          'Times New Roman', 'Verdana'
        ],
        fontTarget = $('[title=Font]').siblings('.dropdown-menu');
      $.each(fonts, function(idx, fontName) {
        fontTarget.append($('<li><a data-edit="fontName ' + fontName + '" style="font-family:\'' + fontName + '\'">' + fontName + '</a></li>'));
      });
      $('a[title]').tooltip({
        container: 'body'
      });
      $('.dropdown-menu input').click(function() {
          return false;
        })
        .change(function() {
          $(this).parent('.dropdown-menu').siblings('.dropdown-toggle').dropdown('toggle');
        })
        .keydown('esc', function() {
          this.value = '';
          $(this).change();
        });

      $('[data-role=magic-overlay]').each(function() {
        var overlay = $(this),
          target = $(overlay.data('target'));
        overlay.css('opacity', 0).css('position', 'absolute').offset(target.offset()).width(target.outerWidth()).height(target.outerHeight());
      });
      if ("onwebkitspeechchange" in document.createElement("input")) {
        var editorOffset = $('#editor').offset();
        $('#voiceBtn').css('position', 'absolute').offset({
          top: editorOffset.top,
          left: editorOffset.left + $('#editor').innerWidth() - 35
        });
      } else {
        $('#voiceBtn').hide();
      }
    };

    function showErrorAlert(reason, detail) {
      var msg = '';
      if (reason === 'unsupported-file-type') {
        msg = "Unsupported format " + detail;
      } else {
        console.log("error uploading file", reason, detail);
      }
      $('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>' +
        '<strong>File upload error</strong> ' + msg + ' </div>').prependTo('#alerts');
    };
    initToolbarBootstrapBindings();
    $('#editor').wysiwyg({
      fileUploadError: showErrorAlert
    });
    window.prettyPrint && prettyPrint();
  });
</script>