<?php 
require_once('model/require_general.php');

$type = $_GET["type"];

$fm = new Front_Manage();
$fm2 = new Front_Manage2();

$fmData = $fm->getAllFM();
$fm2Data = $fm2->getAllFM();

$which = 1;




?>
	<link href="http://netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css" rel="stylesheet">
	<link href="css/editor/external/google-code-prettify/prettify.css" rel="stylesheet">
	<link href="css/editor/index.css" rel="stylesheet">

	<!-- page content -->
	<div>
      <div class="right_col" role="main">
        <div class="">
          <div style="width:100%;" class="page-title">
            <div style="width:100%;" class="title_left">
              <h3>
              <?php 
              switch($type){ 
              	case "fmBuyStep":
              		echo "購物流程";
              		break;
              	case "fmFreeRespons":
              		echo "免責聲明";
              		break;
              	case "fmServiceRules":
              		echo "服務條款";
              		break;
              	case "fmPrivacy":
              		echo "隱私聲明";
              		break;
              	case "fmRecBonus":
              		echo "什麼是推薦碼";
              		break;
				case "fmLoanVIP":
              		echo "貸款VIP服務";
              		break;
				case "fmInstallPromise":
              		echo "分期付款約定事項";
              		break;
              	case "fmDirectBuyRules":
              		echo "直購流程";
              		$which = 2;
              		break;
              	case "fmContactService":
              		echo "聯絡客服";
              		$which = 2;
              		break;
              	case "fmCoopDetail":
              		echo "合作提案";
              		$which = 2;
              		break;
              	case "fmBuyMustKnow":
              		echo "購買須知";
              		$which = 2;
              		break;
              	case "fmPeriodDeclare":
              		echo "分期付款約定書";
              		$which = 2;
              		break;
              	case "fmAboutUs":
              		echo "關於我們";
              		$which = 2;
              		break;
              }?>
              	編輯頁
              </h3>
			  <?php if($type == "fmPeriodDeclare"){?>
				<span style='color:red;font-size:16px;padding:10px 0;'>只能修改前台資訊，後台列印版本請工程師修改</span>
			  <?php } ?>
            </div>

          </div>
          <div class="clearfix"></div>

          <div class="row">
			<form action="ajax/service/email.php" method="POST">
			<input type="hidden" name="type" value="<?php echo $type; ?>">
            <div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
				  <div class="x_title">
					<h2>頁面編輯</h2>
					<div class="clearfix"></div>
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

					  <!-- <div class="btn-group">
						<a class="btn" title="Insert picture (or just drag & drop)" id="pictureBtn"><i class="icon-picture"></i></a>
						<input type="file" data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" />
					  </div> -->
					  <div class="btn-group">
						<a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="icon-undo"></i></a>
						<a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="icon-repeat"></i></a>
					  </div>
					</div>
					<?php if($which == 1){ ?>
					<div id="editor"><?php echo $fmData[0][$type] ?></div>
					<?php }else{ ?>
		    		<div id="editor"><?php echo $fm2Data[0][$type] ?></div>
		    		<?php } ?>
					<textarea name="<?php echo $type ?>" id="descr" style="display:none;"></textarea>
					<br />
					<button type="submit" class="btn btn-success">更新</button>

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
    	$('button[type="submit"]').click(function(e) {
        	e.preventDefault();
	        $('#descr').val($('#editor').html());
    	    var data = $("form").serialize();
    	    <?php if($which == 1){ ?>
    	    $.post("ajax/frontManage/edit.php",data,function(result){
    			alert(result);
    		});
    		<?php }else{ ?>
    		$.post("ajax/frontManage/edit2.php",data,function(result){
    			alert(result);
    		});
    		<?php } ?>
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