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

<main role="main">
    <h1><span>分期購買</span><small>staging</small></h1>
    <section id="staging-zone">
    <form id="order_add">
        <div class="container" id="columns">
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




            <?php
            //print_r($_SESSION['shopping_user']);

            //欄位名稱
            $or = new Orders();
            $or_data = $or->getOneOrderByNo($_SESSION['ord_code']);
            $member = new Member();
            $memberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
            $year = explode('-',$memberData[0]['memBday']);
            //print_r($columnName);
            ?>



            <p>申請人基本資料<span class="text-orange">*為必填欄位，請務必詳實填寫，如未滿20歲,需父母同意分期購買。</span></p>
            <div class="section-staging bg-white">
                <p class="text-orange">*上傳證件時請確認圖檔不反光且對焦清楚，要近拍，以利案件申請</p>
                <div class="section-order-title">證件資料<span class="text-orange">*圖檔大小不得超過10MB</span></div>


                <div class="form-group row">
                    <label for="CName" class="col-sm-3 col-form-label"><span class="text-orange">*</span>申請人身分證正面</label>
                    <div class="col-sm-9">
                        <div class="demo">
                            <div class="btn">
                                <input id="fileupload" type="file" name="mypic">
<!--                                <p>說明：圖片大小不能超過10M。</p>-->
                            </div>
                            <div class="progress">
                                <span class="bar"></span><span class="percent">0%</span >
                            </div>
                            <div class="files"></div>
                            <div id="showimg">
                            <?php
                                if ($or_data[0]['orAppAuthenIdImgTop'] != "") echo "<img src='".$or_data[0]['orAppAuthenIdImgTop']."' />";
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="CName" class="col-sm-3 col-form-label"><span class="text-orange">*</span>申請人身分證反面</label>
                    <div class="col-sm-9">
                        <div class="demo">
                            <div class="btn_1">
                                <input id="fileupload_1" type="file" name="mypic_1">
                            </div>
                            <div class="progress_1">
                                <span class="bar_1"></span><span class="percent_1">0%</span >
                            </div>
                            <div class="files_1"></div>
                            <div id="showimg_1">
                            <?php
                                if ($or_data[0]['orAppAuthenIdImgBot'] != "") echo "<img src='".$or_data[0]['orAppAuthenIdImgBot']."' />";
                            ?>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="CName" class="col-sm-3 col-form-label"><span class="text-orange">*</span>學生證/ 軍人證正面上傳</label>
                    <div class="col-sm-9">
                        <div class="demo">
                            <div class="btn_2">
                                <input id="fileupload_2" type="file" name="mypic_2" class="form-control">
                            </div>
                            <div class="progress_2">
                                <span class="bar_2"></span><span class="percent_2">0%</span >
                            </div>
                            <div class="files_2"></div>
                            <div id="showimg_2">
                                <?php
                                if ($or_data[0]['orAppAuthenStudentIdImgTop'] != "") echo "<img src='".$or_data[0]['orAppAuthenStudentIdImgTop']."' />";
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="CName" class="col-sm-3 col-form-label"><span class="text-orange">*</span>學生證/軍人證背面上傳</label>
                    <div class="col-sm-9">
                        <div class="demo">
                            <div class="btn_3">
                                <input id="fileupload_3" type="file" name="mypic_3" class="form-control">
                            </div>
                            <div class="progress_3">
                                <span class="bar_3"></span><span class="percent_3">0%</span >
                            </div>
                            <div class="files_3"></div>
                            <div id="showimg_3">
                                <?php
                                if ($or_data[0]['orAppAuthenStudentIdImgBot'] != "") echo "<img src='".$or_data[0]['orAppAuthenStudentIdImgBot']."' />";
                                ?>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="CName" class="col-sm-3 col-form-label"><span class="text-orange">*</span>申請自拍照上傳</label>
                    <div class="col-sm-9">
                        <div class="demo">
                            <div class="btn_7">
                                <input id="fileupload_7" type="file" name="mypic_7" class="form-control">
                            </div>
                            <div class="progress_7">
                                <span class="bar_7"></span><span class="percent_7">0%</span >
                            </div>
                            <div class="files_7"></div>
                            <div id="showimg_7">
                                <?php
                                if ($or_data[0]['orAppAuthenStudentIdImgBot'] != "") echo "<img src='".$or_data[0]['orAppAuthenSelfImgTop']."' />";
                                ?>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="CName" class="col-sm-3 col-form-label"><span class="text-orange">*</span>補件資料上傳</label>
                    <div class="col-sm-9">
                        <div class="demo">
                            <div class="btn_4">
                                <input id="fileupload_4" type="file" name="mypic_4" class="form-control">
                            </div>
                            <div class="progress_4">
                                <span class="bar_4"></span><span class="percent_4">0%</span >
                            </div>
                            <div class="files_4"></div>
                            <div id="showimg_4">
                                <?php
                                if ($or_data[0]['orAppAuthenExtraInfo'] != "") echo "<img src='".$or_data[0]['orAppAuthenExtraInfo']."' />";
                                ?>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <div class="section-staging bg-white">
                <div class="section-order-title">請在下方兩處，以滑鼠或手寫功能簽上正楷簽名</div>
                <p>本票： 憑票於中華民國 年 月 日無條件支付 大方藝彩行銷顧問股份有限公司或指定人
                    <br>新台幣　
                    <span style=""><?php
                        $ex = preg_split("//", $or_data[0]['orPeriodTotal']);
                        $ln = strlen($or_data[0]['orPeriodTotal']);
                        $count = count($ex);
                        echo ($ln > 5) ? $coin[$ex[1]]:'零';
                        ?>
                    </span>
                    <span>　
                        拾
                        <span style=""><?php echo ($ln == 5) ? $coin[$ex[1]]:'零'; ?></span>
                        <span>
                        &nbsp;拾&nbsp;
                        <span style=""><?php echo ($ln == 5) ? $coin[$ex[1]]:'零'; ?></span>
                        &nbsp;萬&nbsp;
                        <span style=""><?php echo $coin[$ex[2]]; ?></span>
                        &nbsp;仟&nbsp;
                        <span style=""><?php echo $coin[$ex[3]]; ?></span>
                        &nbsp;佰&nbsp;
                        <span style=""><?php echo $coin[$ex[4]]; ?></span>
                        &nbsp;拾&nbsp;
                        <span style=""><?php echo $coin[$ex[5]]; ?></span>
                    　  元整<br>
                    <br>此總額為您選擇之月付金X期數
                    <br>此本票免除作成拒絕證書及票據法第八十九條之通知義務，
                    <br>利息自到期日迄清償日止按年利率百分之二十計付，
                    <br>發票人授權持票人得填載到期日。
                    <br>付款地：桃園市桃園區文中路493號4樓
                    <br>此據
                    <br>中華民國 <?php echo date('Y',strtotime($or_data[0]['orDate']))-1911; ?>  年 <?php echo date('m',strtotime($or_data[0]['orDate'])); ?> 月 <?php echo date('d',strtotime($or_data[0]['orDate'])); ?>  日
                    <br>約定說明：「此本票係供為分期付款買賣之分期款項總額憑證，俟分期付款完全清償完畢時，此本票自動失效，但如有一期未付，發票人願意就全部本票債務負責清償。」本人同意依法令規定應以書面為之者,得以電子文件為之.依法令規定應簽名或蓋章者，得以電子簽章為之。 </p>
                <p>發票人中文正楷簽名</p>
