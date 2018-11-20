<?php 
require_once('model/require_general.php');

$member = new Member();
$allData = $member->getAllMember();

?>
	<link href="http://netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css" rel="stylesheet">
	<link href="css/editor/external/google-code-prettify/prettify.css" rel="stylesheet">
	<link href="css/editor/index.css" rel="stylesheet">
	<style>
	#user-list{
	    list-style: none;
	    border: 1px solid #CCC;
	    position: absolute;
	    width: 523px;
	    right: 22px;
	    padding: 5px;
	    z-index: 999;
	    background-color:#FFF;
	    display:none;
	}
	#user-list li{
		-moz-user-select: none; /* Firefox */
      	-ms-user-select: none; /* Internet Explorer */
   		-khtml-user-select: none; /* KHTML browsers (e.g. Konqueror) */
  		-webkit-user-select: none; /* Chrome, Safari, and Opera */
  		-webkit-touch-callout: none; /* Disable Android and iOS callouts*/
	}
	#user-list li.active{
		background-color:#73879C;
		color:#FFF;
		cursor:default;
	}
	#user-list li:hover{
		background-color:#73879C;
		color:#FFF;
		cursor:default;
	}
	
	
	</style>
			<div class="black-box">
				<div class="warning-box">
					<div class="warning-text">
						
					</div>
				</div>
			</div>
	<!-- page content -->
	<div>
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3 style="font-size:20px;">Email發送</h3>
            </div>
          </div>
          <div class="clearfix"></div>

          <div class="row">
			<form action="ajax/service/email.php" method="POST">
            <div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
				<?php if(!isset($_GET["memno"])){ ?>
				  <div class="x_title">
					<h2>發送對象</h2>
					<div style="float:right;">
                    	<label style="margin-right:20px;">搜尋使用者: </label>
                    	<input autocomplete="off" id="search-user" type="text" size="70" placeholder="可用會員編號、帳號、姓名查詢">
                    	<ul id="user-list">
                    	</ul>
                    </div>
					  <div id="who-to-send" class="x_content">
					  	<div class="checkbox">
	                      <label>
	                        <input id="all" name="sendAll" value="all" type="checkbox" class="flat"> 所有人
	                      </label>
	                    </div>
	                    <div style="margin:10px 0;">已選擇：</div>
					  </div>
					<div class="clearfix"></div>
				  </div>
				  <?php 
					}else{
						$memData = $member->getOneMemberByNo($_GET["memno"]);
						$memEmail = "";
						if($memData[0]["memClass"] == 0){
							$memEmail = $memData[0]["memSubEmail"];
						}else{
							$memEmail = $memData[0]["memAccount"];
						}
				  ?>
				  
				  <div class="x_title">
					  <h2>發送對象</h2>
					  <div id="who-to-send" class="x_content">
					  	<span class="tag">
						  	<input type="hidden" name="receiverMail[]" value="<?php echo $memEmail; ?>">
						  	<input type="hidden" name="receiverName[]" value="<?php echo $memData[0]["memName"]; ?>">
						  	<span style="color:#FFF;"><?php echo $memEmail; ?>　<?php echo $memData[0]["memName"]; ?>&nbsp;&nbsp;</span>
					  	</span>
					  </div>
					  <div class="clearfix"></div>
				  </div>
				  <?php }?>
				  
				  <div class="form-group">
                      <label style="font-size:20px;font-weight:initial;" class="control-label col-xs-12" for="first-name">
                      	標題 : 
                      </label>
                      <div class="col-md-12 col-sm-6 col-xs-12">
                      	<input required="required" type="text" class="form-control" name="title" />
                        <ul class="parsley-errors-list"><li id="nameErr"></li></ul>
                      </div>
                  </div>
				  <div class="form-group">
                    <label style="font-size:20px;font-weight:initial;" class="control-label col-xs-12" for="first-name">
                     	內容 : 
                    </label>
                  </div>
				  <div class="x_content">
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

					<div id="editor">

					</div>
					<textarea name="content" id="descr" style="display:none;"></textarea>
					<br />
					<button type="submit" class="btn btn-success">發送</button>

				  </div>
				</div>
            </div>
            </form>
          </div>
        </div>
  <!-- richtext editor -->
  <script src="js/editor/bootstrap-wysiwyg.js"></script>
  <script src="js/editor/external/jquery.hotkeys.js"></script>
  <script src="js/editor/external/google-code-prettify/prettify.js"></script>
  <!-- editor -->
  <script>
    $(document).ready(function() {
		$("#descr").val("");
		
        //防止按enter鍵
    	$(window).keydown(function(event){
    	    if(event.keyCode == 13 && $("#search-user:focus").length>0) {
    	      event.preventDefault();
    	      return false;
    	    }
    	});
		//增加收件人tag
	    $(document).on("input","#search-user",function(){
			$.post("ajax/service/search_user.php", {"search":$("#search-user").val()}, function(result){
				if(result == "[]"){
					$("#user-list").hide();
					$("#user-list li").remove();
				}else{
					$("#user-list li").remove();
					$("#user-list").show();
					var results = JSON.parse(result);
					$.each(results,function(k,v){
						var ifExist = false;
						$(".tag span").each(function(){
							if($(this).text().indexOf(v.memAccount) != -1){
								ifExist = true;
							}
						});
						if(ifExist){
							$("#user-list").append('<li class="active">'+v.memNo+'　'+v.memAccount+'　'+v.memName+'</li>');
						}else{
							$("#user-list").append('<li>'+v.memNo+'　'+v.memAccount+'　'+v.memName+'</li>');
						}
					});
				}
			});
		});
	    $(document).on("focus","#search-user",function(){
	    	$.post("ajax/service/search_user.php", {"search":$("#search-user").val()}, function(result){
				if(result == "[]"){
					$("#user-list").hide();
					$("#user-list li").remove();
				}else{
					$("#user-list li").remove();
					$("#user-list").show();
					var results = JSON.parse(result);
					$.each(results,function(k,v){
						var ifExist = false;
						$(".tag span").each(function(){
							if($(this).text().indexOf(v.memAccount) != -1){
								ifExist = true;
							}
						});
						if(ifExist){
							$("#user-list").append('<li class="active">'+v.memNo+'　'+v.memAccount+'　'+v.memName+'</li>');
						}else{
							$("#user-list").append('<li>'+v.memNo+'　'+v.memAccount+'　'+v.memName+'</li>');
						}
					});
				}
			});
		});
	    $(document).on("blur","#search-user",function(){
	    	$("#user-list").hide();
			$("#user-list li").remove();
		});
		//點選加入標籤
		$(document).on("mousedown","#user-list li",function(){
			var email = $(this).text().split("　")[1];
			var name = $(this).text().split("　")[2];
			if($(this).hasClass("active")){
				$("span.tag span").each(function(){
					if($(this).text().indexOf(email) != -1){
						$(this).parent().remove();
					}
				});
			}else{
				$("#who-to-send").append('<span class="tag">'+
						'<input type="hidden" name="receiverMail[]" value="'+email+'">'+
						'<input type="hidden" name="receiverName[]" value="'+name+'">'+
	                    '<span style="color:#FFF;">'+email+'　'+name+'&nbsp;&nbsp;</span>'+
	                    '<a href="#" class="remove-tag">x</a>'+
	                '</span>');
			}
		});
		//移除標籤
		$(document).on("click",".remove-tag",function(){
			$(this).parent().remove();
		});

        //發送時
        $('button[type="submit"]').click(function(e) {
        	e.preventDefault();
	        $('#descr').val($('#editor').html());
            $(".warning-text").text("發送中...");
            $(".black-box").fadeIn(500,function(){
    	        var data = $("form").serialize();
    	        $.post("ajax/service/email.php",data,function(result){
    				alert(result);
    				$(".black-box").fadeOut(500);
    		    });
            });
        });
    });

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
  <!-- /editor -->