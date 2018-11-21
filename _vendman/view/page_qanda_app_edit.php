<?php 
require_once('model/require_general.php');

if($_GET["action"] == "edit"){
	$qaa = new Qa_App();
	$qaaNo = $_GET["qaano"];
	$qaData = $qaa->getOne($qaaNo);
}

?>
	<link href="http://netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css" rel="stylesheet">
	<link href="css/editor/external/google-code-prettify/prettify.css" rel="stylesheet">
	<link href="css/editor/index.css" rel="stylesheet">
	<style>
	
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
              	<?php if($_GET["action"]=="edit"){ ?>
	              <h3>編輯APP問答</h3>
	            <?php }else{ ?>
	              <h3>新增APP問答</h3>
	            <?php } ?>
            </div>
          </div>
          <div class="clearfix"></div>

          <div class="row">
			<form method="POST">
            <div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<?php if($_GET["action"]=="edit"){ ?>
					  <input type="hidden" name="qaaNo" value="<?php echo $qaaNo; ?>">
					<?php } ?>
				  <div class="form-group">
                      <label style="font-size:20px;font-weight:initial;" class="control-label col-xs-12" for="first-name">
                      	問題 : 
                      </label>
                      <div class="col-md-12 col-sm-6 col-xs-12">
                      	<input value="<?php if($_GET["action"]=="edit") echo $qaData[0]["qaaQues"]; ?>" required="required" type="text" class="form-control" name="qaaQues" />
                        <ul class="parsley-errors-list"><li id="qaaQuesErr"></li></ul>
                      </div>
                  </div>
				  <div class="form-group">
                    <label style="font-size:20px;font-weight:initial;" class="control-label col-xs-12" for="first-name">
                     	解答 : 
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

					<div id="editor"><?php if($_GET["action"]=="edit") echo $qaData[0]["qaaAnsw"]; ?></div>
					<textarea name="qaaAnsw" id="descr" style="display:none;"></textarea>
					<br />
					<div class="form-group">
                      <label style="font-size:20px;font-weight:initial;" class="control-label col-xs-12" for="first-name">
                      	排序 : 
                      </label>
                      <div class="col-md-12 col-sm-6 col-xs-12">
                      	<input value="<?php if($_GET["action"]=="edit") echo $qaData[0]["qaaOrder"]; ?>" required="required" type="text" class="form-control" name="qaaOrder" />
                        <ul class="parsley-errors-list"><li id="qaaQuesErr"></li></ul>
                      </div>
                    </div>
					<div class="form-group">
                      <label style="font-size:20px;font-weight:initial;" class="control-label col-xs-12" for="first-name">
                      	是否顯示 : 
                      </label>
                      <div class="col-md-12 col-sm-6 col-xs-12">
                      	<select name="qaaIfShow" class="form-control">
                          <option <?php if($_GET["action"]=="edit" && $qaData[0]["qaaIfShow"]==1) echo "selected"; ?> value="1">是</option>
                          <option <?php if($_GET["action"]=="edit" && $qaData[0]["qaaIfShow"]==0) echo "selected"; ?> value="0">否</option>
                        </select>
                        <ul class="parsley-errors-list"><li id="qaaQuesErr"></li></ul>
                      </div>
                    </div>
					<button type="submit" class="btn btn-success"><?php if($_GET["action"]=="edit") echo "確認修改"; else echo "確認新增" ?></button>

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

		//發送時
		$("button[type='submit']").click(function(e){
			e.preventDefault();
			
			$(".parsley-errors-list li").text("");
			$('#descr').val($('#editor').html());
			
			var form = $("form").serialize();
			var url = "ajax/qanda_app/<?php echo $_GET["action"]; ?>.php";
			var redirect = "?page=qaapp<?php if($_GET["action"]=="edit") echo "&action=view&qaano=".$qaaNo; if($_GET["action"]=="insert") echo "&pageIndex=last" ?>";
			
			$.ajax({
				url:url,
				type:"post",
				data:form,
				datatype:"json",
				success:function(result){
					var results = JSON.parse(result);
					if(results.errMsg != ""){
						addError($("#qaaQuesErr"),results.errMsg.qaaQuesErr);
					}else if(results.errMsg == ""){
						alert(results.success);
						location.href= redirect;
					}
				}
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
    function addError(selector, errMsg){
    	selector.text(errMsg);
    }
  </script>
  <!-- /editor -->