<!--                <div class="sign-zone"></div>-->
                <canvas id="colors_sketch" style="border: 1px solid red;"></canvas>
                <div class="form-group form-btn text-right">
                    <button class="btn bg-gray button" onclick='$("#colors_sketch").data("jqScribble").clear();'>清除</button>
                    <a id="upload"><button class="btn bg-yellow button">確認簽名</button></a>
                    <?php
                    if ($or_data[0]['orAppAuthenProvement'] != "") echo "<img src='".$or_data[0]['orAppAuthenProvement']."' id='orAppAuthenProvement' />";
                    ?>
                </div>
                <div class="section-order-title"></div>
                <p>★分期付款期間未繳清以前禁止出售或典當，以免觸法<br>分期付款約定事項： 一、 申請人(即買方)及其連帶保證人向商品經銷商(即賣方)以分期付款方式購買消費性商品，並簽約本「分期付款申請書暨約定書」，業經申請人及其連帶保證人對本條約所有條款均已經合理天數詳細審閱，且已充份理解契約內容，同意與商品經銷商共同遵守「分期付款約定書(點文字可連結閱讀詳文)」之各項約定條款。<br>二、申請人及其連帶保證人於簽約時同意商品經銷商不另書面通知得將支付分期金額之權利及依本約定書約定所有之其他一切權利及利益轉讓與廿一世紀數位科技有限公司及其帳款收買人，受讓人對於分期付款買賣案件擁有核准與否同意權，並茲授權帳款收買人將分期付款總額或核准金額，逕行扣除手續費及相關費用，撥付與商品經銷商指定銀行帳戶，相關手續費金額之約定則按商品經銷商與 大方藝彩行銷顧問股份有限公司所簽訂相關之合約約定之，申請人及其連帶保證人絕無異議。<br>三、申請人（即買方）及其連帶保證人聲明確實填寫及簽訂本「分期付款申請書暨約定書」內容，且交付商品經銷商之任何文件中並無不實之陳述或說明之情事。 </p>
                <div class="form-check text-left m-2">
                    <input class="form-check-input" type="checkbox" id="check2" name="check" value="" >
                    <label class="form-check-label agree" for="check2">
                        我已詳細閱讀並同意以上條款及
                        <a href="?item=fmPeriodDeclare" target="_blank" class="text-orange" style="text-decoration:underline;">「分期付款約定書(點文字可連結閱讀詳文)」</a>
                        之內容及所有條款
                    </label>
                    <?php
                    if ($or_data[0]['orAppAuthenProvement'] != "") echo "<img src='".$or_data[0]['orAppAuthenProvement']."' id='orAppAuthenProvement' />";
                    ?>          
                </div>
                <div class="form-check text-left m-2">
                    <input class="form-check-input check4" type="checkbox" id="check4">
                    <label class="form-check-label agree" for="check4">
                        我已詳細閱讀並同意
                        <a href="?item=fmFreeRespons" class="text-orange">免責聲明</a>、
                        <a href="?item=fmServiceRules" class="text-orange">服務條款</a>、
                        <a href="?item=fmPrivacy" class="text-orange">隱私權聲明</a>
                        等條款
                    </label>
                </div>
                <p>申請人中文正楷簽名</p>
