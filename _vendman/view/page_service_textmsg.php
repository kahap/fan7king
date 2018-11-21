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
              <h3 style="font-size:20px;">手機簡訊發送</h3>
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
				  ?>
				  
				  <div class="x_title">
					  <h2>發送對象</h2>
					  <div id="who-to-send" class="x_content">
					  	<span class="tag">
						  	<input type="hidden" name="receiverMail[]" value="<?php echo $memData[0]["memCell"]; ?>">
						  	<input type="hidden" name="receiverName[]" value="<?php echo $memData[0]["memName"]; ?>">
						  	<span style="color:#FFF;"><?php echo $memData[0]["memCell"]; ?>　<?php echo $memData[0]["memName"]; ?>&nbsp;&nbsp;</span>
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

<!-- 					<div id="editor"> -->

<!-- 					</div> -->
					<textarea id="editor" name="content" style="width:99%;"></textarea>
					<br />
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
		
        //防止按enter鍵
    	$(window).keydown(function(event){
    	    if(event.keyCode == 13 && $("#search-user:focus").length>0) {
    	      event.preventDefault();
    	      return false;
    	    }
    	});
		//增加收件人tag
	    $(document).on("input","#search-user",function(){
			$.post("ajax/service/search_user_textmsg.php", {"search":$("#search-user").val()}, function(result){
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
							if($(this).text().indexOf(v.memCell) != -1){
								ifExist = true;
							}
						});
						if(ifExist){
							$("#user-list").append('<li class="active">'+v.memNo+'　'+v.memCell+'　'+v.memName+'</li>');
						}else{
							$("#user-list").append('<li>'+v.memNo+'　'+v.memCell+'　'+v.memName+'</li>');
						}
					});
				}
			});
		});
	    $(document).on("focus","#search-user",function(){
	    	$.post("ajax/service/search_user_textmsg.php", {"search":$("#search-user").val()}, function(result){
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
							if($(this).text().indexOf(v.memCell) != -1){
								ifExist = true;
							}
						});
						if(ifExist){
							$("#user-list").append('<li class="active">'+v.memNo+'　'+v.memCell+'　'+v.memName+'</li>');
						}else{
							$("#user-list").append('<li>'+v.memNo+'　'+v.memCell+'　'+v.memName+'</li>');
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
// 	        $('#descr').val($('#editor').html());
            $(".warning-text").text("發送中...");
            $(".black-box").fadeIn(500,function(){
    	        var data = $("form").serialize();
    	        $.post("ajax/service/textmsg.php",data,function(result){
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
//       initToolbarBootstrapBindings();
//       $('#editor').wysiwyg({
//         fileUploadError: showErrorAlert
//       });
      window.prettyPrint && prettyPrint();
    });
  </script>
  <!-- /editor -->