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

<main role="main">
    <h1><span>分期購買</span><small>staging</small></h1>
    <section id="staging-zone">
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-lg-8">
                    <div class="row bs-wizard">
                        <div class="col-3 bs-wizard-step complete">
                            <div class="progress"><div class="progress-bar"></div></div>
                            <a href="#" class="bs-wizard-dot">1</a>
                            <div class="desc">填寫基本資料</div>
                        </div>
                        <div class="col-3 bs-wizard-step active">
                            <div class="progress"><div class="progress-bar"></div></div>
                            <a href="#" class="bs-wizard-dot">2</a>
                            <div class="desc">簽名及附件上傳</div>
                        </div>
                        <div class="col-3 bs-wizard-step disabled">
                            <div class="progress"><div class="progress-bar"></div></div>
                            <a href="#" class="bs-wizard-dot">3</a>
                            <div class="desc">確認訂單資訊</div>
                        </div>
                        <div class="col-3 bs-wizard-step disabled">
                            <div class="progress"><div class="progress-bar"></div></div>
                            <a href="#" class="bs-wizard-dot">4</a>
                            <div class="desc">完成等候照會</div>
                        </div>
                    </div>
                </div>
            </div>
            <p>申請人基本資料<span class="text-orange">*為必填欄位，請務必詳實填寫，如未滿20歲,需父母同意分期購買。</span></p>
            <div class="section-staging bg-white">
                <p class="text-orange">*上傳證件時請確認圖檔不反光且對焦清楚，要近拍，以利案件申請</p>
                <div class="section-order-title">證件資料<span class="text-orange">*圖檔大小不得超過10MB</span></div>
                <div class="form-group row">
                    <label for="CName" class="col-sm-3 col-form-label"><span class="text-orange">*</span>學生證/ 軍人證正面上傳</label>
                    <div class="col-sm-9">
                        <input type="file" class="form-control" id="customFile">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="CName" class="col-sm-3 col-form-label"><span class="text-orange">*</span>學生證/軍人證背面上傳</label>
                    <div class="col-sm-9">
                        <input type="file" class="form-control" id="customFile">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="CName" class="col-sm-3 col-form-label"><span class="text-orange">*</span>申請自拍照上傳</label>
                    <div class="col-sm-9">
                        <input type="file" class="form-control" id="customFile">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="CName" class="col-sm-3 col-form-label"><span class="text-orange">*</span>補件資料上傳</label>
                    <div class="col-sm-9">
                        <input type="file" class="form-control" id="customFile">
                    </div>
                </div>
            </div>
            <div class="section-staging bg-white">
                <div class="section-order-title">請在下方兩處，以滑鼠或手寫功能簽上正楷簽名</div>
                <p>本票： 憑票於中華民國 年 月 日無條件支付 大方藝彩行銷顧問股份有限公司或指定人
                    <br>新台幣　 零  拾  柒  萬  肆  仟  陸  佰  陸  拾  肆   　元整
                    <br>此總額為您選擇之月付金X期數
                    <br>此本票免除作成拒絕證書及票據法第八十九條之通知義務，
                    <br>利息自到期日迄清償日止按年利率百分之二十計付，
                    <br>發票人授權持票人得填載到期日。
                    <br>付款地：桃園市桃園區文中路493號4樓
                    <br>此據
                    <br>中華民國 107 年 10 月 02 日
                    <br>約定說明：「此本票係供為分期付款買賣之分期款項總額憑證，俟分期付款完全清償完畢時，此本票自動失效，但如有一期未付，發票人願意就全部本票債務負責清償。」本人同意依法令規定應以書面為之者,得以電子文件為之.依法令規定應簽名或蓋章者，得以電子簽章為之。 </p>
                <p>發票人中文正楷簽名</p>
                <div class="sign-zone"></div>
                <div class="form-group form-btn text-right">
                    <button class="btn bg-gray">上一步</button>
                    <button class="btn bg-yellow">確認簽名</button>
                </div>
                <div class="section-order-title"></div>
                <p>★分期付款期間未繳清以前禁止出售或典當，以免觸法<br>分期付款約定事項： 一、 申請人(即買方)及其連帶保證人向商品經銷商(即賣方)以分期付款方式購買消費性商品，並簽約本「分期付款申請書暨約定書」，業經申請人及其連帶保證人對本條約所有條款均已經合理天數詳細審閱，且已充份理解契約內容，同意與商品經銷商共同遵守「分期付款約定書(點文字可連結閱讀詳文)」之各項約定條款。<br>二、申請人及其連帶保證人於簽約時同意商品經銷商不另書面通知得將支付分期金額之權利及依本約定書約定所有之其他一切權利及利益轉讓與廿一世紀數位科技有限公司及其帳款收買人，受讓人對於分期付款買賣案件擁有核准與否同意權，並茲授權帳款收買人將分期付款總額或核准金額，逕行扣除手續費及相關費用，撥付與商品經銷商指定銀行帳戶，相關手續費金額之約定則按商品經銷商與 大方藝彩行銷顧問股份有限公司所簽訂相關之合約約定之，申請人及其連帶保證人絕無異議。<br>三、申請人（即買方）及其連帶保證人聲明確實填寫及簽訂本「分期付款申請書暨約定書」內容，且交付商品經銷商之任何文件中並無不實之陳述或說明之情事。 </p>
                <div class="form-check text-left m-2">
                    <input class="form-check-input" type="checkbox" id="FieldsetCheck">
                    <label class="form-check-label" for="FieldsetCheck">我已詳細閱讀並同意以上條款及<a href="#" class="text-orange">「分期付款約定書(點文字可連結閱讀詳文)」</a>之內容及所有條款</label>
                </div>
                <div class="form-check text-left m-2">
                    <input class="form-check-input" type="checkbox" id="FieldsetCheck">
                    <label class="form-check-label" for="FieldsetCheck">我已詳細閱讀並同意<a href="#" class="text-orange">免責聲明</a>、<a href="#" class="text-orange">服務條款</a>、<a href="#" class="text-orange">隱私權聲明</a>等條款</label>
                </div>
                <p>申請人中文正楷簽名</p>
                <div class="sign-zone"></div>
                <div class="form-group form-btn text-right">
                    <button class="btn bg-gray">上一步</button>
                    <button class="btn bg-yellow">確認簽名</button>
                </div>
            </div>
            <div class="section-order">
                <div class="form-group form-btn text-center">
                    <a href="staging-1.htm" class="btn btn-prev bg-gray">上一步</a>
                    <a href="staging-3.htm" class="btn btn-next bg-yellow">下一步</a>
                </div>
            </div>
        </div>
    </section>