<!--                <div class="sign-zone"></div>-->
                <canvas id="colors_sketch_1" style="border: 1px solid red;"></canvas>
                <div class="form-group form-btn text-right">
                    <button class="btn bg-gray button" onclick='$("#colors_sketch_1").data("jqScribble").clear();'>清除</button>
                    <a id="upload_1"><button class="btn bg-yellow button">確認簽名</button></a>
                    <?php
                    if ($or_data[0]['orAppAuthenPromiseLetter'] != "") echo "<img src='".$or_data[0]['orAppAuthenPromiseLetter']."' id='orAppAuthenPromiseLetter' />";
                    ?>
                </div>
            </div>
            <div class="section-order">
                <div class="form-group form-btn text-center">
                    <a href="index.php?item=member_center&action=order_period&method=1" class="btn btn-prev bg-gray">上一步</a>
<!--                    <a href="staging-3.htm" class="btn btn-next bg-yellow">下一步</a>-->
                    <a class="btn btn-next bg-yellow next-btn" id="next">下一步</a>
                </div>
            </div>
        </div>
    </form>
    </section>
</main>


<!-- ./page wapper-->
<script>
    $("#next").click(function(e){
        if($("#check2").prop("checked")){
            if($("#check4").is(":checked")){
                $.ajax({
                    url: 'portal/Controllers/php/order_check_file.php',
                    data: $('#order_add').serialize(),
                    type:"POST",
                    dataType:'text',
                    success: function(msg){
                        if(msg == 1){
                            location.href='index.php?item=member_center&action=order_period&method=3';
                        }else{
                            alert(msg);
                        }
                        e.preventDefault();
                        return false;
                    },
                    error:function(xhr, ajaxOptions, thrownError){
                        alert(xhr.status);
                        alert(thrownError);
                        return false;
                    }
                });
            }else{
                alert("請勾選同意條款");
                return false;
            }
        }else{
            alert("請勾選同意條款");
            return false;
        }
    });
    //20歲以下,請勾選同意條款及父母同意確認購買此商品
    // $("#next_1").click(function(){
    //     if($("input[name='check']:checked").length == 1 && $("input[name='check3']:checked").length == 1 && $("input[name='check4']:checked").length == 1){
    //         $.ajax({
    //             url: 'php/order_check_file.php',
    //             data: $('#order_add').serialize(),
    //             type:"POST",
    //             dataType:'text',
    //             success: function(msg){
    //                 if(msg == 1){
    //                     location.href='index.php?item=member_center&action=order_period&method=3';
    //                 }else{
    //                     alert(msg);
    //                 }
    //             },
    //
    //             error:function(xhr, ajaxOptions, thrownError){
    //                 alert(xhr.status);
    //                 alert(thrownError);
    //             }
    //         });
    //     }else{
    //         alert("請勾選同意條款及父母同意確認購買此商品");
    //     }
    // });
    $(function () {
        var bar = $('.bar');
        var percent = $('.percent');
        var showimg = $('#showimg');
        var progress = $(".progress");
        var files = $(".files");
        var btn = $(".btn span");
        $("#fileupload").wrap("<form id='myupload' action='portal/Controllers/php/file.php' method='post' enctype='multipart/form-data'></form>");
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
                        var img = data.url+"/admin/file/<?php echo $memberData[0]['memNo'];?>/"+data.pic;
                        showimg.html("<img src='"+img+"'>");
                        btn.html("上傳檔案");
                    }else{
                        btn.html("上傳失敗");
                        bar.width('0');
                        files.html(xhr.responseText);
                    }
                },
                error:function(xhr){
                    btn.html("上傳失敗");
                    bar.width('0');
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
        $("#fileupload_1").wrap("<form id='myupload_1' action='portal/Controllers/php/file_1.php' method='post' enctype='multipart/form-data'></form>");
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
        $("#fileupload_2").wrap("<form id='myupload_2' action='portal/Controllers/php/file_2.php' method='post' enctype='multipart/form-data'></form>");
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
        $("#fileupload_3").wrap("<form id='myupload_3' action='portal/Controllers/php/file_3.php' method='post' enctype='multipart/form-data'></form>");
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
        $("#fileupload_4").wrap("<form id='myupload_4' action='portal/Controllers/php/file_4.php' method='post' enctype='multipart/form-data'></form>");
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
        //申請自拍照上傳
        var bar_7 = $('.bar_7');
        var percent_7 = $('.percent_7');
        var showimg_7 = $('#showimg_7');
        var progress_7 = $(".progress_7");
        var files_7 = $(".files_7");
        var btn_7 = $(".btn_7 span");
        $("#fileupload_7").wrap("<form id='myupload_7' action='portal/Controllers/php/file_7.php' method='post' enctype='multipart/form-data'></form>");
        $("#fileupload_7").change(function(){
            $("#myupload_7").ajaxSubmit({
                dataType:  'json',
                beforeSend: function() {
                    showimg_7.empty();
                    progress_7.show();
                    var percentVal = '0%';
                    bar_7.width(percentVal);
                    percent_7.html(percentVal);
                    btn_7.html("上傳中...");
                },
                uploadProgress: function(event, position, total, percentComplete) {
                    var percentVal = percentComplete + '%';
                    bar_7.width(percentVal);
                    percent_7.html(percentVal);
                },
                success: function(data) {
                    var img = "https://happyfan7.com/admin/file/<?php echo $memberData[0]['memNo'];?>/"+data.pic;
                    showimg_7.html("<img src='"+img+"'>");
                    btn_7.html("上傳檔案");
                },
                error:function(xhr){
                    btn_7.html("上傳失敗");
                    bar_7.width('0')
                    files_7.html(xhr.responseText);
                }
            });
        });
    });
