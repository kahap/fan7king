<!--<script type="text/javascript" src="assets/js/jquery.form.js"></script>
<script type="text/javascript" src="assets/js/sketch.js"></script>-->
<script type="text/javascript" src="assets/js/jquery.form.js"></script>
		<script src="assets/draw/jquery.jqscribble.js" type="text/javascript"></script>
		<script src="assets/draw/jqscribble.extrabrushes.js" type="text/javascript"></script>
	<script>
		$(document).ready(function()
		{
			$("#colors_sketch").jqScribble();
			$("#colors_sketch_1").jqScribble();
		});
	</script>
<style>
	.btn, .btn_1, .btn_2, .btn_3, .btn_4{position: relative;overflow: hidden;margin-right: 4px;display:inline-block; 
			*display:inline;padding:4px 10px 4px;font-size:14px;line-height:18px; 
			*line-height:20px;color:#fff; 
			text-align:center;vertical-align:middle;cursor:pointer;background:#5bb75b; 
			border:1px solid #cccccc;border-color:#e6e6e6 #e6e6e6 #bfbfbf; 
			border-bottom-color:#b3b3b3;-webkit-border-radius:4px; 
			-moz-border-radius:4px;border-radius:4px;
	} 
	.btn input, .btn_1 input, .btn_2 input, .btn_3 input, .btn_4 input{position: absolute;top: 0; right: 0;margin: 0;border:solid transparent; 
		opacity: 0;filter:alpha(opacity=0); cursor: pointer;
	} 
	.progress, .progress_1, .progress_2, .progress_3, .progress_4{position:relative; margin-left:100px; margin-top:-24px;  
		width:200px;padding: 1px; border-radius:3px; display:none
	} 
	.bar, .bar_1, .bar_2, .bar_3, .bar_4{background-color: green; display:block; width:0%; height:20px;  
		border-radius:3px; 
	} 
	.percent, .percent_1, .percent_2, .percent_3, .percent_4{position:absolute; height:20px; display:inline-block;  
		top:3px; left:2%; color:#fff } 
	.files, .files_1, .files_2, .files_3, .files_4{height:22px; line-height:22px; margin:10px 0} 
	.delimg, .delimg_1, .delimg_2, .delimg_3, .delimg_4{margin-left:20px; color:#090; cursor:pointer}
	label {
		display: inline-block;
		cursor: pointer;
		position: relative;
		padding-left: 25px;
		margin-right: 15px;
		font-size: 14px;
	}
	label:before {
		content: "";
		display: inline-block;
		width: 16px;
		height: 16px;
		margin-right: 10px;
		position: absolute;
		left: 0;
		bottom: 1px;
		background-color: #aaa;
		box-shadow: inset 0px 2px 3px 0px rgba(0, 0, 0, .3), 0px 1px 0px 0px rgba(255, 255, 255, .8);
	}
	input[type=checkbox] {
		display: none;
	}
	.checkbox label {
		margin-bottom: 10px;
	}
	.checkbox label:before {
		border-radius: 3px;
	}
	input[type=checkbox]:checked + label:before {
		content: "\2713";
		text-shadow: 1px 1px 1px rgba(0, 0, 0, .2);
		font-size: 15px;
		color: #f3f3f3;
		text-align: center;
		line-height: 15px;
	}
</style>

<!-- page wapper-->
<div class="columns-container">
    <div class="container" id="columns">
        <!-- breadcrumb -->
        <div class="breadcrumb clearfix">
            <a class="home" href="#" title="Return to Home">Home</a>
            <span class="navigation-pipe">&nbsp;</span>
            <span class="navigation_page">分期購買流程</span>
        </div>
        <!-- ./breadcrumb -->
        <!-- page heading-->
		<?php
			//print_r($or_data);
			$member = new Member();
			$memberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
			$or_dataDealFinish = $or->getDealFinish($_SESSION['user']['memNo'],'1');
			$year = explode('-',$memberData[0]['memBday']);
			//欄位名稱
			//print_r($columnName);
		?>
        <!-- ../page heading-->
        <div class="page-content page-order">
            <ul class="step">
                <li class=""><img src="assets/images/B-B-1.png"/></li>
                <li class="current-step"><img src="assets/images/B-C-3.png"/></li>
                <li class=""><img src="assets/images/B-B-4.png"/></li>
				<?php
					if(in_array($memberData['0']['memFBtoken'],$fb_token)){
				?>
					<li class=""><img src="assets/images/B-B-6.png"/></li>
				<?php
					}
				?>
				<li class=""><img src="assets/images/B-B-5.png"/></li>
            </ul>
            <div class="heading-counter warning">簽名及附件上傳
                <span style="">*為必填欄位，請務必詳實填寫，如未滿20歲,需父母同意分期購買</span>
            </div>
			<div class="heading-counter warning">
                <span style="color:red;">***上傳證件時請確認圖檔「不反光」且「對焦清楚」要近拍，以利案件申請***</span>
            </div>
			<div class="box-border">
				<ul>
					<div class="row">
						<div class="col-sm-12">
							<form id="order_add">
								<span>身分證發證日期</span>
								<select style="width: 60px;border-style: groove;" name="orIdIssueYear">
								<?php 
									$year = date('Y',time())-1911;
									for($i=50;$i<=$year;$i++){ ?>
									<option value="<?=$i ?>" <?php echo ($or_data[1]["orIdIssueYear"] == $i) ? 'selected':''; ?>><?=$i?></option>
								<?php } ?>
								</select>
								<span>年</span>
								
								<select style="width: 60px;border-style: groove;" name="orIdIssueMonth">
								<?php
									for($i=1;$i<=12;$i++){ ?>
									<option value="<?=$i ?>" <?php echo ($or_data[1]["orIdIssueMonth"] == $i) ? 'selected':''; ?>><?=$i?></option>
								<?php } ?>
								</select>
								<span>月</span>
								
								<select style="width: 60px;border-style: groove;" name="orIdIssueDay">
								<?php
								for($i=1;$i<=31;$i++){ ?>
									<option value="<?=$i ?>" <?php echo ($or_data[1]["orIdIssueDay"] == $i) ? 'selected':''; ?>><?=$i?></option>
								<?php } ?>
								</select>
								<span>日</span>
								<br>
								
								<span>發證地點</span>
								<select class="input form-control" name="orIdIssuePlace">
									<option value="">請選擇</option>
									<?php 
										foreach($IdPlace as $key => $value){
										$IdSelected = ($value == $or_data[1]['orIdIssuePlace']) ? "selected":"";
									?>
										<option value="<?php echo $value; ?>" <?php echo $IdSelected?>><?php echo $value; ?></option>
									<?php
										}
									?>
								</select>
								
								<span>補換發類別</span>
								<select class="input form-control" name="orIdIssueType">
									<option value="初發" <?php echo ($or_data[1]['orIdIssueType'] == "初發") ? "selected":""; ?>>初發</option>
									<option value="補發" <?php echo ($or_data[1]['orIdIssueType'] == "初發") ? "selected":""; ?>>補發</option>
									<option value="換發" <?php echo ($or_data[1]['orIdIssueType'] == "初發") ? "selected":""; ?>>換發</option>
								</select>
							</form>
							<h3>證件資料上傳</h3>
							<div class="demo">
								<p>說明：圖片大小不能超過10M。</p>
								<div class="btn">
									<span>申請人身份證正面：</span>
									<input id="fileupload" type="file" name="mypic">
								</div>
								<div class="progress">
									<span class="bar"></span><span class="percent">0%</span >
								</div>
								<div class="files"></div>
								<div id="showimg">
								<?php 
									if($or_data[1]['orAppAuthenIdImgTop'] ==""){ 
										echo "<img src='".$or_dataDealFinish[0]['orAppAuthenIdImgTop']."' />";
									}else{
										echo "<img src='".$or_data[1]['orAppAuthenIdImgTop']."' />";
									}
								?>
								</div>
						   </div>
						   <div class="demo">
								<div class="btn_1">
									<span>申請人身份證反面：</span>
									<input id="fileupload_1" type="file" name="mypic_1">
								</div>
								<div class="progress_1">
									<span class="bar_1"></span><span class="percent_1">0%</span >
								</div>
								<div class="files_1"></div>
								<div id="showimg_1">
								<?php 
									if($or_data[1]['orAppAuthenIdImgBot'] ==""){ 
										echo "<img src='".$or_dataDealFinish[0]['orAppAuthenIdImgBot']."' />";
									}else{
										echo "<img src='".$or_data[1]['orAppAuthenIdImgBot']."' />";
									}
								?>
								</div>
						   </div>
						   <?php
							if($memberData[0]['memClass'] == 0){
						   ?>
						   <div class="demo">
								<div class="btn_2">
									<span>申請人學生證正面：</span>
									<input id="fileupload_2" type="file" name="mypic_2">
								</div>
								<div class="progress_2">
									<span class="bar_2"></span><span class="percent_2">0%</span >
								</div>
								<div class="files_2"></div>
								<div id="showimg_2">
								<?php 
									if($or_data[1]['orAppAuthenStudentIdImgTop'] ==""){ 
										echo "<img src='".$or_dataDealFinish[0]['orAppAuthenStudentIdImgTop']."' />";
									}else{
										echo "<img src='".$or_data[1]['orAppAuthenStudentIdImgTop']."' />";
									}
								?>
								</div>
						   </div>
						   <div class="demo">
								<div class="btn_3">
									<span>申請人學生證反面：</span>
									<input id="fileupload_3" type="file" name="mypic_3">
								</div>
								<div class="progress_3">
									<span class="bar_3"></span><span class="percent_3">0%</span >
								</div>
								<div class="files_3"></div>
								<div id="showimg_3">
								<?php 
									if($or_data[1]['orAppAuthenStudentIdImgBot'] ==""){ 
										echo "<img src='".$or_dataDealFinish[0]['orAppAuthenStudentIdImgBot']."' />";
									}else{
										echo "<img src='".$or_data[1]['orAppAuthenStudentIdImgBot']."' />";
									}
								?>
								</div>
						   </div>
						   <?php
							}
						   ?>
						   <div class="demo">
								<div class="btn_4">
									<span>補件資料：</span>
									<input id="fileupload_4" type="file" name="mypic_4">
								</div>
								<div class="progress_4">
									<span class="bar_4"></span><span class="percent_4">0%</span >
								</div>
								<div class="files_4"></div>
								<div id="showimg_4">
								<!--<?php 
									/*if($or_data[1]['orAppAuthenExtraInfo'] ==""){ 
										echo "<img src='".$or_dataDealFinish[0]['orAppAuthenExtraInfo']."' />";
									}else{
										echo "<img src='".$or_data[1]['orAppAuthenExtraInfo']."' />";
									}*/
								?>-->
								</div>
						   </div>
						   
						   <h3>請於下面兩個簽名處，以滑鼠或手寫功能簽上正楷簽名</h3>
						   <div class="table-responsive" style="border:2px solid #000;padding: 15px;">
								<span>本票：</span>
								<span>憑票於中華民國  年  月  日無條件支付</span>
								<span style="">廿一世紀數位科技有限公司</span>或指定人<br>
										新台幣　
										<span style=""><?php  
											$ex = preg_split("//", $or_data[0]['orPeriodTotal']);
											$ln = strlen($or_data[0]['orPeriodTotal']);
											$count = count($ex);
											echo ($ln > 5) ? $coin[$ex[1]]:'零';
										?></span>
										<span>
										&nbsp;拾&nbsp;
										<span style=""><?php echo ($ln == 5 ) ? $coin[$ex[1]]:'零'; ?></span>
										&nbsp;萬&nbsp;
										<span style=""><?php echo $coin[$ex[2]]; ?></span>
										&nbsp;仟&nbsp;
										<span style=""><?php echo $coin[$ex[3]]; ?></span>
										&nbsp;佰&nbsp;
										<span style=""><?php echo $coin[$ex[4]]; ?></span>
										&nbsp;拾&nbsp;
										<span style=""><?php echo $coin[$ex[5]]; ?></span>
										&nbsp;&nbsp;　元整<br>
										此總額為您選擇之月付金X期數<br>
										此本票免除作成拒絕證書及票據法第八十九條之通知義務，<br>
										利息自到期日迄清償日止按年利率百分之二十計付，<br>
										發票人授權持票人得填載到期日。<br>
										付款地：台北市信義區基隆路一段163號2樓之2<br>
										此據<br>
										中華民國 <?php echo date('Y',strtotime($or_data[0]['orDate']))-1911; ?> 年 <?php echo date('m',strtotime($or_data[0]['orDate'])); ?> 月 <?php echo date('d',strtotime($or_data[0]['orDate'])); ?> 日
										</span><br>
								<span>約定說明：<span style=";">「此本票係供為分期付款買賣之分期款項總額憑證，俟分期付款完全清償完畢時，此本票自動失效，但如有一期未付，發票人願意就全部本票債務負責清償。」本人同意依法令規定應以書面為之者,得以電子文件為之.依法令規定應簽名或蓋章者，得以電子簽章為之。</span>
								</span><br>
									<h3>發票人中文正楷簽名：</h3>
									<canvas id="colors_sketch" style="border: 1px solid red;"></canvas>
									<button class="button" onclick='$("#colors_sketch").data("jqScribble").clear();'>清除</button>
									<a id="upload"><button class="button">確認簽名</button></a>
									<?php 
									if ($or_data[0]['orAppAuthenProvement'] != "") echo "<img src='".$or_data[0]['orAppAuthenProvement']."' id='orAppAuthenProvement' />";
									?>
						   </div><br><br><br>
						   
						   <div class="table-responsive" style="border:2px solid #000;padding:10px;padding: 15px;">
						   <span style=";">★分期付款期間未繳清以前禁止出售或典當，以免觸法</span><br>
								<span>分期付款約定事項：</span>
								<span>一、	申請人(即買方)及其連帶保證人向商品經銷商(即賣方)以分期付款方式購買消費性商品，並簽約本「分期付款申請書暨約定書」，業經申請人及其連帶保證人對本條約所有條款均已經合理天數詳細審閱，且已充份理解契約內容，同意與商品經銷商共同遵守<span style="">「<a href="?item=fmPeriodDeclare" target="_blank" style="text-decoration:underline;">分期付款約定書(點文字可連結閱讀詳文)</a>」之各項約定條款。</span><br>二、申請人及其連帶保證人於簽約時同意商品經銷商不另書面通知得將支付分期金額之權利及依本約定書約定所有之其他一切權利及利益轉讓與<span style="">廿一世紀數位科技有限公司</span>及其帳款收買人，受讓人對於分期付款買賣案件擁有核准與否同意權，並茲授權帳款收買人將分期付款總額或核准金額，逕行扣除手續費及相關費用，撥付與商品經銷商指定銀行帳戶，相關手續費金額之約定則按商品經銷商與<span style="">廿一世紀數位科技有限公司</span>所簽訂相關之合約約定之，申請人及其連帶保證人絕無異議。<br>三、申請人（即買方）及其連帶保證人聲明確實填寫及簽訂本「分期付款申請書暨約定書」內容，且交付商品經銷商之任何文件中並無不實之陳述或說明之情事。
								</span><br>
											<input id="check2" type="checkbox" name="check" value="" >
											<label for="check2" class="agree">我已詳細閱讀並同意以上條款及<span style="">「<a href="?item=fmPeriodDeclare" target="_blank" style="text-decoration:underline;"; >分期付款約定書(點文字可連結閱讀詳文)</a>」</span>之內容及所有條款
											</label><br>
											
											<?php
												$dault = $year['0']+1911;
												$didf = (time()-strtotime($dault."-".$year[1]."-".$year[2]))/(86400*365);
												if($didf < 20){
											?>
											<input id="check3" type="checkbox" name="check3">
											<label for="check3" class="agree">我已徵求父母或法定代理人同意分期購買此商品</label><br>
											<?php
											}
											?>
											<input id="check4" type="checkbox" name="check4" value="" style="margin-top: 15px;margin-right: -5px;">
											<label for="check4" class="agree">我已詳細閱讀並同意<a href="?item=fmFreeRespons" style="" target="_blank">免責聲明</a>、<a href="?item=fmServiceRules" style="" target="_blank">服務條款</a>、<a href="?item=fmPrivacy" style="" target="_blank">隱私權聲明</a>等條款</label><br>
										<h3>申請人中文正楷簽名：</h3>
										<canvas id="colors_sketch_1" style="border: 1px solid red;"></canvas>
										<button class="button" onclick='$("#colors_sketch_1").data("jqScribble").clear();'>清除</button>
										<a id="upload_1"><button class="button">確認簽名</button></a>
										<?php 
										if ($or_data[0]['orAppAuthenPromiseLetter'] != "") echo "<img src='".$or_data[0]['orAppAuthenPromiseLetter']."' id='orAppAuthenPromiseLetter' />";
										?>
							</div>
								
							
							<br>
							
							
							
					</div>
				</ul>
			</div>
                <div class="cart_navigation">
                    <!--<a class="prev-btn" href="#">Continue shopping</a>-->
					<a class="prev-btn" href="index.php?item=member_center&action=order_period&method=1">上一步</a>
					<?php 
						if($didf < 20){
					?>
						<a class="next-btn" id="next_1"><button >下一步</button></a>
					<?php
						}else{
					?>
						<a class="next-btn" id="next"><button >下一步</button></a>
					<?php } ?>
                </div>

        </div>
    </div>
</div>
<!-- ./page wapper-->
<script>
	$("#next").click(function(){
		if($("#check2").prop("checked")){
			if($("#check4").is(":checked")){
				$.ajax({
					url: 'php/default_order_check_file.php',
					data: $('#order_add').serialize(),
					type:"POST",
					dataType:'text',
					success: function(msg){
						if(msg == 1){
							location.href='index.php?item=member_center&action=order_period&method=3';
						}else{
							alert(msg);
						}
					},

				error:function(xhr, ajaxOptions, thrownError){ 
					alert(xhr.status); 
					alert(thrownError);
				}
				});
			}else{
				alert("請勾選同意條款");
			}
		}else{
			alert("請勾選同意條款");
		}

	});
	$("#next_1").click(function(){
		if($("input[name='check']:checked").length == 1 && $("input[name='check3']:checked").length == 1 && $("input[name='check4']:checked").length == 1){
			$.ajax({
                url: 'php/default_order_check_file.php',
                data: $('#order_add').serialize(),
                type:"POST",
                dataType:'text',
                success: function(msg){
                    if(msg == 1){
						location.href='index.php?item=member_center&action=order_period&method=3';
					}else{
						alert(msg);
					}
                },

            error:function(xhr, ajaxOptions, thrownError){ 
                alert(xhr.status); 
                alert(thrownError);
            }
        });
		}else{
			alert("請勾選同意條款及父母同意確認購買此商品");
		}
	});
	$(function () {
	var bar = $('.bar');
	var percent = $('.percent');
	var showimg = $('#showimg');
	var progress = $(".progress");
	var files = $(".files");
	var btn = $(".btn span");
	$("#fileupload").wrap("<form id='myupload' action='php/file.php' method='post' enctype='multipart/form-data'></form>");
    $("#fileupload").change(function(){
		$("#myupload").ajaxSubmit({
			dataType:  'json',
			beforeSend: function() {
        		showimg.empty();
				progress.show();
        		var percentVal = '0%';
        		bar.width(percentVal);
        		percent.html(percentVal);
				btn.html("上傳中...");
    		},
    		uploadProgress: function(event, position, total, percentComplete) {
        		var percentVal = percentComplete + '%';
        		bar.width(percentVal);
        		percent.html(percentVal);
    		},
			success: function(data) {
				var img = "https://nowait.shop/admin/file/<?php echo $memberData[0]['memNo'];?>/"+data.pic;
				showimg.html("<img src='"+img+"'>");
				btn.html("上傳檔案");
			},
			error:function(xhr){
				btn.html("上傳失敗");
				bar.width('0')
				files.html(xhr.responseText);
			}
		});
	});
	
	var bar_1 = $('.bar_1');
	var percent_1 = $('.percent_1');
	var showimg_1 = $('#showimg_1');
	var progress_1 = $(".progress_1");
	var files_1 = $(".files_1");
	var btn_1 = $(".btn_1 span");
	$("#fileupload_1").wrap("<form id='myupload_1' action='php/file_1.php' method='post' enctype='multipart/form-data'></form>");
    $("#fileupload_1").change(function(){
		$("#myupload_1").ajaxSubmit({
			dataType:  'json',
			beforeSend: function() {
        		showimg_1.empty();
				progress_1.show();
        		var percentVal = '0%';
        		bar_1.width(percentVal);
        		percent_1.html(percentVal);
				btn_1.html("上傳中...");
    		},
    		uploadProgress: function(event, position, total, percentComplete) {
        		var percentVal = percentComplete + '%';
        		bar_1.width(percentVal);
        		percent_1.html(percentVal);
    		},
			success: function(data) {
				var img = "https://nowait.shop/admin/file/<?php echo $memberData[0]['memNo'];?>/"+data.pic;
				showimg_1.html("<img src='"+img+"'>");
				btn_1.html("上傳檔案");
			},
			error:function(xhr){
				btn_1.html("上傳失敗");
				bar_1.width('0')
				files_1.html(xhr.responseText);
			}
		});
	});
	var bar_2 = $('.bar_2');
	var percent_2 = $('.percent_2');
	var showimg_2 = $('#showimg_2');
	var progress_2 = $(".progress_2");
	var files_2 = $(".files_2");
	var btn_2 = $(".btn_2 span");
	$("#fileupload_2").wrap("<form id='myupload_2' action='php/file_2.php' method='post' enctype='multipart/form-data'></form>");
    $("#fileupload_2").change(function(){
		$("#myupload_2").ajaxSubmit({
			dataType:  'json',
			beforeSend: function() {
        		showimg_2.empty();
				progress_2.show();
        		var percentVal = '0%';
        		bar_2.width(percentVal);
        		percent_2.html(percentVal);
				btn_2.html("上傳中...");
    		},
    		uploadProgress: function(event, position, total, percentComplete) {
        		var percentVal = percentComplete + '%';
        		bar_2.width(percentVal);
        		percent_2.html(percentVal);
    		},
			success: function(data) {
				var img = "https://nowait.shop/admin/file/<?php echo $memberData[0]['memNo'];?>/"+data.pic;
				showimg_2.html("<img src='"+img+"'>");
				btn_2.html("上傳檔案");
			},
			error:function(xhr){
				btn_2.html("上傳失敗");
				bar_2.width('0')
				files_2.html(xhr.responseText);
			}
		});
	});
	var bar_3 = $('.bar_3');
	var percent_3 = $('.percent_3');
	var showimg_3 = $('#showimg_3');
	var progress_3 = $(".progress_3");
	var files_3 = $(".files_3");
	var btn_3 = $(".btn_3 span");
	$("#fileupload_3").wrap("<form id='myupload_3' action='php/file_3.php' method='post' enctype='multipart/form-data'></form>");
    $("#fileupload_3").change(function(){
		$("#myupload_3").ajaxSubmit({
			dataType:  'json',
			beforeSend: function() {
        		showimg_3.empty();
				progress_3.show();
        		var percentVal = '0%';
        		bar_3.width(percentVal);
        		percent_3.html(percentVal);
				btn_3.html("上傳中...");
    		},
    		uploadProgress: function(event, position, total, percentComplete) {
        		var percentVal = percentComplete + '%';
        		bar_3.width(percentVal);
        		percent_3.html(percentVal);
    		},
			success: function(data) {
				var img = "https://nowait.shop/admin/file/<?php echo $memberData[0]['memNo'];?>/"+data.pic;
				showimg_3.html("<img src='"+img+"'>");
				btn_3.html("上傳檔案");
			},
			error:function(xhr){
				btn_3.html("上傳失敗");
				bar_3.width('0')
				files_3.html(xhr.responseText);
			}
		});
	});
	var bar_4 = $('.bar_4');
	var percent_4 = $('.percent_4');
	var showimg_4 = $('#showimg_4');
	var progress_4 = $(".progress_4");
	var files_4 = $(".files_4");
	var btn_4 = $(".btn_4 span");
	$("#fileupload_4").wrap("<form id='myupload_4' action='php/file_4.php' method='post' enctype='multipart/form-data'></form>");
    $("#fileupload_4").change(function(){
		$("#myupload_4").ajaxSubmit({
			dataType:  'json',
			beforeSend: function() {
        		showimg_4.empty();
				progress_4.show();
        		var percentVal = '0%';
        		bar_4.width(percentVal);
        		percent_4.html(percentVal);
				btn_4.html("上傳中...");
    		},
    		uploadProgress: function(event, position, total, percentComplete) {
        		var percentVal = percentComplete + '%';
        		bar_4.width(percentVal);
        		percent_4.html(percentVal);
    		},
			success: function(data) {
				var img = "https://nowait.shop/admin/file/<?php echo $memberData[0]['memNo'];?>/"+data.pic;
				showimg_4.html("<img src='"+img+"'>");
				btn_4.html("上傳檔案");
			},
			error:function(xhr){
				btn_4.html("上傳失敗");
				bar_4.width('0')
				files_4.html(xhr.responseText);
			}
		});
	});
});
</script>
<script type="text/javascript">
  $(function() {
	$('#upload').click(function(){
		$("#colors_sketch").data("jqScribble").save(function(imageData){
				
			$.post('php/file_5.php', {imagedata: imageData}, function(response){
				$('#upload button').html('簽名完成');
				$('#upload button').prop("disabled", true);
			});	
		});
	});

	$('#upload_1').click(function(){
		$("#colors_sketch_1").data("jqScribble").save(function(imageData){
				
			$.post('php/file_6.php', {imagedata: imageData}, function(response){
				$('#upload_1 button').html('簽名完成');
				$('#upload_1 button').prop("disabled", true);
			});	
		});
	});
	$('#upload_2').click(function(){
		var canvasData_2 = colors_sketch_2.toDataURL("image/png");
		var ajax = new XMLHttpRequest();
		ajax.open("POST",'php/file_7.php',false);
		ajax.setRequestHeader('Content-Type', 'application/upload');
		ajax.send(canvasData_2);
		$('#upload_2 button').html('上傳成功');
	});
  });
</script>