</main>


<!-- ./page wapper-->
<script>
    $("#next").click(function(){
        if($("#check2").prop("checked")){
            if($("#check4").is(":checked")){
                $.ajax({
                    url: 'php/order_check_file.php',
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
                url: 'php/order_check_file.php',
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
                    if(data.pic != ''){
                        var img = "https://happyfan7.com/admin/file/<?php echo $memberData[0]['memNo'];?>/"+data.pic;
                        showimg.html("<img src='"+img+"'>");
                        btn.html("上傳檔案");
                    }else{
                        btn.html("上傳失敗");
                        bar.width('0')
                        files.html(xhr.responseText);
                    }
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
                    if(data.pic != ''){
                        var img = "https://happyfan7.com/admin/file/<?php echo $memberData[0]['memNo'];?>/"+data.pic;
                        showimg_1.html("<img src='"+img+"'>");
                        btn_1.html("上傳檔案");
                    }else{
                        btn_1.html("上傳失敗");
                        bar_1.width('0')
                        files_1.html(xhr.responseText);
                    }
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
                    if(data.pic != ''){
                        var img = "https://happyfan7.com/admin/file/<?php echo $memberData[0]['memNo'];?>/"+data.pic;
                        showimg_2.html("<img src='"+img+"'>");
                        btn_2.html("上傳檔案");
                    }else{
                        btn_2.html("上傳失敗");
                        bar_2.width('0')
                        files_2.html(xhr.responseText);
                    }
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
                    if(data.pic != ''){
                        var img = "https://happyfan7.com/admin/file/<?php echo $memberData[0]['memNo'];?>/"+data.pic;
                        showimg_3.html("<img src='"+img+"'>");
                        btn_3.html("上傳檔案");
                    }else{
                        btn_3.html("上傳失敗");
                        bar_3.width('0')
                        files_3.html(xhr.responseText);
                    }
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
                    var img = "https://happyfan7.com/admin/file/<?php echo $memberData[0]['memNo'];?>/"+data.pic;
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
                    $('#orAppAuthenProvement').hide();
                });
            });
        });

        $('#upload_1').click(function(){
            $("#colors_sketch_1").data("jqScribble").save(function(imageData){

                $.post('php/file_6.php', {imagedata: imageData}, function(response){
                    $('#upload_1 button').html('簽名完成');
                    $('#upload_1 button').prop("disabled", true);
                    $('#orAppAuthenPromiseLetter').hide();

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