</script>
<script type="text/javascript">
    $(function() {
        //發票人中文正楷簽名
        $('#upload').click(function(){
            $("#colors_sketch").data("jqScribble").save(function(imageData){

                $.post('portal/Controllers/php/file_5.php', {imagedata: imageData}, function(response){
                    $('#upload button').html('簽名完成');
                    $('#upload button').prop("disabled", true);
                    $('#orAppAuthenProvement').hide();
                });
            });
        });
        //申請人中文正楷簽名
        $('#upload_1').click(function(){
            $("#colors_sketch_1").data("jqScribble").save(function(imageData){

                $.post('portal/Controllers/php/file_6.php', {imagedata: imageData}, function(response){
                    $('#upload_1 button').html('簽名完成');
                    $('#upload_1 button').prop("disabled", true);
                    $('#orAppAuthenPromiseLetter').hide();

                });
            });
        });
        //
        // $('#upload_2').click(function(){
        //     var canvasData_2 = colors_sketch_2.toDataURL("image/png");
        //     var ajax = new XMLHttpRequest();
        //     ajax.open("POST",'portal/Controllers/php/file_7.php',false);
        //     ajax.setRequestHeader('Content-Type', 'application/upload');
        //     ajax.send(canvasData_2);
        //     $('#upload_2 button').html('上傳成功');
        // });
    });
</script>
